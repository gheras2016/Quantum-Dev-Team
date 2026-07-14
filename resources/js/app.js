import './bootstrap';
import Alpine from 'alpinejs';

/**
 * Global dark-mode store, persisted to localStorage and synced with the
 * system preference. Shared by both the public site and the admin panel.
 */
Alpine.store('theme', {
    dark: false,

    init() {
        this.dark =
            localStorage.theme === 'dark' ||
            (!('theme' in localStorage) &&
                window.matchMedia('(prefers-color-scheme: dark)').matches);
        this.apply();
    },

    toggle() {
        this.dark = !this.dark;
        localStorage.theme = this.dark ? 'dark' : 'light';
        this.apply();
    },

    apply() {
        document.documentElement.classList.toggle('dark', this.dark);
    },
});

/**
 * Auto-generate a URL slug from a source field while the slug is untouched.
 * Usage: <div x-data="slugify()"> with x-ref="source" and x-ref="slug".
 */
Alpine.data('slugify', () => ({
    touched: false,

    slug(value) {
        return value
            .toString()
            .trim()
            .toLowerCase()
            .replace(/[^\wء-ي\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-');
    },

    generate() {
        if (!this.touched && this.$refs.source && this.$refs.slug) {
            this.$refs.slug.value = this.slug(this.$refs.source.value);
        }
    },
}));

window.Alpine = Alpine;
Alpine.start();
Alpine.store('theme').init();

/**
 * Animated count-up for elements with a [data-count] attribute. The number
 * animates from 0 to the target once the element scrolls into view.
 */
function initCountUp() {
    const els = document.querySelectorAll('[data-count]');
    if (!els.length || !('IntersectionObserver' in window)) return;

    const animate = (el) => {
        const target = parseInt(el.dataset.count, 10) || 0;
        const suffix = el.dataset.suffix || '';
        const duration = 1400;
        const start = performance.now();
        const step = (now) => {
            const progress = Math.min((now - start) / duration, 1);
            const eased = 1 - Math.pow(1 - progress, 3);
            el.textContent = Math.floor(eased * target) + suffix;
            if (progress < 1) requestAnimationFrame(step);
        };
        requestAnimationFrame(step);
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                animate(entry.target);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.4 });

    els.forEach((el) => observer.observe(el));
}

if (document.readyState !== 'loading') initCountUp();
else document.addEventListener('DOMContentLoaded', initCountUp);
