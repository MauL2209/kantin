<?php

namespace App\Http\Controllers\Penjaga;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Authorize that the product belongs to the logged-in penjaga's stand.
     */
    private function authorizeProduct(Product $product): void
    {
        if ($product->stand_id !== Auth::user()->stand_id) {
            abort(403, 'Akses ditolak. Produk ini bukan milik Stand Anda.');
        }
    }

    /**
     * Display a listing of products for the penjaga's own stand.
     */
    public function index()
    {
        $products = Product::with('category')
            ->where('stand_id', Auth::user()->stand_id)
            ->latest()
            ->paginate(15);

        return view('penjaga.products.index', compact('products'));
    }

    /**
     * Show form for creating a new product.
     */
    public function create()
    {
        $categories = Category::all();

        return view('penjaga.products.create', compact('categories'));
    }

    /**
     * Store a newly created product for the penjaga's stand.
     * stand_id is always taken from Auth::user()->stand_id — never from the request.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'price'       => ['required', 'integer', 'min:500'],
            'stock'       => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'image'       => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'status'      => ['required', 'in:available,unavailable'],
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $path      = $request->file('image')->store('products', 'public');
            $imagePath = Storage::url($path);
        }

        // Automatically set status to unavailable when stock is 0
        $status = ($validated['stock'] == 0) ? 'unavailable' : $validated['status'];

        Product::create([
            'stand_id'    => Auth::user()->stand_id, // always from auth, never from request
            'category_id' => $validated['category_id'],
            'name'        => $validated['name'],
            'description' => $validated['description'] ?? null,
            'price'       => $validated['price'],
            'stock'       => $validated['stock'],
            'image'       => $imagePath,
            'status'      => $status,
        ]);

        return redirect()->route('penjaga.products.index')
            ->with('success', 'Produk "' . $validated['name'] . '" berhasil ditambahkan!');
    }

    /**
     * Show form for editing a product.
     */
    public function edit(Product $product)
    {
        $this->authorizeProduct($product);

        $categories = Category::all();

        return view('penjaga.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product.
     */
    public function update(Request $request, Product $product)
    {
        $this->authorizeProduct($product);

        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'price'       => ['required', 'integer', 'min:500'],
            'stock'       => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'image'       => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'status'      => ['required', 'in:available,unavailable'],
        ]);

        $imagePath = $product->image;
        if ($request->hasFile('image')) {
            $path      = $request->file('image')->store('products', 'public');
            $imagePath = Storage::url($path);
        }

        // AC 4.5/4.7: auto-unavailable when stock is 0 is the default behavior
        // AC 4.6: but penjaga can explicitly override to 'available' via the edit form
        // The penjaga's explicit status choice from the form always takes precedence
        $status = $validated['status'];

        $product->update([
            'category_id' => $validated['category_id'],
            'name'        => $validated['name'],
            'description' => $validated['description'] ?? null,
            'price'       => $validated['price'],
            'stock'       => $validated['stock'],
            'image'       => $imagePath,
            'status'      => $status,
            // stand_id is intentionally not updated — penjaga cannot move products to another stand
        ]);

        return redirect()->route('penjaga.products.index')
            ->with('success', 'Produk "' . $product->name . '" berhasil diperbarui!');
    }

    /**
     * Remove the specified product.
     */
    public function destroy(Product $product)
    {
        $this->authorizeProduct($product);

        $name = $product->name;
        $product->delete();

        return redirect()->route('penjaga.products.index')
            ->with('info', 'Produk "' . $name . '" telah dihapus.');
    }

    /**
     * Quick stock update for the specified product.
     */
    public function updateStock(Request $request, Product $product)
    {
        $this->authorizeProduct($product);

        $request->validate([
            'stock' => ['required', 'integer', 'min:0'],
        ]);

        $product->update([
            'stock'  => $request->stock,
            'status' => ($request->stock > 0) ? 'available' : 'unavailable',
        ]);

        return back()->with('success', 'Stok produk "' . $product->name . '" berhasil diubah menjadi ' . $request->stock . '.');
    }
}
