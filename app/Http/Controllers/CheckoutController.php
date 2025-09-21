<?php

namespace App\Http\Controllers;

use App\Models\Cart;
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
        $cart = auth()->user()->cart()->with('cartItems.product')->first();

        if (!$cart || $cart->cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $cartItems = $cart->cartItems;
        $total = $cartItems->sum(function ($item) {
            return $item->qty * $item->price;
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
            'shipping_city' => 'required|string|max:100',
            'shipping_postal_code' => 'required|string|max:10',
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $cart = auth()->user()->cart()->with('cartItems.product')->first();

        if (!$cart || $cart->cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $cartItems = $cart->cartItems;
        $total = $cartItems->sum(function ($item) {
            return $item->qty * $item->price;
        });

        // Check stock availability
        foreach ($cartItems as $item) {
            if ($item->product->stock < $item->qty) {
                return back()->with('error', "Insufficient stock for {$item->product->name}. Available: {$item->product->stock}");
            }
        }

        $order = null;
        DB::transaction(function () use ($cart, $cartItems, $total, $request, &$order) {
            // Handle file upload
            $path = $request->file('payment_proof')->store('payment_proofs', 'public');

            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
                'total' => $total,
                'shipping_address' => $request->shipping_address,
                'shipping_city' => $request->shipping_city,
                'shipping_postal_code' => $request->shipping_postal_code,
                'payment_proof_path' => $path,
                'status' => 'diproses' // Default status
            ]);

            // Create order items
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'qty' => $item->qty,
                    'price' => $item->price,
                    'subtotal' => $item->qty * $item->price,
                ]);

                // Update product stock
                $item->product->decrement('stock', $item->qty);
            }

            // Mark cart as completed
            $cart->update(['status' => 'completed']);
        });

        return redirect()->route('orders.show', $order)
            ->with('success', 'Order placed successfully!');
    }
}
