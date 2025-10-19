<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Product Details') }}
            </h2>
            <a href="{{ route('admin.products.edit', $product) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                Edit Product
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            @if($product->image_path)
                                <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="w-full h-64 object-cover rounded">
                            @else
                                <div class="w-full h-64 bg-gray-200 dark:bg-gray-600 rounded flex items-center justify-center">
                                    <span class="text-gray-500 dark:text-gray-400">No Image</span>
                                </div>
                            @endif
                        </div>

                        <div>
                            <h3 class="text-2xl font-bold mb-4 text-gray-900 dark:text-gray-100">{{ $product->name }}</h3>
                            
                            <div class="mb-4">
                                <strong class="text-gray-700 dark:text-gray-300">Description:</strong>
                                <p class="mt-1 text-gray-600 dark:text-gray-400">{{ $product->description ?: 'No description' }}</p>
                            </div>

                            <div class="mb-4">
                                <strong class="text-gray-700 dark:text-gray-300">Category:</strong>
                                <p class="mt-1 text-gray-600 dark:text-gray-400">{{ $product->category->name }}</p>
                            </div>

                            <div class="mb-4">
                                <strong class="text-gray-700 dark:text-gray-300">Price:</strong>
                                <p class="mt-1 text-gray-600 dark:text-gray-400">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                            </div>

                            <div class="mb-4">
                                <strong class="text-gray-700 dark:text-gray-300">Stock:</strong>
                                <p class="mt-1 text-gray-600 dark:text-gray-400">{{ $product->stock }}</p>
                            </div>

                            <div class="mb-4">
                                <strong class="text-gray-700 dark:text-gray-300">Status:</strong>
                                <span class="px-2 py-1 rounded text-sm {{ $product->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                    {{ $product->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>

                            <div class="mb-4">
                                <strong class="text-gray-700 dark:text-gray-300">Created At:</strong>
                                <p class="mt-1 text-gray-600 dark:text-gray-400">{{ $product->created_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('admin.products.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Back to Products
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>