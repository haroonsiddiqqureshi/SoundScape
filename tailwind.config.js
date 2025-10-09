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
                'primary-low': 'var(--color-primary-low)',
                'secondary': 'var(--color-secondary)',
                'accent': 'var(--color-accent)',
                'background': 'var(--color-background)',
                'text': 'var(--color-text)',
                'text-low': 'var(--color-text-low)',
                'card': 'var(--color-card)',
            },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms, typography],
};
