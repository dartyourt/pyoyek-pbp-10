<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Manage Products') }}
            </h2>
            <a href="{{ route('admin.products.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Add New Product
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Search Form -->
                    <form method="GET" action="{{ route('admin.products.index') }}" class="mb-4 flex gap-2">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by product name..." class="flex-1 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md shadow-sm px-3 py-2">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Search</button>
                        @if(request('search'))
                            <a href="{{ route('admin.products.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Clear</a>
                        @endif
                    </form>

                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-gray-700">
                                    <th class="px-4 py-2 text-left text-gray-600 dark:text-gray-300">Image</th>
                                    <th class="px-4 py-2 text-left text-gray-600 dark:text-gray-300">Name</th>
                                    <th class="px-4 py-2 text-left text-gray-600 dark:text-gray-300">Category</th>
                                    <th class="px-4 py-2 text-left text-gray-600 dark:text-gray-300">Price</th>
                                    <th class="px-4 py-2 text-left text-gray-600 dark:text-gray-300">Stock</th>
                                    <th class="px-4 py-2 text-left text-gray-600 dark:text-gray-300">Status</th>
                                    <th class="px-4 py-2 text-left text-gray-600 dark:text-gray-300">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $product)
                                    <tr class="border-t dark:border-gray-700">
                                        <td class="px-4 py-2">
                                            @if($product->image_path)
                                                <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="w-16 h-16 object-cover rounded">
                                            @else
                                                <div class="w-16 h-16 bg-gray-200 dark:bg-gray-600 rounded flex items-center justify-center">
                                                    <span class="text-gray-500 dark:text-gray-400">No Image</span>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-4 py-2">{{ $product->name }}</td>
                                        <td class="px-4 py-2">{{ $product->category->name }}</td>
                                        <td class="px-4 py-2">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                        <td class="px-4 py-2">{{ $product->stock }}</td>
                                        <td class="px-4 py-2">
                                            <span class="px-2 py-1 rounded text-xs {{ $product->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                                {{ $product->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-2">
                                            <a href="{{ route('admin.products.show', $product) }}" class="text-blue-600 dark:text-blue-400 hover:underline mr-2">View</a>
                                            <a href="{{ route('admin.products.edit', $product) }}" class="text-yellow-600 dark:text-yellow-400 hover:underline mr-2">Edit</a>
                                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 dark:text-red-400 hover:underline">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-4 py-2 text-center">No products found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>