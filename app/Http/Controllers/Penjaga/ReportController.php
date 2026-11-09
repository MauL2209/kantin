<?php

namespace App\Http\Controllers\Penjaga;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Display sales report for the penjaga's own stand.
     *
     * AC 6.1: Show total revenue, order count, and top products filtered by period.
     * AC 6.2: Data is always scoped to the authenticated penjaga's stand.
     * AC 6.3: If a foreign stand_id is passed via query param, redirect back to own report
     *         with a warning — not abort(403).
     */
    public function index(Request $request)
    {
        $user   = Auth::user();
        $standId = $user->stand_id;

        // AC 6.3: Reject any attempt to view another stand's report
        if ($request->filled('stand_id') && (int) $request->stand_id !== (int) $standId) {
            return redirect()->route('penjaga.reports.index')
                ->with('warning', 'Akses ditolak. Anda hanya dapat melihat laporan Stand milik Anda sendiri.');
        }

        // Period filter — default: today
        $period = $request->get('period', 'today');
        $now    = Carbon::now();

        switch ($period) {
            case 'this_week':
                $startDate = $now->copy()->startOfWeek();
                $endDate   = $now->copy()->endOfWeek();
                break;
            case 'this_month':
                $startDate = $now->copy()->startOfMonth();
                $endDate   = $now->copy()->endOfMonth();
                break;
            case 'today':
            default:
                $period    = 'today';
                $startDate = $now->copy()->startOfDay();
                $endDate   = $now->copy()->endOfDay();
                break;
        }

        // Base order scope: orders that contain at least one product from this stand,
        // within the selected period, excluding cancelled orders.
        $baseOrderQuery = fn () => Order::whereHas(
            'orderDetails',
            fn ($q) => $q->whereHas('product', fn ($p) => $p->where('stand_id', $standId))
        )
            ->where('status', '!=', 'Dibatalkan')
            ->whereBetween('created_at', [$startDate, $endDate]);

        // AC 6.1: Total revenue — sum only the subtotals for THIS stand's items
        $totalRevenue = OrderDetail::whereHas('product', fn ($q) => $q->where('stand_id', $standId))
            ->whereHas('order', fn ($q) => $q
                ->where('status', '!=', 'Dibatalkan')
                ->whereBetween('created_at', [$startDate, $endDate])
            )
            ->sum('subtotal');

        // AC 6.1: Total orders containing at least one product from this stand
        $totalOrders = $baseOrderQuery()->count();

        // AC 6.1: Top 5 selling products from this stand by quantity sold
        $topProducts = OrderDetail::select(
            'product_id',
            DB::raw('SUM(quantity) as total_qty'),
            DB::raw('SUM(subtotal) as total_amount')
        )
            ->whereHas('product', fn ($q) => $q->where('stand_id', $standId))
            ->whereHas('order', fn ($q) => $q
                ->where('status', '!=', 'Dibatalkan')
                ->whereBetween('created_at', [$startDate, $endDate])
            )
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->with('product')
            ->take(5)
            ->get();

        // Recent orders for the table listing
        $orders = $baseOrderQuery()
            ->with(['orderDetails.product', 'user'])
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('penjaga.reports.index', compact(
            'period',
            'startDate',
            'endDate',
            'totalRevenue',
            'totalOrders',
            'topProducts',
            'orders',
        ));
    }
}
