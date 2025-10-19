// Live search functionality for product catalog
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    const categorySelect = document.getElementById('category');
    const sortSelect = document.getElementById('sort');
    const searchForm = document.getElementById('searchForm');
    // Selector yang lebih fleksibel untuk menemukan container produk
    // Coba beberapa selector yang mungkin berbeda di antara template yang berbeda
    let productsContainer = document.querySelector('.grid.grid-cols-1.sm\\:grid-cols-2.md\\:grid-cols-3.lg\\:grid-cols-4');
    
    // Fallback selectors jika selector utama tidak ditemukan
    if (!productsContainer) {
        console.log("Trying fallback selector 1");
        productsContainer = document.querySelector('.grid-cols-1.sm\\:grid-cols-2.md\\:grid-cols-3.lg\\:grid-cols-4');
    }
    
    if (!productsContainer) {
        console.log("Trying fallback selector 2");
        productsContainer = document.querySelector('.grid.gap-6');
    }
    
    // Find banner elements
    const heroHeaderCarousel = document.querySelector('[x-data*="slides"]'); // Hero carousel
    const promotionalSections = document.querySelector('.my-16.space-y-16.mb-12'); // Promotional sections
    
    if (!searchInput) return;
    if (!productsContainer) {
        console.error('Products container not found. Selector: .grid.grid-cols-1.sm\\:grid-cols-2.md\\:grid-cols-3.lg\\:grid-cols-4');
        return;
    }
    
    // Prevent form submission on Enter key (since we're doing AJAX search)
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            performSearch();
        });
    }

    let typingTimer;
    const doneTypingInterval = 300; // ms wait after user stops typing
    
        // Add loading indicator
    const loadingIndicator = document.createElement('div');
    loadingIndicator.className = 'hidden w-full py-8 text-center text-gray-600 dark:text-gray-300';
    loadingIndicator.id = 'search-loading-indicator'; // Tambahkan ID untuk debugging
    loadingIndicator.innerHTML = `
        <svg class="animate-spin h-8 w-8 mx-auto text-primary-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <p class="mt-2">Searching...</p>
    `;
    
    if (productsContainer && productsContainer.parentNode) {
        productsContainer.parentNode.insertBefore(loadingIndicator, productsContainer.nextSibling);
    } else {
        console.error('Could not insert loading indicator - productsContainer or its parent is null');
    }    // Events for the search input
    searchInput.addEventListener('input', function() {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(performSearch, doneTypingInterval);
    });
    
    searchInput.addEventListener('keydown', function() {
        clearTimeout(typingTimer);
    });
    
    // Events for filters
    if (categorySelect) {
        categorySelect.addEventListener('change', performSearch);
    }
    
    if (sortSelect) {
        sortSelect.addEventListener('change', performSearch);
    }
    
    // Hide/show banner elements based on search status
    function toggleBannerVisibility(isSearching) {
        if (heroHeaderCarousel) {
            heroHeaderCarousel.style.display = isSearching ? 'none' : '';
        }
        
        if (promotionalSections) {
            promotionalSections.style.display = isSearching ? 'none' : '';
        }
    }
    
    // Check if there are already search params on page load
    const initialQuery = searchInput.value.trim();
    const initialCategory = categorySelect ? categorySelect.value : '';
    const initialSort = sortSelect ? sortSelect.value : 'newest';
    
    // Apply banner visibility on page load
    toggleBannerVisibility(initialQuery || initialCategory || (initialSort && initialSort !== 'newest'));
    
    function performSearch() {
        const query = searchInput.value.trim();
        const category = categorySelect ? categorySelect.value : '';
        const sort = sortSelect ? sortSelect.value : 'newest';
        
        // Show loading
        loadingIndicator.classList.remove('hidden');
        productsContainer.classList.add('hidden');
        
        // Hide banner elements when searching or filtering
        toggleBannerVisibility(query || category || (sort && sort !== 'newest'));
        
        // Construct the search URL
        const url = new URL('/api/search', window.location.origin);
        url.searchParams.append('q', query);
        if (category) url.searchParams.append('category', category);
        url.searchParams.append('sort', sort);
        
        // Debug info - lihat URL yang digunakan untuk troubleshooting
        console.log('Search URL:', url.toString());
        console.log('Products container found:', productsContainer ? 'Yes' : 'No');
        
        fetch(url.toString())
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                // Debug info - lihat respon API
                console.log('Search response:', data);
                
                // Update URL with search params (without reloading)
                const pageUrl = new URL('/catalog', window.location.origin);
                if (query) pageUrl.searchParams.append('search', query);
                if (category) pageUrl.searchParams.append('category', category);
                if (sort) pageUrl.searchParams.append('sort', sort);
                window.history.pushState({ path: pageUrl.toString() }, '', pageUrl.toString());
                
                // Update the product grid
                updateProductsGrid(data.results);
            })
            .catch(error => {
                console.error('Error performing search:', error);
            })
            .finally(() => {
                // Hide loading indicator
                loadingIndicator.classList.add('hidden');
                productsContainer.classList.remove('hidden');
            });
    }
    
    // Menyimpan referensi ke elemen "No Results" di luar fungsi agar tidak tergantung pada konteks 'this'
    let noResultsElement = null;
    
    function updateProductsGrid(products) {
        // Clear the current products
        productsContainer.innerHTML = '';
        
        if (products.length === 0) {
            // Clear the grid container completely
            productsContainer.innerHTML = '';
            
            // Create a full-width absolutely centered message that spans the entire container
            const noResultsDiv = document.createElement('div');
            noResultsDiv.className = 'w-full flex items-center justify-center py-16';
            noResultsDiv.id = 'no-results-message'; // Tambahkan ID untuk mudah diidentifikasi
            noResultsDiv.innerHTML = `
                <div class="text-center w-full max-w-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-16 w-16 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <h3 class="mt-4 text-xl font-medium text-gray-900 dark:text-white">No products found</h3>
                    <p class="mt-2 text-base text-gray-500 dark:text-gray-400">
                        Try adjusting your search or filter to find what you're looking for.
                    </p>
                </div>
            `;
            
            // Replace the grid with our centered message
            productsContainer.parentNode.insertBefore(noResultsDiv, productsContainer);
            productsContainer.style.display = 'none';
            
            // Store the reference to remove it later when results are found
            noResultsElement = noResultsDiv;
            return;
        } else {
            // If we previously added a no results message, remove it
            const existingNoResults = document.getElementById('no-results-message');
            if (existingNoResults) {
                existingNoResults.parentNode.removeChild(existingNoResults);
            }
            productsContainer.style.display = 'grid';
        }
        
        // Add each product to the grid
        products.forEach(product => {
            const productCard = document.createElement('div');
            productCard.className = 'group relative bg-white dark:bg-gray-700 rounded-lg shadow overflow-hidden border border-gray-200 dark:border-gray-600 hover:shadow-lg transition-shadow duration-300';
            
            let imageHtml = '';
            if (product.image) {
                imageHtml = `<img src="${product.image}" alt="${product.name}" class="w-full h-48 object-cover object-center group-hover:opacity-75">`;
            } else {
                imageHtml = `
                    <div class="w-full h-48 flex items-center justify-center bg-gray-100 dark:bg-gray-500 text-gray-400 dark:text-gray-300">
                        No Image
                    </div>
                `;
            }
            
            let stockHtml = '';
            if (product.stock > 0) {
                stockHtml = `<span class="text-green-600">In Stock (${product.stock})</span>`;
            } else {
                stockHtml = `<span class="text-red-600">Out of Stock</span>`;
            }
            
            // Check if user is logged in by seeing if the body has the 'auth' class
            const isLoggedIn = document.body.classList.contains('auth') || document.querySelector('a[href="/login"]') === null;
            
            let actionHtml = '';
            if (isLoggedIn) {
                actionHtml = `
                    <form action="/cart/add" method="POST">
                        <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                        <input type="hidden" name="product_id" value="${product.id}">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" 
                            class="text-sm bg-primary-600 hover:bg-primary-700 text-white py-1 px-3 rounded-md"
                            ${product.stock <= 0 ? 'disabled' : ''}>
                            Add to Cart
                        </button>
                    </form>
                `;
            } else {
                actionHtml = `
                    <a href="/login" class="text-sm text-primary-600 hover:text-primary-900">
                        Login to Buy
                    </a>
                `;
            }
            
            productCard.innerHTML = `
                <div class="aspect-w-3 aspect-h-2 bg-gray-200 dark:bg-gray-600 w-full overflow-hidden">
                    ${imageHtml}
                </div>
                
                <div class="p-4">
                    <h3 class="text-sm font-medium text-gray-900 dark:text-white">
                        <a href="${product.url}">
                            <span aria-hidden="true" class="absolute inset-0"></span>
                            ${product.name}
                        </a>
                    </h3>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">${product.category}</p>
                    <p class="mt-2 text-sm font-bold text-gray-900 dark:text-white">${product.formatted_price}</p>
                    
                    <div class="mt-3 flex justify-between items-center">
                        <div class="text-xs text-gray-500 dark:text-gray-400">
                            ${stockHtml}
                        </div>
                        ${actionHtml}
                    </div>
                </div>
            `;
            
            productsContainer.appendChild(productCard);
        });
    }
});