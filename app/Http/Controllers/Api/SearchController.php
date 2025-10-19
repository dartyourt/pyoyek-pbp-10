<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Search products by name or description
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $query = $request->input('q');
        $categoryId = $request->input('category');
        $sort = $request->input('sort', 'newest');
        $limit = $request->input('limit', 12);
        
        // Base query - only active products
        $productsQuery = Product::where('is_active', true);
        
        // Apply search filter
        if ($query) {
            $productsQuery->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            });
        }
        
        // Apply category filter
        if ($categoryId) {
            $productsQuery->where('category_id', $categoryId);
        }
        
        // Apply sorting
        switch ($sort) {
            case 'price_low':
                $productsQuery->orderBy('price', 'asc');
                break;
            case 'price_high':
                $productsQuery->orderBy('price', 'desc');
                break;
            case 'name':
                $productsQuery->orderBy('name', 'asc');
                break;
            case 'newest':
            default:
                $productsQuery->orderBy('created_at', 'desc');
                break;
        }
        
        // Get products with limit
        $products = $productsQuery->limit($limit)->get();
        
        // Transform products for the response
        $results = $products->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'formatted_price' => 'Rp ' . number_format($product->price, 0, ',', '.'),
                'category' => $product->category ? $product->category->name : 'Uncategorized',
                'image' => $product->image_path ? asset('storage/' . $product->image_path) : null,
                'stock' => $product->stock,
                'url' => route('catalog.show', $product)
            ];
        });
        
        return response()->json([
            'results' => $results,
            'count' => count($results)
        ]);
    }
}