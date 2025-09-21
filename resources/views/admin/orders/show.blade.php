<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Order Details #') . $order->id }}
            </h2>
            <a href="{{ route('admin.orders.edit', $order) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                Update Status
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Order Information</h3>
                            <div class="mb-2"><strong>Order ID:</strong> #{{ $order->id }}</div>
                            <div class="mb-2"><strong>Customer:</strong> {{ $order->user->name }}</div>
                            <div class="mb-2"><strong>Email:</strong> {{ $order->user->email }}</div>
                            <div class="mb-2"><strong>Total:</strong> Rp {{ number_format($order->total, 0, ',', '.') }}</div>
                            <div class="mb-2"><strong>Status:</strong> 
                                <span class="px-2 py-1 rounded text-sm 
                                    @if($order->status == 'diproses') bg-yellow-100 text-yellow-800
                                    @elseif($order->status == 'dikirim') bg-blue-100 text-blue-800
                                    @elseif($order->status == 'selesai') bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                            <div class="mb-2"><strong>Order Date:</strong> {{ $order->created_at->format('d M Y H:i') }}</div>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold mb-4">Shipping Information</h3>
                            <div class="mb-2"><strong>Address:</strong> {{ $order->address_text }}</div>
                            @if($order->phone)
                                <div class="mb-2"><strong>Phone:</strong> {{ $order->phone }}</div>
                            @endif
                            @if($order->notes)
                                <div class="mb-2"><strong>Notes:</strong> {{ $order->notes }}</div>
                            @endif
                        </div>
                    </div>

                    <h3 class="text-lg font-semibold mb-4">Order Items</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="px-4 py-2 text-left">Product</th>
                                    <th class="px-4 py-2 text-left">Price</th>
                                    <th class="px-4 py-2 text-left">Quantity</th>
                                    <th class="px-4 py-2 text-left">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderItems as $item)
                                    <tr class="border-t">
                                        <td class="px-4 py-2">{{ $item->product->name }}</td>
                                        <td class="px-4 py-2">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                        <td class="px-4 py-2">{{ $item->qty }}</td>
                                        <td class="px-4 py-2">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('admin.orders.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Back to Orders
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>