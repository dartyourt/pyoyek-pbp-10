<?php

namespace App\Http\Controllers\Catalog;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index(Request $request)
    {
        // Get query parameters
        $categoryId = $request->query('category');
        $search = $request->query('search');
        $sort = $request->query('sort', 'newest'); // Default sort by newest

        // Base query
        $query = Product::query();

        // Filter by category if provided
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        // Search by name or description if provided
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Apply sorting
        switch ($sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        // Get products with pagination
        $products = $query->paginate(12);
        
        // Get all categories for the filter
        $categories = Category::orderBy('name')->get();

        return view('catalog.index', compact('products', 'categories', 'categoryId', 'search', 'sort'));
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        // Get related products from the same category
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();

        return view('catalog.show', compact('product', 'relatedProducts'));
    }
}