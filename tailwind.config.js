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
                accent: {
                    DEFAULT: '#2563EB',
                    hover: '#1D4ED8',
                    soft: '#EFF6FF',
                },
                surface: '#F8FAFC',
                darkbg: '#0F172A',
            },
            fontFamily: {
                sans: ['Inter', 'sans-serif'],
            },
            borderRadius: {
                xs: '0.75rem',    /* 12px  — badge, elemen sangat kecil */
                sm: '1rem',       /* 16px  — input, dropdown, ikon-box kecil */
                DEFAULT: '1.25rem', /* 20px  — tombol, card kecil (grid menu dashboard) */
                lg: '1.5rem',     /* 24px  — card besar, hero card, card form */
                full: '9999px',   /* avatar bulat penuh, kalau memang dibutuhkan */
            },
        },
    },

    plugins: [forms],
};