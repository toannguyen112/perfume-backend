//https://raw.githubusercontent.com/tailwindlabs/tailwindcss/master/stubs/defaultConfig.stub.js

module.exports = {
    mode: "jit",
    purge: [
        './resources/views/**/*.blade.php',
        "./resources/Frontend/js/**/*.vue",
    ],
    darkMode: false,
    theme: {
        screens: {
            sm: "640px",
            md: "768px",
            lg: "1024px",
            xl: "1280px",
            "2xl": "1536px",
            "max-2xl": { max: "1535px" },
            "max-xl": { max: "1279px" },
            "max-lg": { max: "1023px" },
            "max-md": { max: "767px" },
            "max-sm": { max: "639px" },
        },
        extend: {
            colors: {
                primary: {
                    DEFAULT: "#FF6F00",
                    dark: "#E56400",
                    darker: "#DB5F00",
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

            fontFamily: {
                display: ["Fahkwang", "sans-serif"],
                sans: [
                    "Averta",
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

            container: {
                center: true,
                padding: {
                    DEFAULT: "1rem",
                    sm: "1.5rem",
                    lg: "2rem",
                },
            },
        },
    },
    variants: {
        extend: {},
    },
    plugins: [
        require("@tailwindcss/typography"),
    ],
};
