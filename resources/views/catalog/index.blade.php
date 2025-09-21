<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Search and Filters -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white">
                    <form action="{{ route('catalog.index') }}" method="GET" class="space-y-4">
                        <div class="flex flex-col md:flex-row gap-4">
                            <!-- Search -->
                            <div class="flex-1">
                                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                                <input type="text" name="search" id="search" value="{{ $search ?? '' }}" 
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    placeholder="Search products...">
                            </div>
                            
                            <!-- Category Filter -->
                            <div class="w-full md:w-1/4">
                                <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                                <select name="category" id="category" 
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">All Categories</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" @selected($categoryId == $category->id)>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <!-- Sort Order -->
                            <div class="w-full md:w-1/4">
                                <label for="sort" class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
                                <select name="sort" id="sort" 
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="newest" @selected($sort === 'newest')>Newest</option>
                                    <option value="price_low" @selected($sort === 'price_low')>Price: Low to High</option>
                                    <option value="price_high" @selected($sort === 'price_high')>Price: High to Low</option>
                                    <option value="name" @selected($sort === 'name')>Name</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                Apply Filters
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Products Grid -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white">
                    @if($products->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                            @foreach($products as $product)
                                <div class="group relative bg-white rounded-lg shadow overflow-hidden border border-gray-200 hover:shadow-lg transition-shadow duration-300">
                                    <div class="aspect-w-3 aspect-h-2 bg-gray-200 w-full overflow-hidden">
                                        @if($product->image_path)
                                            <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" 
                                                class="w-full h-48 object-cover object-center group-hover:opacity-75">
                                        @else
                                            <div class="w-full h-48 flex items-center justify-center bg-gray-100 text-gray-400">
                                                No Image
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="p-4">
                                        <h3 class="text-sm font-medium text-gray-900">
                                            <a href="{{ route('catalog.show', $product) }}">
                                                <span aria-hidden="true" class="absolute inset-0"></span>
                                                {{ $product->name }}
                                            </a>
                                        </h3>
                                        <p class="mt-1 text-xs text-gray-500">{{ $product->category ? $product->category->name : 'Uncategorized' }}</p>
                                        <p class="mt-2 text-sm font-bold text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                        
                                        <div class="mt-3 flex justify-between items-center">
                                            <div class="text-xs text-gray-500">
                                                @if($product->stock > 0)
                                                    <span class="text-green-600">In Stock ({{ $product->stock }})</span>
                                                @else
                                                    <span class="text-red-600">Out of Stock</span>
                                                @endif
                                            </div>
                                            
                                            @auth
                                                <form action="{{ route('cart.add') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                    <input type="hidden" name="quantity" value="1">
                                                    <button type="submit" 
                                                        class="text-sm bg-indigo-600 hover:bg-indigo-700 text-white py-1 px-3 rounded-md"
                                                        @disabled($product->stock <= 0)>
                                                        Add to Cart
                                                    </button>
                                                </form>
                                            @else
                                                <a href="{{ route('login') }}" class="text-sm text-indigo-600 hover:text-indigo-900">
                                                    Login to Buy
                                                </a>
                                            @endauth
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $products->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                            <h3 class="mt-2 text-lg font-medium text-gray-900">No products found</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Try adjusting your search or filter to find what you're looking for.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>