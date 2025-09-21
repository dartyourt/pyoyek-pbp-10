<x-app-layout>
    <div class="py-8">
                         <!-- Product Image -->
                        <div>
                            @if($product->image_path)
                                <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" 
                                    class="w-full h-auto object-cover rounded-lg shadow">
                            @else
                                <div class="w-full h-80 flex items-center justify-center bg-gray-100 text-gray-400 rounded-lg border">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div> class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Breadcrumbs -->
            <nav class="flex mb-5" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('catalog.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-indigo-600">
                            <svg aria-hidden="true" class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                            </svg>
                            Home
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg aria-hidden="true" class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <a href="{{ route('catalog.index', ['category' => $product->category_id]) }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-indigo-600 md:ml-2">
                                {{ $product->category->name }}
                            </a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg aria-hidden="true" class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">{{ $product->name }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
            
            <!-- Product Details -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Product Image -->
                        <div>
                            @if($product->image_path)
                                <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" 
                                    class="w-full h-auto object-cover rounded-lg shadow">
                            @else
                                <div class="w-full h-80 flex items-center justify-center bg-gray-100 text-gray-400 rounded-lg border">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Product Info -->
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">{{ $product->name }}</h1>
                            <p class="mt-1 text-sm text-indigo-600">{{ $product->category->name }}</p>
                            
                            <div class="mt-4">
                                <p class="text-3xl font-bold text-gray-900">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </p>
                            </div>
                            
                            <div class="mt-4">
                                <h2 class="text-sm font-medium text-gray-900">Stock Status</h2>
                                @if($product->stock > 0)
                                    <p class="text-sm text-green-600">In Stock ({{ $product->stock }} available)</p>
                                @else
                                    <p class="text-sm text-red-600">Out of Stock</p>
                                @endif
                            </div>
                            
                            <div class="mt-6 border-t border-gray-200 pt-6">
                                <h2 class="text-sm font-medium text-gray-900">Description</h2>
                                <div class="mt-2 prose prose-sm text-gray-500">
                                    <p>{{ $product->description }}</p>
                                </div>
                            </div>
                            
                            @auth
                                <div class="mt-6">
                                    <form action="{{ route('cart.add') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        
                                        <div class="mb-4">
                                            <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                                            <div class="mt-1 flex rounded-md shadow-sm">
                                                <input type="number" name="quantity" id="quantity" min="1" max="{{ $product->stock }}" value="1" 
                                                    class="block w-20 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                                    @disabled($product->stock <= 0)>
                                            </div>
                                        </div>
                                        
                                        <button type="submit" 
                                            class="w-full bg-indigo-600 border border-transparent rounded-md py-3 px-8 flex items-center justify-center text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                            @disabled($product->stock <= 0)>
                                            @if($product->stock > 0)
                                                Add to Cart
                                            @else
                                                Out of Stock
                                            @endif
                                        </button>
                                    </form>
                                </div>
                            @else
                                <div class="mt-6">
                                    <a href="{{ route('login') }}" 
                                        class="w-full inline-block text-center bg-gray-600 border border-transparent rounded-md py-3 px-8 font-medium text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                        Login to Buy
                                    </a>
                                </div>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Related Products -->
            @if($relatedProducts->count() > 0)
                <div class="mt-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Related Products</h2>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        @foreach($relatedProducts as $relatedProduct)
                            <div class="group relative bg-white rounded-lg shadow overflow-hidden border border-gray-200 hover:shadow-lg transition-shadow duration-300">
                                <div class="aspect-w-3 aspect-h-2 bg-gray-200 w-full overflow-hidden">
                                    @if($relatedProduct->image_path)
                                        <img src="{{ asset('storage/' . $relatedProduct->image_path) }}" alt="{{ $relatedProduct->name }}" 
                                            class="w-full h-48 object-cover object-center group-hover:opacity-75">
                                    @else
                                        <div class="w-full h-48 flex items-center justify-center bg-gray-100 text-gray-400">
                                            No Image
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="p-4">
                                    <h3 class="text-sm font-medium text-gray-900">
                                        <a href="{{ route('catalog.show', $relatedProduct) }}">
                                            <span aria-hidden="true" class="absolute inset-0"></span>
                                            {{ $relatedProduct->name }}
                                        </a>
                                    </h3>
                                    <p class="mt-1 text-sm font-bold text-gray-900">Rp {{ number_format($relatedProduct->price, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>