<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;

class CartController extends Controller
{
    /**
     * Constructor to add middleware
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (auth()->check() && auth()->user()->isAdmin()) {
                return redirect()->route('admin.dashboard')
                    ->with('warning', 'Administrators should use the admin dashboard to manage products and orders.');
            }
            return $next($request);
        });
    }

    /**
     * Display the contents of the cart.
     */
    public function index()
    {
        $cart = auth()->user()->cart;
        return view('cart.index', compact('cart'));
    }

    /**
     * Add a product to the cart.
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        $cart = Cart::firstOrCreate(
            ['user_id' => auth()->id(), 'status' => 'active'],
            []
        );

        // Check if item is already in the cart
        $cartItem = $cart->cartItems()->where('product_id', $product->id)->first();

        if ($cartItem) {
            // Update quantity if item exists
            $cartItem->increment('qty', $request->qty);
        } else {
            // Add new item if it does not exist
            $cart->cartItems()->create([
                'product_id' => $product->id,
                'qty' => $request->qty,
                'price' => $product->price, // Store price at the time of adding
            ]);
        }

        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }

    /**
     * Update the specified item in the cart.
     */
    public function update(Request $request, CartItem $cartItem)
    {
        $request->validate([
            'qty' => 'required|integer|min:1',
        ]);

        // Ensure the user owns the cart item
        if ($cartItem->cart->user_id !== auth()->id()) {
            return abort(403, 'Unauthorized action.');
        }

        $cartItem->qty = $request->qty;
        $cartItem->save();

        return redirect()->route('cart.index')->with('success', 'Cart updated successfully.');
    }

    /**
     * Remove the specified item from the cart.
     */
    public function destroy(CartItem $cartItem)
    {
        // Ensure the user owns the cart item
        if ($cartItem->cart->user_id !== auth()->id()) {
            return abort(403, 'Unauthorized action.');
        }

        $cartItem->delete();

        return redirect()->route('cart.index')->with('success', 'Item removed from cart.');
    }
}
