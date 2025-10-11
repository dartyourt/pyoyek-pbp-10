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

                        // Optionally update row total and subtotal if returned
                        if (data.item && data.item.line_total) {
                            const row = form.closest('tr');
                            const totalElem = row.querySelector('.cart-row-total');
                            if (totalElem) totalElem.textContent = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(data.item.line_total);
                        }
                        if (data.cart && data.cart.subtotal !== undefined) {
                            const subtotalElem = document.querySelector('.cart-subtotal');
                            if (subtotalElem) subtotalElem.textContent = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(data.cart.subtotal);
                        }
                    })
                    .catch(err => {
                        let msg = 'Failed to update cart.';
                        if (err.response && err.response.data && err.response.data.message) msg = err.response.data.message;
                        window.dispatchEvent(new CustomEvent('toast', { detail: { message: msg, type: 'error' } }));
                    })
                    .finally(() => {
                        btn.disabled = false;
                        btn.innerHTML = originalText;
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
