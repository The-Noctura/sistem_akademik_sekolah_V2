import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                accent: { DEFAULT: '#2563EB', hover: '#1D4ED8', soft: '#EFF6FF' },
                surface: '#F8FAFC',
            },
            fontFamily: {
                sans: ['Inter', 'sans-serif'],
            },
        },
    },

    plugins: [forms],
};