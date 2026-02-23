<?php

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $featuredProducts = Product::where('is_featured', true)
        ->where('is_active', true)
        ->with(['category', 'images', 'variants'])
        ->latest()
        ->take(8)
        ->get();

    $categories = Category::where('is_active', true)
        ->orderBy('sort_order')
        ->take(6)
        ->get();

    $latestProducts = Product::where('is_active', true)
        ->with(['category', 'images', 'variants'])
        ->latest()
        ->take(12)
        ->get();

    return view('home', compact('featuredProducts', 'categories', 'latestProducts'));
});
