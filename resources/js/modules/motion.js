const prefersReduced = () => window.matchMedia('(prefers-reduced-motion: reduce)').matches;

/**
 * Adds `.is-visible` to `.reveal` / `.reveal-img` elements as they enter the viewport.
 * Children of `[data-reveal-stagger]` receive incremental delays.
 */
export function revealOnScroll() {
    document.querySelectorAll('[data-reveal-stagger]').forEach((group) => {
        const step = parseInt(group.dataset.revealStagger || '90', 10);
        [...group.querySelectorAll(':scope > .reveal, :scope > .reveal-img')].forEach((el, i) => {
            el.style.setProperty('--reveal-delay', `${i * step}ms`);
        });
    });

    const targets = document.querySelectorAll('.reveal, .reveal-img');
    if (prefersReduced() || !('IntersectionObserver' in window)) {
        targets.forEach((el) => el.classList.add('is-visible'));
        return;
    }

    const io = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                io.unobserve(entry.target);
            }
        });
    }, { rootMargin: '0px 0px -8% 0px', threshold: 0.08 });

    targets.forEach((el) => io.observe(el));
}

/** Subtle vertical parallax for elements with `data-parallax="0.15"`. */
export function parallax() {
    const items = [...document.querySelectorAll('[data-parallax]')];
    if (!items.length || prefersReduced()) return;

    let ticking = false;
    const update = () => {
        const vh = window.innerHeight;
        items.forEach((el) => {
            const rect = el.parentElement.getBoundingClientRect();
            if (rect.bottom < 0 || rect.top > vh) return;
            const speed = parseFloat(el.dataset.parallax) || 0.15;
            const offset = (rect.top + rect.height / 2 - vh / 2) * -speed;
            el.style.transform = `translate3d(0, ${offset.toFixed(1)}px, 0) scale(1.15)`;
        });
        ticking = false;
    };

    window.addEventListener('scroll', () => {
        if (!ticking) { requestAnimationFrame(update); ticking = true; }
    }, { passive: true });
    update();
}
