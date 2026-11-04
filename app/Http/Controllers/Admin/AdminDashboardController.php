<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Stand;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    /**
     * Display modern admin dashboard.
     */
    public function index()
    {
        $today = Carbon::today();

        // 1. Stat cards metrics
        $todayRevenue = Order::whereDate('created_at', $today)
            ->where('status', '!=', 'Dibatalkan')
            ->sum('total_price');

        $todayOrdersCount = Order::whereDate('created_at', $today)->count();

        $totalProducts = Product::count();
        $totalStands = Stand::count();

        $processingOrdersCount = Order::where('status', 'Sedang Diproses')->count();
        $readyOrdersCount = Order::where('status', 'Siap Diambil')->count();

        // 2. Daily Sales Chart (Last 7 days)
        $dates = [];
        $salesData = [];
        for ($i = 6; $i >= 0; $i--) {
            $day = Carbon::today()->subDays($i);
            $dayLabel = $day->translatedFormat('d M');
            $dates[] = $dayLabel;

            $daySum = Order::whereDate('created_at', $day)
                ->where('status', '!=', 'Dibatalkan')
                ->sum('total_price');

            $salesData[] = (int) $daySum;
        }

        // 3. Top 5 Best-Selling Products Chart
        $topProducts = OrderDetail::select('product_id', DB::raw('SUM(quantity) as total_sold'))
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->with('product')
            ->take(5)
            ->get();

        $topProductLabels = [];
        $topProductData = [];
        foreach ($topProducts as $item) {
            $topProductLabels[] = $item->product->name ?? 'Produk';
            $topProductData[] = (int) $item->total_sold;
        }

        // Fallback for chart if no order details yet
        if (empty($topProductLabels)) {
            $topProductLabels = ['Nasi Goreng RPL', 'Kopi Susu Aren', 'Mie Goreng Logistik', 'Ayam Geprek', 'Dimsum Udang'];
            $topProductData = [12, 10, 8, 6, 4];
        }

        // 4. Recent Orders (Latest 10 orders)
        $recentOrders = Order::with(['orderDetails.product.stand', 'user'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'todayRevenue',
            'todayOrdersCount',
            'totalProducts',
            'totalStands',
            'processingOrdersCount',
            'readyOrdersCount',
            'dates',
            'salesData',
            'topProductLabels',
            'topProductData',
            'recentOrders'
        ));
    }
}

