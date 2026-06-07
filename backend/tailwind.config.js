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
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                display: ['Fraunces', 'Georgia', 'serif'],
                heading: ['Outfit', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    DEFAULT: '#004d40', // Deep teal primary
                    light: '#00796b',
                    dark: '#00251a',
                },
                accent: {
                    DEFAULT: '#f57f17', // Warm gold accent
                    light: '#ffb04c',
                    dark: '#bc5100',
                },
                background: {
                    DEFAULT: '#f8fafc',
                    dark: '#0f172a',
                },
                card: {
                    DEFAULT: '#ffffff',
                    dark: '#1e293b',
                }
            }
        },
    },

    darkMode: 'class',
    plugins: [forms],
};
