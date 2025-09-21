<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 sm:p-8 bg-white border-b border-gray-200">
                    <h1 class="text-3xl font-bold text-gray-900 mb-8">My Orders</h1>

                    @if (session('success'))
                        <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-lg">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($orders->count() > 0)
                        <div class="space-y-6">
                            @foreach($orders as $order)
                                <div class="border border-gray-200 rounded-2xl p-6 shadow-sm hover:shadow-md transition">
                                    <div class="flex items-center justify-between gap-6">
                                        
                                        <!-- Left: Foto -->
                                        <div class="flex items-center gap-4 flex-1">
                                            <img src="{{ asset('storage/' . $order->orderItems->first()->product->image_path) }}" 
                                                alt="Product" 
                                                class="h-16 w-16 rounded-lg object-cover border hover:scale-105 transition" />
                                            
                                            <!-- Info Order -->
                                            <div class="flex flex-col">
                                                <div class="flex items-center gap-2">
                                                    <p class="font-semibold text-gray-900">Order #{{ $order->id }}</p>
                                                    <span class="text-sm text-gray-500">• {{ $order->created_at->format('d F Y') }}</span>
                                                </div>
                                                <p class="text-base font-bold text-gray-900 mt-1">
                                                    Rp {{ number_format($order->total, 0, ',', '.') }}
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Right: Status + Tombol -->
                                        <div class="flex items-center gap-3">
                                            <span class="px-3 py-1 rounded-full text-xs font-medium
                                                @if($order->status === 'dikirim') bg-green-100 text-green-800
                                                @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                            <a href="{{ route('orders.show', $order) }}" 
                                               class="px-4 py-2 rounded-lg text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 transition whitespace-nowrap">
                                                View Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-8">
                            {{ $orders->links() }}
                        </div>
                    @else
                        <!-- No Orders -->
                        <div class="text-center py-16">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="mt-2 text-lg font-medium text-gray-900">No orders yet</h3>
                            <p class="mt-1 text-sm text-gray-500">When you place an order, it will appear here.</p>
                            <div class="mt-6">
                                <a href="{{ route('catalog.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">
                                    Start Shopping
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>