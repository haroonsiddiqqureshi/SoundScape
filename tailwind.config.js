import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {

    darkMode: 'class',

    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            colors: {
                'primary': 'var(--color-primary)',
                'primary-high': 'var(--color-primary-high)',
                'primary-medium': 'var(--color-primary-medium)',
                'primary-low': 'var(--color-primary-low)',
                'primary-hover': 'var(--color-primary-hover)',
                'secondary': 'var(--color-secondary)',
                'secondary-high': 'var(--color-secondary-high)',
                'secondary-medium': 'var(--color-secondary-medium)',
                'secondary-low': 'var(--color-secondary-low)',
                'secondary-hover': 'var(--color-secondary-hover)',
                'accent': 'var(--color-accent)',
                'accent-high': 'var(--color-accent-high)',
                'accent-medium': 'var(--color-accent-medium)',
                'accent-low': 'var(--color-accent-low)',
                'accent-hover': 'var(--color-accent-hover)',
                'background': 'var(--color-background)',
                'background-high': 'var(--color-background-high)',
                'background-medium': 'var(--color-background-medium)',
                'background-low': 'var(--color-background-low)',
                'background-hover': 'var(--color-background-hover)',
                'text': 'var(--color-text)',
                'text-high': 'var(--color-text-high)',
                'text-medium': 'var(--color-text-medium)',
                'text-low': 'var(--color-text-low)',
                'text-hover': 'var(--color-text-hover)',
                'text-reverse': 'var(--color-text-reverse)',
                'text-reverse-high': 'var(--color-text-reverse-high)',
                'text-reverse-medium': 'var(--color-text-reverse-medium)',
                'text-reverse-low': 'var(--color-text-reverse-low)',
                'text-reverse-hover': 'var(--color-text-reverse-hover)',
                'card': 'var(--color-card)',
                'card-high': 'var(--color-card-high)',
                'card-medium': 'var(--color-card-medium)',
                'card-low': 'var(--color-card-low)',
                'card-hover': 'var(--color-card-hover)',
                'map-tiles': 'var(--map-tiles-filter)',
            },
            fontFamily: {
                sans: ['Poppins', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms, typography],
};
