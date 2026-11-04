<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display cart contents.
     */
    public function index()
    {
        $cart = session()->get('cart', []);
        $total = 0;
        $itemsCount = 0;

        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
            $itemsCount += $item['quantity'];
        }

        return view('cart.index', compact('cart', 'total', 'itemsCount'));
    }

    /**
     * Add product to cart.
     */
    public function add(Request $request, Product $product)
    {
        if ($product->stock <= 0 || $product->status !== 'available') {
            return back()->with('error', 'Maaf, produk "' . $product->name . '" saat ini sedang habis.');
        }

        $cart = session()->get('cart', []);
        $requestedQty = max(1, (int)$request->input('quantity', 1));

        if (isset($cart[$product->id])) {
            $newQty = $cart[$product->id]['quantity'] + $requestedQty;
            if ($newQty > $product->stock) {
                return back()->with('warning', 'Jumlah pesanan melebihi stok yang tersedia (Stok tersisa: ' . $product->stock . ').');
            }
            $cart[$product->id]['quantity'] = $newQty;
        } else {
            if ($requestedQty > $product->stock) {
                return back()->with('warning', 'Jumlah pesanan melebihi stok yang tersedia (Stok tersisa: ' . $product->stock . ').');
            }
            $cart[$product->id] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $requestedQty,
                'image' => $product->image,
                'stand_name' => $product->stand->name ?? 'Stand Melati',
                'category_name' => $product->category->name ?? 'Menu',
                'stock' => $product->stock,
            ];
        }

        session()->put('cart', $cart);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil ditambahkan ke keranjang!',
                'cart_count' => array_sum(array_column($cart, 'quantity')),
            ]);
        }

        return back()->with('success', 'Berhasil menambahkan "' . $product->name . '" ke keranjang.');
    }

    /**
     * Update quantity of a product in cart.
     */
    public function update(Request $request, $id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $product = Product::find($id);
            $action = $request->input('action'); // 'increase', 'decrease', or 'set'
            $currentQty = $cart[$id]['quantity'];

            if ($action === 'increase') {
                $newQty = $currentQty + 1;
            } elseif ($action === 'decrease') {
                $newQty = $currentQty - 1;
            } else {
                $newQty = max(1, (int)$request->input('quantity', 1));
            }

            if ($product && $newQty > $product->stock) {
                return back()->with('warning', 'Stok hanya tersisa ' . $product->stock . ' porsi.');
            }

            if ($newQty <= 0) {
                unset($cart[$id]);
                session()->put('cart', $cart);
                return back()->with('info', 'Produk dihapus dari keranjang.');
            }

            $cart[$id]['quantity'] = $newQty;
            session()->put('cart', $cart);
            return back()->with('success', 'Jumlah produk berhasil diperbarui.');
        }

        return back()->with('error', 'Produk tidak ditemukan di keranjang.');
    }

    /**
     * Remove item from cart.
     */
    public function remove($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $name = $cart[$id]['name'];
            unset($cart[$id]);
            session()->put('cart', $cart);
            return back()->with('info', '"' . $name . '" telah dihapus dari keranjang.');
        }

        return back()->with('error', 'Item tidak ditemukan.');
    }

    /**
     * Clear all items from cart.
     */
    public function clear()
    {
        session()->forget('cart');
        return back()->with('info', 'Keranjang telah dikosongkan.');
    }
}

