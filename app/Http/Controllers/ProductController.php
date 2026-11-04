<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Stand;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display menu list with filtering, searching, and sorting.
     */
    public function index(Request $request)
    {
        $stands = Stand::all();
        $categories = Category::all();

        $query = Product::with(['stand', 'category']);

        // Search by keyword
        if ($request->filled('q')) {
            $keyword = $request->q;
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%");
            });
        }

        // Filter by Stand
        if ($request->filled('stand')) {
            $query->where('stand_id', $request->stand);
        }

        // Filter by Category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Sorting
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'featured':
                $query->orderBy('is_featured', 'desc')->orderBy('created_at', 'desc');
                break;
            case 'latest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $products = $query->paginate(12)->withQueryString();

        return view('menu.index', compact('products', 'stands', 'categories'));
    }

    /**
     * Display product detail.
     */
    public function show(Product $product)
    {
        $product->load(['stand', 'category']);
        $relatedProducts = Product::where('stand_id', $product->stand_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        return view('menu.show', compact('product', 'relatedProducts'));
    }
}

