import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';

import { revealOnScroll, parallax } from './modules/motion';
import slider from './modules/slider';
import audioPlayer from './modules/audio-player';
import cart from './modules/cart';

Alpine.plugin(collapse);

Alpine.data('slider', slider);
Alpine.data('audioPlayer', audioPlayer);
Alpine.data('cart', cart);

/** Sticky header state: condensed + shadow once the user scrolls. */
Alpine.data('siteHeader', () => ({
    scrolled: false,
    open: false,
    init() {
        const onScroll = () => { this.scrolled = window.scrollY > 24; };
        onScroll();
        window.addEventListener('scroll', onScroll, { passive: true });
        this.$watch('open', (value) => {
            document.documentElement.classList.toggle('overflow-hidden', value);
        });
    },
}));

/** Lightweight share helper for article pages. */
Alpine.data('share', (url, title) => ({
    copied: false,
    async copy() {
        try {
            await navigator.clipboard.writeText(url);
            this.copied = true;
            setTimeout(() => (this.copied = false), 2000);
        } catch (e) { /* clipboard unavailable */ }
    },
    native() {
        if (navigator.share) navigator.share({ title, url });
        else this.copy();
    },
}));

window.Alpine = Alpine;
Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    revealOnScroll();
    parallax();
});
