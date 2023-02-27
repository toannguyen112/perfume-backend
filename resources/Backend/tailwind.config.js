module.exports = {
    mode: "jit",
    purge: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/Backend/js/**/*.vue",
    ],

    theme: {
        container: {
            center: true,
        },
        extend: {
            fontSize: {
                "2xs": ["0.55rem", { lineHeight: "0.75rem" }],
            },
            fontFamily: {
                display: ["sans-serif"],
                sans: [
                    "system-ui",
                    "-apple-system",
                    "BlinkMacSystemFont",
                    '"Segoe UI"',
                    "Roboto",
                    '"Helvetica Neue"',
                    "Arial",
                    '"Noto Sans"',
                    "sans-serif",
                    '"Apple Color Emoji"',
                    '"Segoe UI Emoji"',
                    '"Segoe UI Symbol"',
                    '"Noto Color Emoji"',
                ],
            },
            colors: {
                primary: {
                    DEFAULT: "#FF6F00",
                    dark: "#E56400",
                    darker: "#DB5F00",
                },
                gray: {
                    50: "#F8FAFC",
                    100: "#F1F5F9",
                    200: "#e9ecef",
                    300: "#dee2e6",
                    400: "#ced4da",
                    500: "#adb5bd",
                    600: "#545866",
                    700: "#131928",
                    800: "#1c1c1c",
                    900: "#111111",
                },
            },
            typography: (theme) => ({
                DEFAULT: {
                    css: {
                        color: theme("colors.gray.900"),
                        a: {
                            color: "#0000EE",
                        },
                    },
                },
            }),
        },
    },

    variants: {
        extend: {
            opacity: ["disabled"],
            borderStyle: ["focus"],
            display: ["group-hover"],
        },
    },

    plugins: [require("@tailwindcss/typography")],
};
