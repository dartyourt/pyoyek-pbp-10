// Handles AJAX update and remove actions on the cart page
(function () {
    document.addEventListener('DOMContentLoaded', () => {
        // Update quantity forms
        document.querySelectorAll('form[data-ajax="cart-update"]').forEach(form => {
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                const btn = form.querySelector('button[type="submit"]');
                const qtyInput = form.querySelector('input[name="qty"]');
                if (!btn || !qtyInput) return;

                // optimistic update: compute new totals immediately
                const oldQty = parseInt(qtyInput.getAttribute('value') || qtyInput.value, 10);
                const newQty = parseInt(qtyInput.value, 10);
                const row = form.closest('tr');
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
                    // parse numeric subtotal and adjust
                    const numeric = parseInt(subtotalElem.textContent.replace(/[^0-9]/g, ''), 10) || 0;
                    const newSubtotal = numeric + (price * (newQty - oldQty));
                    subtotalElem.textContent = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(newSubtotal);
                }

                const originalText = btn.innerHTML;
                btn.disabled = true;
                btn.innerHTML = 'Updating...';

                const formData = new FormData(form);
                const url = form.getAttribute('action');

                axios.post(url, formData)
                    .then(res => {
                        const data = res.data || {};
                        window.dispatchEvent(new CustomEvent('toast', { detail: { message: data.message || 'Cart updated', type: 'success' } }));
                        if (data.cart) window.dispatchEvent(new CustomEvent('cart.updated', { detail: data.cart }));
                        else window.dispatchEvent(new CustomEvent('cart.updated'));

                        // if server returns authoritative totals, set them
                        if (data.item && data.item.line_total && rowTotalElem) {
                            rowTotalElem.textContent = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(data.item.line_total);
                        }
                        if (data.cart && data.cart.subtotal !== undefined && subtotalElem) {
                            subtotalElem.textContent = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(data.cart.subtotal);
                        }
                    })
                    .catch(err => {
                        // rollback to previous totals on error
                        if (rowTotalElem && prevRowTotal !== null) rowTotalElem.textContent = prevRowTotal;
                        if (subtotalElem && prevSubtotal !== null) subtotalElem.textContent = prevSubtotal;

                        let msg = 'Failed to update cart.';
                        if (err.response && err.response.data && err.response.data.message) msg = err.response.data.message;
                        window.dispatchEvent(new CustomEvent('toast', { detail: { message: msg, type: 'error' } }));
                    })
                    .finally(() => {
                        btn.disabled = false;
                        btn.innerHTML = originalText;
                        // update input value attribute to new value so subsequent edits use updated oldQty
                        qtyInput.setAttribute('value', qtyInput.value);
                    });
            });
        });

        // Remove item forms
        document.querySelectorAll('form[data-ajax="cart-remove"]').forEach(form => {
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                const btn = form.querySelector('button[type="submit"]');
                if (!btn) return;

                const originalHtml = btn.innerHTML;
                btn.disabled = true;
                btn.innerHTML = 'Removing...';

                const url = form.getAttribute('action');

                axios.delete(url)
                    .then(res => {
                        const data = res.data || {};
                        window.dispatchEvent(new CustomEvent('toast', { detail: { message: data.message || 'Item removed', type: 'success' } }));
                        if (data.cart) window.dispatchEvent(new CustomEvent('cart.updated', { detail: data.cart }));
                        else window.dispatchEvent(new CustomEvent('cart.updated'));

                        // remove row from DOM
                        const row = form.closest('tr');
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

    });
})();
