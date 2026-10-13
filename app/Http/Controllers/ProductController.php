<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display a listing of products with filters and eager loading.
     */
    public function index(Request $request): View
    {
        $categories = Category::active()->get();

        $query = Product::query()
            ->active()
            ->with(['category'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews');

        // Filter by keyword search
        if ($request->filled('q')) {
            $query->search($request->input('q'));
        }

        // Filter by category
        if ($request->filled('category')) {
            $category = Category::where('slug', $request->input('category'))->first();
            if ($category) {
                $query->byCategory($category->id);
            }
        }

        // Filter by minimum & maximum price
        if ($request->filled('min_price') && $request->filled('max_price')) {
            $query->priceBetween((float) $request->input('min_price'), (float) $request->input('max_price'));
        }

        // Filter by in-stock only
        if ($request->boolean('in_stock')) {
            $query->inStock();
        }

        // Sorting
        $sort = $request->input('sort', 'latest');
        match ($sort) {
            'price_low' => $query->orderBy('price', 'asc'),
            'price_high' => $query->orderBy('price', 'desc'),
            'rating' => $query->orderByDesc('reviews_avg_rating'),
            default => $query->latest(),
        };

        $products = $query->paginate(12)->withQueryString();

        return view('products.index', compact('products', 'categories'));
    }

    /**
     * Display the specified product detail with related items.
     */
    public function show(Product $product): View
    {
        $product->load([
            'category',
            'reviews' => fn ($q) => $q->approved()->with('user')->latest(),
        ]);

        $product->loadAvg('reviews', 'rating');
        $product->loadCount('reviews');

        // Related products in the same category (eager loaded)
        $relatedProducts = Product::active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with('category')
            ->limit(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }
}
