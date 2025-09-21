<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Product Details') }}
            </h2>
            <div>
                <a href="{{ route('admin.products.edit', $product) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">
                    Edit Product
                </a>
                <a href="{{ route('admin.products.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Back to Products
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Product Image -->
                        <div>
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-64 object-cover rounded-lg">
                            @else
                                <div class="w-full h-64 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <span class="text-gray-400 text-lg">No Image</span>
                                </div>
                            @endif
                        </div>

                        <!-- Product Details -->
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4">{{ $product->name }}</h3>

                            <div class="space-y-3">
                                <div>
                                    <span class="font-semibold text-gray-700 dark:text-gray-300">Category:</span>
                                    <span class="text-gray-900 dark:text-gray-100">{{ $product->category ? $product->category->name : 'Uncategorized' }}</span>
                                </div>

                                <div>
                                    <span class="font-semibold text-gray-700 dark:text-gray-300">Price:</span>
                                    <span class="text-2xl font-bold text-green-600">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                </div>

                                <div>
                                    <span class="font-semibold text-gray-700 dark:text-gray-300">Stock:</span>
                                    <span class="text-gray-900 dark:text-gray-100">{{ $product->stock }} units</span>
                                </div>

                                <div>
                                    <span class="font-semibold text-gray-700 dark:text-gray-300">Status:</span>
                                    @if($product->stock > 0)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            In Stock
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Out of Stock
                                        </span>
                                    @endif
                                </div>

                                <div>
                                    <span class="font-semibold text-gray-700 dark:text-gray-300">Created:</span>
                                    <span class="text-gray-900 dark:text-gray-100">{{ $product->created_at->format('M d, Y \a\t g:i A') }}</span>
                                </div>

                                <div>
                                    <span class="font-semibold text-gray-700 dark:text-gray-300">Last Updated:</span>
                                    <span class="text-gray-900 dark:text-gray-100">{{ $product->updated_at->format('M d, Y \a\t g:i A') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    @if($product->description)
                        <div class="mt-6">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Description</h4>
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <p class="text-gray-700 dark:text-gray-300">{{ $product->description }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Actions -->
                    <div class="mt-6 flex space-x-4">
                        <a href="{{ route('admin.products.edit', $product) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Edit Product
                        </a>
                        <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this product?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                Delete Product
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>