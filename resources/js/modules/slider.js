/**
 * Accessible slider (testimonials). Usage:
 * <div x-data="slider({ count: 5, interval: 7000 })"> ... </div>
 */
export default ({ count = 1, interval = 8000 } = {}) => ({
    index: 0,
    count,
    timer: null,
    paused: false,
    init() {
        this.start();
    },
    start() {
        if (this.count < 2 || window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
        this.stop();
        this.timer = setInterval(() => { if (!this.paused) this.next(); }, interval);
    },
    stop() {
        if (this.timer) clearInterval(this.timer);
    },
    go(i) {
        this.index = (i + this.count) % this.count;
    },
    next() { this.go(this.index + 1); },
    prev() { this.go(this.index - 1); },
    isActive(i) { return this.index === i; },
});
