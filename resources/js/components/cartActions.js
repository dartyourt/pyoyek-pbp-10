// Handles AJAX update and remove actions on the cart page
(function () {
    document.addEventListener('DOMContentLoaded', () => {

        // helper: render cart table body from server JSON
        function renderCartFromData(cart) {
            if (!cart || !Array.isArray(cart.items)) return;
            const tbody = document.querySelector('table.min-w-full tbody');
            if (!tbody) return;

            const rows = cart.items.map(item => {
                let imgSrc = '/images/default-product.png';
                if (item.product_image_path) {
                    const path = item.product_image_path;
                    if (path.startsWith('http') || path.startsWith('//')) {
                        imgSrc = path;
                    } else {
                        // ensure absolute origin so the browser loads immediately after JS re-render
                        const origin = window.location.origin || '';
                        if (path.startsWith('/')) {
                            imgSrc = origin + path;
                        } else {
                            imgSrc = origin + '/storage/' + path;
                        }
                    }
                }
                const productName = item.product_name || (item.product && item.product.name) || '';
                const price = item.price || item.unit_price || 0;
                const qty = item.quantity || item.qty || item.qty || 1;
                const lineTotal = item.line_total || (price * qty);
                const cartItemId = item.id || item.cart_item_id || '';

                return `
                    <tr data-product-id="${item.product_id || ''}" data-cart-item-id="${cartItemId}">
                        <td class="px-5 py-5 border-b border-gray-200 bg-white dark:bg-gray-800 text-sm">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-16 h-16">
                                    <img class="w-full h-full rounded-md object-cover" src="${imgSrc}" alt="${productName}" />
                                </div>
                                <div class="ml-3">
                                    <p class="text-gray-900 dark:text-white whitespace-no-wrap font-semibold">${productName}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white dark:bg-gray-800 text-sm">
                            <p class="text-gray-900 dark:text-white whitespace-no-wrap">Rp ${new Intl.NumberFormat('id-ID').format(price)}</p>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white dark:bg-gray-800 text-sm">
                            <form action="/cart/items/${cartItemId}" method="POST" class="flex justify-center" data-ajax="cart-update">
                                <input name="_method" type="hidden" value="PATCH">
                                <input name="qty" type="number" value="${qty}" min="1" class="w-16 rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 text-center">
                                <button type="submit" class="ml-2 text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 text-xs font-bold">UPDATE</button>
                            </form>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white dark:bg-gray-800 text-sm text-right">
                            <p class="text-gray-900 dark:text-white whitespace-no-wrap font-semibold cart-row-total">Rp ${new Intl.NumberFormat('id-ID').format(lineTotal)}</p>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white dark:bg-gray-800 text-sm text-center">
                            <form action="/cart/items/${cartItemId}" method="POST" data-ajax="cart-remove">
                                <input name="_method" type="hidden" value="DELETE">
                                <input type="hidden" name="product_id" value="${item.product_id || item.productId || ''}">
                                <button type="submit" class="text-gray-500 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400" title="Remove item">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </td>
                    </tr>`;
            }).join('\n');

            tbody.innerHTML = rows;

            // update subtotal display
            const subtotalElem = document.querySelector('.cart-subtotal');
            if (subtotalElem && cart.subtotal !== undefined) {
                subtotalElem.textContent = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(cart.subtotal);
            }

            // re-attach handlers for the new forms
            setupUpdateForms();
            setupRemoveForms();
        }

    // store removed items so we can undo (token => payload)
    const removedItemsStore = {};

    // setup handlers are separated so we can re-run them after re-render
        function setupUpdateForms() {
            document.querySelectorAll('form[data-ajax="cart-update"]').forEach(form => {
                // remove existing listener by cloning node
                const newForm = form.cloneNode(true);
                form.parentNode.replaceChild(newForm, form);
                newForm.addEventListener('submit', (e) => {
                    e.preventDefault();
                    const btn = newForm.querySelector('button[type="submit"]');
                    const qtyInput = newForm.querySelector('input[name="qty"]');
                    if (!btn || !qtyInput) return;

                    // optimistic update: compute new totals immediately
                    const oldQty = parseInt(qtyInput.getAttribute('value') || qtyInput.value, 10);
                    const newQty = parseInt(qtyInput.value, 10);
                    const row = newForm.closest('tr');
                    const priceText = row.querySelector('td:nth-child(2) p');
                    const price = priceText ? parseInt(priceText.textContent.replace(/[^0-9]/g, ''), 10) : 0;
                    const rowTotalElem = row.querySelector('.cart-row-total');
                    const subtotalElem = document.querySelector('.cart-subtotal');

                    // store previous values for rollback
                    const prevRowTotal = rowTotalElem ? rowTotalElem.textContent : null;
                    const prevSubtotal = subtotalElem ? subtotalElem.textContent : null;

                    // update UI immediately
                    if (rowTotalElem) {
                        const newLineTotal = price * newQty;
                        rowTotalElem.textContent = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(newLineTotal);
                    }

                    if (subtotalElem) {
                        const numeric = parseInt(subtotalElem.textContent.replace(/[^0-9]/g, ''), 10) || 0;
                        const newSubtotal = numeric + (price * (newQty - oldQty));
                        subtotalElem.textContent = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(newSubtotal);
                    }

                    const originalText = btn.innerHTML;
                    btn.disabled = true;
                    btn.innerHTML = 'Updating...';

                    const formData = new FormData(newForm);
                    const url = newForm.getAttribute('action');

                    axios.post(url, formData)
                        .then(res => {
                            const data = res.data || {};
                            window.dispatchEvent(new CustomEvent('toast', { detail: { message: data.message || 'Cart updated', type: 'success' } }));
                            if (data.cart) window.dispatchEvent(new CustomEvent('cart.updated', { detail: data.cart }));
                            else window.dispatchEvent(new CustomEvent('cart.updated'));

                            if (data.item && data.item.line_total && rowTotalElem) {
                                rowTotalElem.textContent = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(data.item.line_total);
                            }
                            if (data.cart && data.cart.subtotal !== undefined && subtotalElem) {
                                subtotalElem.textContent = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(data.cart.subtotal);
                            }
                        })
                        .catch(err => {
                            if (rowTotalElem && prevRowTotal !== null) rowTotalElem.textContent = prevRowTotal;
                            if (subtotalElem && prevSubtotal !== null) subtotalElem.textContent = prevSubtotal;

                            let msg = 'Failed to update cart.';
                            if (err.response && err.response.data && err.response.data.message) msg = err.response.data.message;
                            window.dispatchEvent(new CustomEvent('toast', { detail: { message: msg, type: 'error' } }));
                        })
                        .finally(() => {
                            btn.disabled = false;
                            btn.innerHTML = originalText;
                            qtyInput.setAttribute('value', qtyInput.value);
                        });
                });
            });
        }

        function setupRemoveForms() {
            // Remove forms: attach listeners by cloning nodes to avoid duplicate handlers
            document.querySelectorAll('form[data-ajax="cart-remove"]').forEach(form => {
                const newForm = form.cloneNode(true);
                form.parentNode.replaceChild(newForm, form);
                newForm.addEventListener('submit', (e) => {
                    e.preventDefault();
                    const btn = newForm.querySelector('button[type="submit"]');
                    if (!btn) return;

                    const originalHtml = btn.innerHTML;
                    btn.disabled = true;
                    btn.innerHTML = 'Removing...';

                    const url = newForm.getAttribute('action');

                    // gather payload to allow undo (try to capture item id and qty)
                    const row = newForm.closest('tr');
                    const productName = row ? row.querySelector('p.font-semibold')?.textContent : null;
                    // prefer explicit hidden product_id if present, otherwise parse from URL
                    const qtyInput = row.querySelector('input[name="qty"]');
                    const qty = qtyInput ? parseInt(qtyInput.value, 10) : 1;
                    const hiddenProductId = row.querySelector('input[name="product_id"]') || newForm.querySelector('input[name="product_id"]');
                    const productIdFromHidden = hiddenProductId ? hiddenProductId.value : null;
                    const productIdMatch = url.match(/\/cart\/items\/(\d+)/);
                    const productIdRaw = productIdFromHidden || (productIdMatch ? productIdMatch[1] : null);
                    const productId = productIdRaw ? parseInt(productIdRaw, 10) : null;

                    axios.delete(url)
                        .then(res => {
                            const data = res.data || {};

                            // generate token and store payload for undo
                            const token = Math.random().toString(36).slice(2, 9);
                            // fallback: try to read data attribute on row if productId is null
                            const rowProductId = row && row.dataset && row.dataset.productId ? parseInt(row.dataset.productId, 10) : null;
                            const finalProductId = productId || rowProductId;
                            removedItemsStore[token] = { product_id: finalProductId, qty: qty };

                            // dispatch toast with undo token and longer duration
                            window.dispatchEvent(new CustomEvent('toast', { detail: { message: (productName ? productName + ' removed' : (data.message || 'Item removed')), type: 'success', undoToken: token, duration: 7000 } }));

                            if (data.cart) window.dispatchEvent(new CustomEvent('cart.updated', { detail: data.cart }));
                            else window.dispatchEvent(new CustomEvent('cart.updated'));

                            // remove row from DOM
                            if (row) row.remove();

                            // update subtotal if provided
                            if (data.cart && data.cart.subtotal !== undefined) {
                                const subtotalElem = document.querySelector('.cart-subtotal');
                                if (subtotalElem) subtotalElem.textContent = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(data.cart.subtotal);
                            }
                        })
                        .catch(err => {
                            let msg = 'Failed to remove item.';
                            if (err.response && err.response.data && err.response.data.message) msg = err.response.data.message;
                            window.dispatchEvent(new CustomEvent('toast', { detail: { message: msg, type: 'error' } }));
                        })
                        .finally(() => {
                            btn.disabled = false;
                            btn.innerHTML = originalHtml;
                        });
                });
            });
        }

        // initial setup
        setupUpdateForms();
        setupRemoveForms();

        // listen for undo events dispatched by the toast UI
        window.addEventListener('undo', (e) => {
            const token = e.detail && e.detail.token;
            if (!token || !removedItemsStore[token]) return;
            const payload = removedItemsStore[token];

            // ensure payload has product_id
            if (!payload || !payload.product_id) {
                window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'Unable to restore item: missing product id.', type: 'error' } }));
                return;
            }

            // attempt to re-add the item using /cart/add endpoint
            axios.post('/cart/add', payload)
                .then(res => {
                    const data = res.data || {};
                    window.dispatchEvent(new CustomEvent('toast', { detail: { message: data.message || 'Item restored', type: 'success' } }));
                    // re-render the cart table from server-provided JSON if available
                    if (data.cart) {
                        renderCartFromData(data.cart);
                        window.dispatchEvent(new CustomEvent('cart.updated', { detail: data.cart }));
                    } else {
                        window.dispatchEvent(new CustomEvent('cart.updated'));
                    }
                    // remove the token after successful restore
                    delete removedItemsStore[token];
                })
                .catch(err => {
                    let msg = 'Failed to restore item.';
                    if (err.response && err.response.data && err.response.data.message) msg = err.response.data.message;
                    window.dispatchEvent(new CustomEvent('toast', { detail: { message: msg, type: 'error' } }));
                });
        });

    });
})();
