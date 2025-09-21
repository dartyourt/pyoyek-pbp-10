<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show checkout form
     */
    public function index()
    {
        $cart = Cart::where('user_id', Auth::id())
            ->where('status', 'active')
            ->with('cartItems.product')
            ->first();

        if (!$cart || $cart->cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $cartItems = $cart->cartItems;
        $total = $cartItems->sum(function ($item) {
            return $item->quantity * $item->price;
        });

        return view('checkout.index', compact('cartItems', 'total'));
    }

    /**
     * Process checkout
     */
    public function store(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string|max:500',
            'payment_method' => 'required|in:cash_on_delivery,bank_transfer',
            'notes' => 'nullable|string|max:1000'
        ]);

        $cart = Cart::where('user_id', Auth::id())
            ->where('status', 'active')
            ->with('cartItems.product')
            ->first();

        if (!$cart || $cart->cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $cartItems = $cart->cartItems;
        $total = $cartItems->sum(function ($item) {
            return $item->quantity * $item->price;
        });

        // Check stock availability
        foreach ($cartItems as $item) {
            if ($item->product->stock < $item->quantity) {
                return back()->with('error', "Insufficient stock for {$item->product->name}. Available: {$item->product->stock}");
            }
        }

        DB::transaction(function () use ($cart, $cartItems, $total, $request) {
            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
                'total_amount' => $total,
                'shipping_address' => $request->shipping_address,
                'payment_method' => $request->payment_method,
                'notes' => $request->notes,
                'status' => 'pending'
            ]);

            // Create order items
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price
                ]);

                // Update product stock
                $item->product->decrement('stock', $item->quantity);
            }

            // Mark cart as completed
            $cart->update(['status' => 'completed']);
        });

        return redirect()->route('orders.show', Order::latest()->first())
            ->with('success', 'Order placed successfully!');
    }
}