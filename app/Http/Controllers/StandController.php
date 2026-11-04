<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Stand;
use Illuminate\Http\Request;

class StandController extends Controller
{
    /**
     * Display all stands.
     */
    public function index()
    {
        $stands = Stand::withCount('products')->get();
        return view('stands.index', compact('stands'));
    }

    /**
     * Display single stand profile and menus.
     */
    public function show(Request $request, Stand $stand)
    {
        $categories = Category::all();

        $query = $stand->products()->with('category');

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $products = $query->paginate(12)->withQueryString();

        return view('stands.show', compact('stand', 'products', 'categories'));
    }
}

