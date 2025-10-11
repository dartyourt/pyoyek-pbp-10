<x-app-layout>
    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 sm:p-8 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <!-- Header -->
                    <div class="flex flex-col sm:flex-row items-start sm:items-center sm:justify-between mb-6">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Order Details</h1>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Order #{{ $order->id }} &bull; Placed on {{ $order->created_at->format('F d, Y') }}</p>
                        </div>
                        <a href="{{ route('orders.index') }}" class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            &larr; Back to My Orders
                        </a>
                    </div>

                    @if(session('success'))
                        <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-lg">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- Left Column: Items & Total -->
                        <div class="md:col-span-2">
                            <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Items Ordered</h2>
                            <div class="space-y-4">
                                @foreach($order->orderItems as $item)
                                    <div class="flex items-center space-x-4 p-4 border dark:border-gray-700 rounded-lg">
                                        <div class="flex-shrink-0 w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-md overflow-hidden">
                                            <img src="{{ asset('storage/' . $item->product->image_path) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h3 class="font-medium text-gray-800 dark:text-gray-200">{{ $item->product->name }}</h3>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $item->qty }} x Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-semibold text-gray-800 dark:text-gray-200">Rp {{ number_format($item->qty * $item->price, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Total -->
                            <div class="mt-6 border-t-2 border-dashed dark:border-gray-700 pt-4 text-right">
                                <div class="flex justify-end items-center">
                                    <span class="text-lg font-medium text-gray-900 dark:text-white">Total:</span>
                                    <span class="text-2xl font-bold text-gray-900 dark:text-white ml-4">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column: Shipping & Payment -->
                        <div class="space-y-6">
                            <!-- Status -->
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Status</h3>
                                <span class="mt-1 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                @if($order->status === 'dikirim') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200 @endif">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                            <!-- Shipping -->
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Shipping Address</h3>
                                <address class="mt-2 not-italic text-gray-600 dark:text-gray-300">
                                    {{ $order->shipping_address }}<br>
                                    {{ $order->shipping_city }}, {{ $order->shipping_postal_code }}
                                </address>
                            </div>

                            <!-- Payment Proof -->
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Proof of Payment</h3>
                                @if($order->payment_proof_path)
                                    <div class="mt-2">
                                        <a href="{{ asset('storage/' . $order->payment_proof_path) }}" target="_blank" rel="noopener noreferrer">
                                            <img src="{{ asset('storage/' . $order->payment_proof_path) }}" alt="Proof of payment" class="rounded-lg border dark:border-gray-600 w-full h-auto object-cover hover:opacity-80 transition-opacity">
                                        </a>
                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Click image to view full size.</p>
                                    </div>
                                @else
                                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">No payment proof was uploaded.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>