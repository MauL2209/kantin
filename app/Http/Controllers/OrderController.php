<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display list of orders for student ("Pesanan Saya").
     */
    public function index(Request $request)
    {
        $query = Order::with(['orderDetails.product.stand', 'payment'])
            ->orderBy('created_at', 'desc');

        if (Auth::check()) {
            // Logged in user: show user's orders
            $query->where('user_id', Auth::id());
        } elseif ($request->filled('q')) {
            // Guest searching by order number or phone
            $keyword = trim($request->q);
            $query->where(function ($q) use ($keyword) {
                $q->where('order_number', 'like', "%{$keyword}%")
                  ->orWhere('customer_phone', 'like', "%{$keyword}%");
            });
        } elseif (session()->has('last_order_id')) {
            // If just placed an order as guest
            $query->where('id', session()->get('last_order_id'));
        } else {
            // Empty if not logged in and no search
            $orders = collect();
            return view('orders.index', compact('orders'));
        }

        // Status filter if clicked on tab
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(10)->withQueryString();

        return view('orders.index', compact('orders'));
    }

    /**
     * Display order detail page.
     */
    public function show(Order $order)
    {
        // If logged in as student, ensure student can only see their own order (unless admin)
        if (Auth::check() && !Auth::user()->isAdmin() && $order->user_id && $order->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki hak untuk melihat pesanan ini.');
        }

        $order->load(['orderDetails.product.stand', 'payment']);

        return view('orders.show', compact('order'));
    }
}

