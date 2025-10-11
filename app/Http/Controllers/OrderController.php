<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show user's order history
     */
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with('orderItems.product')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    /**
     * Confirm order received by user
     */
    public function confirmReceived(Order $order)
    {
        // Ensure order belongs to current user
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Only allow if status is 'dikirim'
        if ($order->status !== 'dikirim') {
            return redirect()->back()->with('error', 'Order tidak dapat dikonfirmasi diterima.');
        }

        $order->update(['status' => 'selesai']);

        return redirect()->back()->with('success', 'Order telah dikonfirmasi diterima. Terima kasih!');
    }

    /**
     * Show specific order details
     */
    public function show(Order $order)
    {
        // Ensure order belongs to current user
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load('orderItems.product');

        return view('orders.show', compact('order'));
    }
}