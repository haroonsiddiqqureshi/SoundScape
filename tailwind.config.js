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
                'secondary': 'var(--color-secondary)',
                'secondary-high': 'var(--color-secondary-high)',
                'secondary-medium': 'var(--color-secondary-medium)',
                'secondary-low': 'var(--color-secondary-low)',
                'accent': 'var(--color-accent)',
                'accent-high': 'var(--color-accent-high)',
                'accent-medium': 'var(--color-accent-medium)',
                'accent-low': 'var(--color-accent-low)',
                'background': 'var(--color-background)',
                'background-high': 'var(--color-background-high)',
                'background-medium': 'var(--color-background-medium)',
                'background-low': 'var(--color-background-low)',
                'text': 'var(--color-text)',
                'text-high': 'var(--color-text-high)',
                'text-medium': 'var(--color-text-medium)',
                'text-low': 'var(--color-text-low)',
                'card': 'var(--color-card)',
                'card-high': 'var(--color-card-high)',
                'card-medium': 'var(--color-card-medium)',
                'card-low': 'var(--color-card-low)',
            },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms, typography],
};
