<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    /**
     * Show checkout form.
     */
    public function index()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')
                ->with('warning', 'Keranjang Anda masih kosong. Silakan pilih menu terlebih dahulu.');
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        $user = Auth::user();

        return view('checkout.index', compact('cart', 'total', 'user'));
    }

    /**
     * Process checkout submission.
     */
    public function process(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')
                ->with('error', 'Keranjang Anda kosong.');
        }

        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_nis' => ['nullable', 'string', 'max:50'],
            'customer_class' => ['required', 'string', 'max:50'],
            'customer_room' => ['required', 'string', 'max:100'],
            'customer_phone' => ['required', 'string', 'max:20'],
            'payment_method' => ['required', 'string', 'in:cash,transfer,qris'],
            'customer_note' => ['nullable', 'string', 'max:500'],
        ], [
            'customer_name.required' => 'Nama pemesan wajib diisi.',
            'customer_class.required' => 'Kelas wajib diisi.',
            'customer_room.required' => 'Ruangan / Lokasi pengambilan wajib diisi.',
            'customer_phone.required' => 'Nomor WhatsApp / HP wajib diisi.',
            'payment_method.required' => 'Pilih metode pembayaran.',
        ]);

        // Begin Transaction
        DB::beginTransaction();
        try {
            // Verify stock for all items
            $totalPrice = 0;
            foreach ($cart as $id => $item) {
                $product = Product::lockForUpdate()->find($id);
                if (!$product) {
                    throw new \Exception('Produk "' . $item['name'] . '" sudah tidak tersedia.');
                }
                if ($product->stock < $item['quantity']) {
                    throw new \Exception('Stok untuk "' . $product->name . '" tidak mencukupi (Tersisa: ' . $product->stock . ').');
                }
                $totalPrice += $product->price * $item['quantity'];
            }

            // Generate unique Order Number
            $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(4));

            // Create Order
            $order = Order::create([
                'user_id' => Auth::id() ?? null,
                'order_number' => $orderNumber,
                'customer_name' => $validated['customer_name'],
                'customer_nis' => $validated['customer_nis'] ?? null,
                'customer_class' => $validated['customer_class'],
                'customer_room' => $validated['customer_room'],
                'customer_phone' => $validated['customer_phone'],
                'total_price' => $totalPrice,
                'payment_method' => $validated['payment_method'],
                'status' => 'Pesanan Dibuat',
                'customer_note' => $validated['customer_note'] ?? null,
            ]);

            // Create Order Details and decrement product stock
            foreach ($cart as $id => $item) {
                $product = Product::find($id);
                $subtotal = $product->price * $item['quantity'];

                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'subtotal' => $subtotal,
                ]);

                // Reduce stock
                $product->decrement('stock', $item['quantity']);
            }

            // Create Payment record
            Payment::create([
                'order_id' => $order->id,
                'payment_method' => $validated['payment_method'],
                'amount' => $totalPrice,
                'status' => ($validated['payment_method'] === 'cash') ? 'pending' : 'paid',
                'paid_at' => ($validated['payment_method'] !== 'cash') ? now() : null,
            ]);

            DB::commit();

            // Clear Cart
            session()->forget('cart');

            // Store last created order in session for guest tracking
            session()->put('last_order_id', $order->id);

            return redirect()->route('checkout.success', $order->id)
                ->with('success', 'Pesanan Anda berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan saat memproses pesanan: ' . $e->getMessage());
        }
    }

    /**
     * Show order success page.
     */
    public function success(Order $order)
    {
        $order->load(['orderDetails.product.stand', 'payment']);
        return view('checkout.success', compact('order'));
    }
}

