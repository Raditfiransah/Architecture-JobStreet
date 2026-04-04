import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            colors: {
                primary: {
                    50:  '#f0faf4',
                    100: '#d6f2e0',
                    200: '#aee3c2',
                    300: '#7dcfa0',
                    400: '#4db87c',
                    500: '#2d9e60',
                    600: '#1e7a47',
                    700: '#155c35',
                    800: '#0e3d23',
                    900: '#071f12',
                },
                ink: {
                    DEFAULT: '#0f1a14',
                    soft:    '#2a3d32',
                    muted:   '#4d6358',
                },
                surface: {
                    DEFAULT: '#ffffff',
                    soft:    '#f8faf9',
                    muted:   '#f0f5f2',
                },
            },
            fontFamily: {
                sans: ['Plus Jakarta Sans', ...defaultTheme.fontFamily.sans],
                mono: ['JetBrains Mono', ...defaultTheme.fontFamily.mono],
            },
            borderColor: {
                DEFAULT: '#e4ede8',
            },
            boxShadow: {
                'soft': '0 1px 3px 0 rgba(125, 207, 160, 0.12), 0 1px 2px -1px rgba(125, 207, 160, 0.08)',
            },
        },
    },

    plugins: [forms],
};
