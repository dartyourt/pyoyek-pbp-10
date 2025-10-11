// Simple module to convert product add-to-cart forms into AJAX requests
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('form[data-ajax="add-to-cart"]').forEach(form => {
        const btn = form.querySelector('button[type="submit"]');
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            if (!btn) return;

            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = 'Adding...';

            const formData = new FormData(form);
            const url = form.getAttribute('action') || window.location.pathname;

            axios.post(url, formData)
                .then(res => {
                    const data = res.data || {};
                    const message = data.message || (data.success ? 'Product added to cart successfully!' : (data.error || 'Unexpected response'));

                    // show in-page toast instead of native alert (so origin text isn't shown)
                    window.dispatchEvent(new CustomEvent('toast', { detail: { message: message, type: 'success' } }));

                    // emit a cart.updated event so mini-cart can refresh
                    if (data.cart) {
                        window.dispatchEvent(new CustomEvent('cart.updated', { detail: data.cart }));
                    } else {
                        window.dispatchEvent(new CustomEvent('cart.updated'));
                    }
                })
                .catch(err => {
                    let msg = 'Failed to add product to cart.';
                    if (err.response && err.response.data && err.response.data.message) msg = err.response.data.message;
                    window.dispatchEvent(new CustomEvent('toast', { detail: { message: msg, type: 'error' } }));
                })
                .finally(() => {
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                });
        });
    });
});
