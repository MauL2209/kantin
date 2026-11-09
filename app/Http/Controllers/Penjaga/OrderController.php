<?php

namespace App\Http\Controllers\Penjaga;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Valid status transitions for penjaga.
     */
    private const TRANSITIONS = [
        'Pesanan Dibuat'  => 'Sedang Diproses',
        'Sedang Diproses' => 'Siap Diambil',
        'Siap Diambil'    => 'Selesai',
    ];

    /**
     * Authorize that the given order contains at least one product
     * from the currently authenticated penjaga's stand.
     */
    private function authorizeOrder(Order $order): void
    {
        $hasProduct = $order->orderDetails()
            ->whereHas('product', fn ($q) => $q->where('stand_id', Auth::user()->stand_id))
            ->exists();

        if (!$hasProduct) {
            abort(403, 'Akses ditolak. Pesanan ini tidak mengandung produk dari Stand Anda.');
        }
    }

    /**
     * Display a listing of orders belonging to the penjaga's stand.
     */
    public function index(Request $request)
    {
        $standId = Auth::user()->stand_id;

        $query = Order::with(['orderDetails.product', 'user'])
            ->whereHas('orderDetails', fn ($q) => $q
                ->whereHas('product', fn ($p) => $p->where('stand_id', $standId))
            )
            ->latest();

        // Filter by status via ?status= query param
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(15)->withQueryString();

        // Status counts scoped to this stand only
        $baseQuery = fn () => Order::whereHas('orderDetails', fn ($q) => $q
            ->whereHas('product', fn ($p) => $p->where('stand_id', $standId))
        );

        $statusCounts = [
            'all'             => $baseQuery()->count(),
            'Pesanan Dibuat'  => $baseQuery()->where('status', 'Pesanan Dibuat')->count(),
            'Sedang Diproses' => $baseQuery()->where('status', 'Sedang Diproses')->count(),
            'Siap Diambil'    => $baseQuery()->where('status', 'Siap Diambil')->count(),
            'Selesai'         => $baseQuery()->where('status', 'Selesai')->count(),
        ];

        return view('penjaga.orders.index', compact('orders', 'statusCounts'));
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        $this->authorizeOrder($order);

        $standId = Auth::user()->stand_id;
        $order->load(['orderDetails.product', 'user', 'payment']);

        // Only expose order details belonging to this stand's products
        $standOrderDetails = $order->orderDetails->filter(
            fn ($detail) => $detail->product && $detail->product->stand_id === $standId
        );

        return view('penjaga.orders.show', compact('order', 'standOrderDetails'));
    }

    /**
     * Update the status of an order.
     *
     * Only forward transitions are allowed; 'Dibatalkan' is silently ignored (admin-only).
     */
    public function updateStatus(Request $request, Order $order)
    {
        $this->authorizeOrder($order);

        $newStatus = $request->status;

        // Penjaga cannot cancel orders — silently ignore, redirect back without error
        if ($newStatus === 'Dibatalkan') {
            return redirect()->back();
        }

        // AC 5.5: same-status update is a no-op
        if ($newStatus === $order->status) {
            return redirect()->back();
        }

        $currentStatus = $order->status;
        $allowedNext   = self::TRANSITIONS[$currentStatus] ?? null;

        if ($allowedNext === null || $newStatus !== $allowedNext) {
            return redirect()->back()
                ->with('error', 'Perubahan status tidak diizinkan.');
        }

        $order->update(['status' => $newStatus]);

        return redirect()->back()
            ->with('success', 'Status pesanan #' . $order->order_number . ' berhasil diubah menjadi: "' . $newStatus . '".');
    }
}
