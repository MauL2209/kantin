<?php

namespace App\Http\Controllers\Penjaga;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show the penjaga dashboard with today's stats scoped to their stand.
     */
    public function index()
    {
        $user = Auth::user()->load('stand');

        // Guard: penjaga belum ditugaskan ke stand manapun
        if ($user->stand_id === null) {
            return view('penjaga.dashboard', [
                'user'           => $user,
                'todayOrders'    => null,
                'todayRevenue'   => null,
                'activeProducts' => null,
                'noStand'        => true,
            ]);
        }

        $standId = $user->stand_id;
        $today   = today();

        // Jumlah pesanan hari ini yang mengandung produk dari stand milik penjaga
        $todayOrders = Order::whereDate('created_at', $today)
            ->where('status', '!=', 'Dibatalkan')
            ->whereHas('orderDetails', function ($q) use ($standId) {
                $q->whereHas('product', fn($p) => $p->where('stand_id', $standId));
            })
            ->count();

        // Total pendapatan hari ini dari stand milik penjaga
        // Menjumlahkan subtotal order_details yang produknya berasal dari stand ini,
        // hanya dari pesanan yang tidak dibatalkan dan dibuat hari ini.
        $todayRevenue = \App\Models\OrderDetail::whereHas('order', function ($q) use ($today) {
                $q->whereDate('created_at', $today)
                  ->where('status', '!=', 'Dibatalkan');
            })
            ->whereHas('product', fn($q) => $q->where('stand_id', $standId))
            ->sum('subtotal');

        // Jumlah produk aktif (available) milik stand ini
        $activeProducts = Product::where('stand_id', $standId)
            ->where('status', 'available')
            ->count();

        return view('penjaga.dashboard', compact(
            'user',
            'todayOrders',
            'todayRevenue',
            'activeProducts'
        ));
    }
}
