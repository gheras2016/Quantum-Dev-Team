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
