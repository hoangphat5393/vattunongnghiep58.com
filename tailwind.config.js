import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: ['./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php', './storage/framework/views/*.php', './resources/**/*.blade.php', './resources/**/*.js', './resources/**/*.vue', './new_template/**/*.html'],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', 'sans-serif', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                leaf: {
                    50: '#f7fee7',
                    100: '#ecfccb',
                    500: '#84cc16',
                    600: '#65a30d',
                    700: '#4d7c0f',
                    900: '#365314',
                },
            },
            backgroundImage: {
                'hero-pattern': "url('https://www.transparenttextures.com/patterns/leaf.png')",
            },
        },
    },
    corePlugins: {
        preflight: false,
    },
    plugins: [],
};
