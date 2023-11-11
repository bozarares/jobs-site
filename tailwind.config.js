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
    darkMode: ['class'],
    theme: {
        extend: {
            colors: {
                background: {
                    50: 'lch(95.76% 0.34 198.43)',
                    100: 'lch(91.21% 0.34 198.43)',
                    200: 'lch(82.25% 0.72 199.44)',
                    300: 'lch(73.07% 1.11 198.92)',
                    400: 'lch(63.66% 1.51 199.27)',
                    500: 'lch(53.95% 1.95 199.08)',
                    600: 'lch(43.66% 1.62 198.99)',
                    700: 'lch(32.9% 1.28 199.14)',
                    800: 'lch(21.51% 0.91 199.23)',
                    900: 'lch(9.15% 0.49 198.79)',
                    950: 'lch(3.56% 0.31 198.43)',
                },
                primary: {
                    50: 'lch(96.13% 7.07 71.97)',
                    100: 'lch(92.54% 15.1 74.25)',
                    200: 'lch(84.98% 30.77 70.79)',
                    300: 'lch(78.05% 48.63 68.65)',
                    400: 'lch(71.35% 66.76 65.51)',
                    500: 'lch(65.51% 80.53 61.36)',
                    600: 'lch(53.08% 67.53 61.59)',
                    700: 'lch(40.07% 53.82 61.94)',
                    800: 'lch(26.5% 38.81 63.34)',
                    900: 'lch(11.93% 19.34 62.33)',
                    950: 'lch(4.37% 6.74 66.51)',
                },
            },
            fontFamily: {
                sans: [
                    'Rubik',
                    // 'Poppins',
                    'Figtree',
                    ...defaultTheme.fontFamily.sans,
                ],
            },
        },
    },

    plugins: [
        forms,
        require('@tailwindcss/typography'),
        require('@tailwindcss/aspect-ratio'),
    ],
};
