<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight flex items-center">
                <span class="bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300 text-xs font-semibold px-2.5 py-0.5 rounded-full mr-2">
                    #{{ $order->id }}
                </span>
                Order Details
                <span class="ml-3 px-3 py-1 text-sm rounded-md
                    @if($order->status == 'diproses') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                    @elseif($order->status == 'dikirim') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                    @elseif($order->status == 'selesai') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                    @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                    @endif">
                    {{ ucfirst($order->status) }}
                </span>
            </h2>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.orders.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-md inline-flex items-center text-sm transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Orders
                </a>
                <a href="{{ route('admin.orders.edit', $order) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-md inline-flex items-center text-sm transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                    </svg>
                    Update Status
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Order Summary Card -->
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 dark:from-blue-700 dark:to-indigo-800 overflow-hidden shadow-lg sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-white">
                        <div class="flex items-center">
                            <div class="p-3 bg-white bg-opacity-20 rounded-full mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-blue-100">Order Date</div>
                                <div class="text-lg font-bold">{{ $order->created_at->format('d M Y, H:i') }}</div>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="p-3 bg-white bg-opacity-20 rounded-full mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-blue-100">Payment Status</div>
                                <div class="text-lg font-bold">
                                    @if(isset($order->payment_proof_path))
                                        <span class="bg-green-500 bg-opacity-30 text-white text-xs font-medium px-2.5 py-0.5 rounded">Paid</span>
                                    @else
                                        <span class="bg-yellow-500 bg-opacity-30 text-white text-xs font-medium px-2.5 py-0.5 rounded">Pending</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="p-3 bg-white bg-opacity-20 rounded-full mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-blue-100">Total Amount</div>
                                <div class="text-lg font-bold">Rp {{ number_format($order->total, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Customer & Shipping Info -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Customer Info Card -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="font-semibold text-lg text-gray-900 dark:text-gray-100 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-500" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                    </svg>
                                    Customer Details
                                </h3>
                            </div>
                            <div class="border-t border-gray-200 dark:border-gray-700 pt-4 space-y-4">
                                <div class="grid grid-cols-2">
                                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Name</div>
                                    <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $order->user->name }}</div>
                                </div>
                                <div class="grid grid-cols-2">
                                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</div>
                                    <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                        <a href="mailto:{{ $order->user->email }}" class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300">
                                            {{ $order->user->email }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Info Card -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="font-semibold text-lg text-gray-900 dark:text-gray-100 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-500" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                    </svg>
                                    Shipping Address
                                </h3>
                            </div>
                            <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                                <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white mb-1">{{ $order->user->name }}</div>
                                    <div class="text-sm text-gray-800 dark:text-gray-200 mb-1">{{ $order->shipping_address }}</div>
                                    <div class="text-sm text-gray-800 dark:text-gray-200 mb-1">{{ $order->shipping_city }}</div>
                                    <div class="text-sm text-gray-800 dark:text-gray-200">{{ $order->shipping_postal_code }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Payment Proof (If Available) -->
                    @if(isset($order->payment_proof_path))
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="font-semibold text-lg text-gray-900 dark:text-gray-100 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-500" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M4 5a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2h-1.586a1 1 0 01-.707-.293l-1.121-1.121A2 2 0 0011.172 3H8.828a2 2 0 00-1.414.586L6.293 4.707A1 1 0 015.586 5H4z" clip-rule="evenodd" />
                                    </svg>
                                    Payment Proof
                                </h3>
                            </div>
                            <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                                <div class="flex justify-center">
                                    <a href="{{ asset('storage/' . $order->payment_proof_path) }}" target="_blank" class="block">
                                        <img src="{{ asset('storage/' . $order->payment_proof_path) }}" alt="Payment Proof" class="w-full h-auto max-h-32 object-contain rounded-lg shadow">
                                    </a>
                                </div>
                                <div class="text-center mt-2">
                                    <a href="{{ asset('storage/' . $order->payment_proof_path) }}" target="_blank" class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 text-sm font-medium inline-flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                        View Full Image
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                
                <!-- Order Items & Summary -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Order Items Card -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="font-semibold text-lg text-gray-900 dark:text-gray-100 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-500" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd" />
                                    </svg>
                                    Order Items
                                </h3>
                            </div>
                            
                            <div class="border-t border-gray-200 dark:border-gray-700">
                                
                                
                                <div class="overflow-x-auto">
                                    @if($order->orderItems->count() > 0)
                                        <!-- Table-based layout for order items -->
                                        <div class="mt-4">
                                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                                <thead class="bg-gray-50 dark:bg-gray-800">
                                                    <tr>
                                                        <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-16">Image</th>
                                                        <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Product</th>
                                                        <th scope="col" class="px-3 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Price</th>
                                                        <th scope="col" class="px-3 py-2 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-16">Qty</th>
                                                        <th scope="col" class="px-3 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Subtotal</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-800">
                                                    @foreach($order->orderItems as $item)
                                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                                                            <!-- Product Image -->
                                                            <td class="px-3 py-2 whitespace-nowrap">
                                                                @if($item->product && !empty($item->product->image_path))
                                                                    <img src="{{ asset('storage/' . $item->product->image_path) }}" alt="{{ $item->product->name }}" class="w-10 h-10 object-cover rounded-md shadow-sm">
                                                                @else
                                                                    <div class="w-10 h-10 bg-gray-200 dark:bg-gray-700 rounded-md flex items-center justify-center">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                                        </svg>
                                                                    </div>
                                                                @endif
                                                            </td>
                                                            
                                                            <!-- Product Info -->
                                                            <td class="px-3 py-2">
                                                                @if($item->product)
                                                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $item->product->name }}</div>
                                                                    @if($item->product->category)
                                                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                                                                {{ $item->product->category->name }}
                                                                            </span>
                                                                        </div>
                                                                    @endif
                                                                @else
                                                                    <div class="text-sm font-medium text-red-600 dark:text-red-400">
                                                                        Product ID: {{ $item->product_id }}
                                                                    </div>
                                                                    <div class="text-xs text-red-500 dark:text-red-400">
                                                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">
                                                                            Product deleted
                                                                        </span>
                                                                    </div>
                                                                @endif
                                                            </td>
                                                            
                                                            <!-- Unit Price -->
                                                            <td class="px-3 py-2 whitespace-nowrap text-right text-sm text-gray-700 dark:text-gray-300">
                                                                Rp {{ number_format($item->price, 0, ',', '.') }}
                                                            </td>
                                                            
                                                            <!-- Quantity -->
                                                            <td class="px-3 py-2 whitespace-nowrap text-center text-sm font-medium text-gray-900 dark:text-white">
                                                                {{ $item->qty }}
                                                            </td>
                                                            
                                                            <!-- Subtotal -->
                                                            <td class="px-3 py-2 whitespace-nowrap text-right text-sm font-semibold text-gray-900 dark:text-white">
                                                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot class="bg-gray-50 dark:bg-gray-800">
                                                    <!-- Summary Rows -->
                                                    <tr>
                                                        <td colspan="3" class="px-3 py-2"></td>
                                                        <td class="px-3 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-400">Subtotal:</td>
                                                        <td class="px-3 py-2 text-right text-sm font-medium text-gray-900 dark:text-white">
                                                            Rp {{ number_format($order->total, 0, ',', '.') }}
                                                        </td>
                                                    </tr>
                                                    
                                                    <tr class="border-t-2 border-gray-300 dark:border-gray-700">
                                                        <td colspan="3" class="px-3 py-2"></td>
                                                        <td class="px-3 py-2 text-right text-sm font-bold text-gray-900 dark:text-white">Total:</td>
                                                        <td class="px-3 py-2 text-right text-base font-bold text-gray-900 dark:text-white">
                                                            Rp {{ number_format($order->total, 0, ',', '.') }}
                                                        </td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    @else
                                        <div class="py-8 text-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                            </svg>
                                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No items in this order</h3>
                                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">This order doesn't contain any items.</p>
                                            <div class="mt-6">
                                                <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-medium text-white hover:bg-indigo-700">
                                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                                    </svg>
                                                    Back to Orders
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    
                    
                    <!-- Actions Card -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="font-semibold text-lg mb-4 text-gray-900 dark:text-gray-100 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                                </svg>
                                Order Actions
                            </h3>
                            
                            <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <a href="{{ route('admin.orders.edit', $order) }}" class="inline-flex justify-center items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300 text-white rounded-md font-medium transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                        </svg>
                                        Update Order Status
                                    </a>
                                    <a href="{{ route('admin.orders.index') }}" class="inline-flex justify-center items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 focus:ring-4 focus:ring-gray-200 text-gray-800 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-white dark:focus:ring-gray-700 rounded-md font-medium transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                                        </svg>
                                        Back to All Orders
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>