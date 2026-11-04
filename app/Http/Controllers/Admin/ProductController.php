<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Stand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of products.
     */
    public function index(Request $request)
    {
        $stands = Stand::all();
        $categories = Category::all();

        $query = Product::with(['stand', 'category'])->latest();

        if ($request->filled('q')) {
            $keyword = $request->q;
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('stand_id')) {
            $query->where('stand_id', $request->stand_id);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->paginate(15)->withQueryString();

        return view('admin.products.index', compact('products', 'stands', 'categories'));
    }

    /**
     * Show form for creating a new product.
     */
    public function create()
    {
        $stands = Stand::all();
        $categories = Category::all();

        return view('admin.products.create', compact('stands', 'categories'));
    }

    /**
     * Store a newly created product.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'stand_id' => ['required', 'exists:stands,id'],
            'category_id' => ['required', 'exists:categories,id'],
            'price' => ['required', 'integer', 'min:500'],
            'stock' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'image_url' => ['nullable', 'url', 'max:500'],
            'status' => ['required', 'in:available,unavailable'],
            'is_featured' => ['nullable', 'boolean'],
        ]);

        $imagePath = $validated['image_url'] ?? null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $imagePath = Storage::url($imagePath);
        }

        Product::create([
            'name' => $validated['name'],
            'stand_id' => $validated['stand_id'],
            'category_id' => $validated['category_id'],
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'description' => $validated['description'] ?? null,
            'image' => $imagePath,
            'status' => $validated['status'],
            'is_featured' => $request->boolean('is_featured'),
        ]);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk "' . $validated['name'] . '" berhasil ditambahkan!');
    }

    /**
     * Show form for editing product.
     */
    public function edit(Product $product)
    {
        $stands = Stand::all();
        $categories = Category::all();

        return view('admin.products.edit', compact('product', 'stands', 'categories'));
    }

    /**
     * Update product in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'stand_id' => ['required', 'exists:stands,id'],
            'category_id' => ['required', 'exists:categories,id'],
            'price' => ['required', 'integer', 'min:500'],
            'stock' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'image_url' => ['nullable', 'url', 'max:500'],
            'status' => ['required', 'in:available,unavailable'],
            'is_featured' => ['nullable', 'boolean'],
        ]);

        $imagePath = $product->image;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $imagePath = Storage::url($imagePath);
        } elseif (!empty($validated['image_url'])) {
            $imagePath = $validated['image_url'];
        }

        $product->update([
            'name' => $validated['name'],
            'stand_id' => $validated['stand_id'],
            'category_id' => $validated['category_id'],
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'description' => $validated['description'] ?? null,
            'image' => $imagePath,
            'status' => $validated['status'],
            'is_featured' => $request->boolean('is_featured'),
        ]);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk "' . $product->name . '" berhasil diperbarui!');
    }

    /**
     * Quick stock update.
     */
    public function updateStock(Request $request, Product $product)
    {
        $request->validate([
            'stock' => ['required', 'integer', 'min:0'],
        ]);

        $product->update([
            'stock' => $request->stock,
            'status' => ($request->stock > 0) ? 'available' : 'unavailable',
        ]);

        return back()->with('success', 'Stok produk "' . $product->name . '" berhasil diubah menjadi ' . $request->stock . '.');
    }

    /**
     * Remove the product.
     */
    public function destroy(Product $product)
    {
        $name = $product->name;
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('info', 'Produk "' . $name . '" telah dihapus.');
    }
}

