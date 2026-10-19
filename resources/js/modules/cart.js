/**
 * Progressive-enhancement add-to-cart. Forms still work without JS (normal POST);
 * with JS the request is sent via fetch and the header badge updates in place.
 */
export default () => ({
    loading: false,
    added: false,
    async submit(event) {
        const form = event.target;
        this.loading = true;
        try {
            const res = await fetch(form.action, {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest', Accept: 'application/json' },
                body: new FormData(form),
            });
            if (!res.ok) throw new Error('Request failed');
            const data = await res.json();
            window.dispatchEvent(new CustomEvent('cart-updated', { detail: data }));
            this.added = true;
            setTimeout(() => (this.added = false), 2200);
        } catch (e) {
            form.submit();
        } finally {
            this.loading = false;
        }
    },
});
