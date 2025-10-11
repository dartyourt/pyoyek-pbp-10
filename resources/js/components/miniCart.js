// Alpine mini-cart component
if (window.Alpine) {
    window.Alpine.data('miniCart', function () {
        return {
            loading: false,
            items_count: 0,
            subtotal: 0,
            items: [],

            init() {
                this.fetchCart();
                window.addEventListener('cart.updated', (e) => {
                    if (e.detail) this.applyCartData(e.detail);
                    else this.fetchCart();
                });
            },

            applyCartData(data) {
                this.items_count = data.items_count || 0;
                this.subtotal = data.subtotal || 0;
                this.items = data.items || [];
            },

            fetchCart() {
                this.loading = true;
                axios.get('/cart')
                    .then(res => {
                        if (res.data) {
                            const data = res.data.cart || res.data;
                            this.applyCartData(data);
                        }
                    })
                    .catch(() => {})
                    .finally(() => this.loading = false);
            },

            formatCurrency(value) {
                return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(value);
            }
        }
    });
}
