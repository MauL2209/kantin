<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display landing page.
     */
    public function index()
    {
        // 2 Makanan favorit: prioritaskan terlaris (berdasarkan jumlah order detail),
        // fallback ke is_featured jika belum ada data penjualan.
        $featuredMakanan = Product::with(['stand', 'category'])
            ->whereHas('category', fn($q) => $q->where('slug', 'makanan'))
            ->where('status', 'available')
            ->withCount('orderDetails')
            ->orderByDesc('order_details_count')
            ->orderByDesc('is_featured')
            ->take(2)
            ->get();

        // 2 Minuman favorit: prioritas sama
        $featuredMinuman = Product::with(['stand', 'category'])
            ->whereHas('category', fn($q) => $q->where('slug', 'minuman'))
            ->where('status', 'available')
            ->withCount('orderDetails')
            ->orderByDesc('order_details_count')
            ->orderByDesc('is_featured')
            ->take(2)
            ->get();

        // Total products count (untuk hero stats)
        $totalProducts = Product::where('status', 'available')->count();

        return view('home', compact('featuredMakanan', 'featuredMinuman', 'totalProducts'));
    }
}

