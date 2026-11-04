<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Stand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of orders.
     */
    public function index(Request $request)
    {
        $stands = Stand::all();

        $query = Order::with(['orderDetails.product.stand', 'user', 'payment'])
            ->latest();

        // Search by order number or customer name
        if ($request->filled('q')) {
            $keyword = $request->q;
            $query->where(function ($q) use ($keyword) {
                $q->where('order_number', 'like', "%{$keyword}%")
                  ->orWhere('customer_name', 'like', "%{$keyword}%")
                  ->orWhere('customer_nis', 'like', "%{$keyword}%")
                  ->orWhere('customer_phone', 'like', "%{$keyword}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $orders = $query->paginate(15)->withQueryString();

        // Status counts for badge tabs
        $statusCounts = [
            'all' => Order::count(),
            'Pesanan Dibuat' => Order::where('status', 'Pesanan Dibuat')->count(),
            'Sedang Diproses' => Order::where('status', 'Sedang Diproses')->count(),
            'Siap Diambil' => Order::where('status', 'Siap Diambil')->count(),
            'Selesai' => Order::where('status', 'Selesai')->count(),
            'Dibatalkan' => Order::where('status', 'Dibatalkan')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'stands', 'statusCounts'));
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        $order->load(['orderDetails.product.stand', 'user', 'payment']);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update order status.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => ['required', 'in:Pesanan Dibuat,Sedang Diproses,Siap Diambil,Selesai,Dibatalkan'],
        ]);

        $newStatus = $request->status;
        $oldStatus = $order->status;

        // If cancelling, restore stock
        if ($newStatus === 'Dibatalkan' && $oldStatus !== 'Dibatalkan') {
            DB::transaction(function () use ($order) {
                foreach ($order->orderDetails as $detail) {
                    if ($detail->product) {
                        $detail->product->increment('stock', $detail->quantity);
                    }
                }
                $order->update(['status' => 'Dibatalkan']);
                if ($order->payment) {
                    $order->payment->update(['status' => 'cancelled']);
                }
            });

            return back()->with('info', 'Pesanan #' . $order->order_number . ' telah dibatalkan dan stok dikembalikan.');
        }

        // If completing, update payment to paid if cash
        if ($newStatus === 'Selesai') {
            if ($order->payment && $order->payment->status !== 'paid') {
                $order->payment->update([
                    'status' => 'paid',
                    'paid_at' => now(),
                ]);
            }
        }

        $order->update(['status' => $newStatus]);

        return back()->with('success', 'Status pesanan #' . $order->order_number . ' berhasil diubah menjadi: "' . $newStatus . '".');
    }

    /**
     * Print receipt view.
     */
    public function print(Order $order)
    {
        $order->load(['orderDetails.product.stand', 'user', 'payment']);
        return view('admin.orders.print', compact('order'));
    }
}

