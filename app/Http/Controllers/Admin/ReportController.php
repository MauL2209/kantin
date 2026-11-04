<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Stand;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    /**
     * Display sales report with various filters.
     */
    public function index(Request $request)
    {
        $stands = Stand::all();
        $filter = $request->get('period', 'this_month');

        $query = Order::with(['orderDetails.product.stand', 'payment'])
            ->where('status', '!=', 'Dibatalkan');

        // Period Filtering
        $now = Carbon::now();
        $startDate = null;
        $endDate = null;

        switch ($filter) {
            case 'today':
                $startDate = $now->copy()->startOfDay();
                $endDate = $now->copy()->endOfDay();
                break;
            case 'this_week':
                $startDate = $now->copy()->startOfWeek();
                $endDate = $now->copy()->endOfWeek();
                break;
            case 'custom':
                if ($request->filled('start_date') && $request->filled('end_date')) {
                    $startDate = Carbon::parse($request->start_date)->startOfDay();
                    $endDate = Carbon::parse($request->end_date)->endOfDay();
                }
                break;
            case 'this_month':
            default:
                $filter = 'this_month';
                $startDate = $now->copy()->startOfMonth();
                $endDate = $now->copy()->endOfMonth();
                break;
        }

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        // Filter by Stand
        if ($request->filled('stand_id')) {
            $standId = $request->stand_id;
            $query->whereHas('orderDetails.product', function ($q) use ($standId) {
                $q->where('stand_id', $standId);
            });
        }

        $orders = $query->latest()->get();

        // Key Metrics
        $totalRevenue = $orders->sum('total_price');
        $totalOrders = $orders->count();
        $avgOrderValue = $totalOrders > 0 ? (int)($totalRevenue / $totalOrders) : 0;

        // Top Selling Products in the selected period
        $topProductsQuery = OrderDetail::select('product_id', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(subtotal) as total_amount'))
            ->whereHas('order', function ($q) use ($startDate, $endDate) {
                $q->where('status', '!=', 'Dibatalkan');
                if ($startDate && $endDate) {
                    $q->whereBetween('created_at', [$startDate, $endDate]);
                }
            })
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->with(['product.stand'])
            ->take(5);

        if ($request->filled('stand_id')) {
            $standId = $request->stand_id;
            $topProductsQuery->whereHas('product', function ($q) use ($standId) {
                $q->where('stand_id', $standId);
            });
        }

        $topProducts = $topProductsQuery->get();

        // Top Stands by Revenue
        $standsReport = Stand::with(['products.orderDetails' => function ($q) use ($startDate, $endDate) {
            $q->whereHas('order', function ($o) use ($startDate, $endDate) {
                $o->where('status', '!=', 'Dibatalkan');
                if ($startDate && $endDate) {
                    $o->whereBetween('created_at', [$startDate, $endDate]);
                }
            });
        }])->get()->map(function ($stand) {
            $revenue = 0;
            $itemsSold = 0;
            foreach ($stand->products as $p) {
                foreach ($p->orderDetails as $od) {
                    $revenue += $od->subtotal;
                    $itemsSold += $od->quantity;
                }
            }
            return [
                'name' => $stand->name,
                'department' => $stand->department,
                'revenue' => $revenue,
                'items_sold' => $itemsSold,
            ];
        })->sortByDesc('revenue');

        return view('admin.reports.index', compact(
            'orders',
            'stands',
            'filter',
            'startDate',
            'endDate',
            'totalRevenue',
            'totalOrders',
            'avgOrderValue',
            'topProducts',
            'standsReport'
        ));
    }

    /**
     * Export Report to CSV.
     */
    public function exportCsv(Request $request)
    {
        $filter = $request->get('period', 'this_month');
        $query = Order::with(['orderDetails.product.stand', 'payment'])
            ->where('status', '!=', 'Dibatalkan');

        $now = Carbon::now();
        if ($filter === 'today') {
            $query->whereDate('created_at', Carbon::today());
        } elseif ($filter === 'this_week') {
            $query->whereBetween('created_at', [$now->copy()->startOfWeek(), $now->copy()->endOfWeek()]);
        } elseif ($filter === 'custom' && $request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay()
            ]);
        } else {
            $query->whereBetween('created_at', [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()]);
        }

        if ($request->filled('stand_id')) {
            $standId = $request->stand_id;
            $query->whereHas('orderDetails.product', function ($q) use ($standId) {
                $q->where('stand_id', $standId);
            });
        }

        $orders = $query->latest()->get();

        $filename = 'laporan-penjualan-kantin-melatiarea-' . date('Ymd-His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return new StreamedResponse(function () use ($orders) {
            $handle = fopen('php://output', 'w');
            // Add UTF-8 BOM for Indonesian Excel compatibility
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            // CSV Header
            fputcsv($handle, [
                'No. Pesanan',
                'Tanggal',
                'Nama Pemesan',
                'NIS',
                'Kelas',
                'Lokasi / Ruang',
                'Metode Pembayaran',
                'Status',
                'Total Harga (Rp)',
                'Daftar Menu',
            ]);

            foreach ($orders as $order) {
                $menuItems = $order->orderDetails->map(function ($d) {
                    return ($d->product->name ?? 'Menu') . ' (' . $d->quantity . 'x)';
                })->implode(', ');

                fputcsv($handle, [
                    $order->order_number,
                    $order->created_at->format('Y-m-d H:i'),
                    $order->customer_name,
                    $order->customer_nis ?? '-',
                    $order->customer_class,
                    $order->customer_room,
                    strtoupper($order->payment_method),
                    $order->status,
                    $order->total_price,
                    $menuItems,
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }

    /**
     * Printable report view.
     */
    public function print(Request $request)
    {
        $filter = $request->get('period', 'this_month');
        $query = Order::with(['orderDetails.product.stand', 'payment'])
            ->where('status', '!=', 'Dibatalkan');

        $now = Carbon::now();
        if ($filter === 'today') {
            $query->whereDate('created_at', Carbon::today());
        } elseif ($filter === 'this_week') {
            $query->whereBetween('created_at', [$now->copy()->startOfWeek(), $now->copy()->endOfWeek()]);
        } elseif ($filter === 'custom' && $request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay()
            ]);
        } else {
            $query->whereBetween('created_at', [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()]);
        }

        $orders = $query->latest()->get();
        $totalRevenue = $orders->sum('total_price');
        $totalOrders = $orders->count();

        return view('admin.reports.print', compact('orders', 'totalRevenue', 'totalOrders', 'filter'));
    }
}

