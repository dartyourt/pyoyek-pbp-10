<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        // Middlewares are now defined in the routes file
    }

    /**
     * Show the admin dashboard.
     */
    public function index()
    {
        // Dashboard data
        $userCount = User::count();
        $productCount = Product::count() ?? 0;
        $categoryCount = Category::count();
        $orderCount = Order::count() ?? 0;

        return view('admin.dashboard', compact(
            'userCount', 
            'productCount', 
            'categoryCount', 
            'orderCount'
        ));
    }
}