/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
        './node_modules/flowbite/**/*.js',
    ],
    theme: {
        extend: {
            fontFamily: {
                display: ['"Plus Jakarta Sans"', 'sans-serif'],
                sans: ['Inter', 'sans-serif'],
                mono: ['"JetBrains Mono"', 'monospace'],
            },
            colors: {
                brand: {
                    50: '#f0f0ff',
                    100: '#e5e3ff',
                    400: '#818cf8',
                    500: '#6366f1',
                    600: '#4f46e5',
                    700: '#7c3aed',
                },
            },
            backgroundImage: {
                'brand-gradient': 'linear-gradient(160deg, #4f46e5 0%, #7c3aed 100%)',
            },
        },
    },
    plugins: [
        require('flowbite/plugin'),
    ],
}