/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./*.php",
        "./inc/**/*.php",
        "./customizer/**/*.php",
        "./template-parts/**/*.php",
        "./woocommerce/**/*.php",
        "./assets/js/**/*.js",
        "./blocks/**/*.{js,json,php}",
        "./src/**/*.{js,jsx,ts,tsx}",
    ],

    safelist: [
        "bc-btn",
        "bc-btn--premium",
        "bc-btn--solid",
        "bc-btn--outline",
        "bc-btn--minimal",
        "bc-btn--pill",
    ],

    important: false,

    theme: {
        extend: {
            colors: {
                primary: {
                    50:  "var(--color-primary-50,  #fdf7e8)",
                    100: "var(--color-primary-100, #f2e2b2)",
                    500: "var(--color-primary-500, #c99140)",
                    600: "var(--color-primary-600, #b07828)",
                    700: "var(--color-primary-700, #8f6118)",
                    900: "var(--color-primary-900, #3d2a0a)",
                },
                accent:  "var(--color-accent,   #d4a843)",
                surface: "var(--color-surface,  #fffdf8)",
                muted:   "var(--color-muted,    #f5eedb)",
                body:    "var(--color-body-bg,  #fdf8f0)",
            },

            fontFamily: {
                heading: ["var(--font-heading, Georgia)", "serif"],
                body: ["var(--font-body, system-ui)", "sans-serif"],
                mono: ["var(--font-mono, monospace)", "monospace"],
            },

            spacing: {
                "wp-xs": "var(--wp--style--block-gap, 1rem)",
                "wp-sm": "clamp(1.25rem, 3vw, 2rem)",
                "wp-md": "clamp(2rem, 5vw, 4rem)",
                "wp-lg": "clamp(3rem, 8vw, 7rem)",
            },

            maxWidth: {
                content: "var(--wp--style--global--content-size, 65ch)",
                wide: "var(--wp--style--global--wide-size, 90rem)",
                site: "var(--wp--style--global--site-size, 1440px)",
            },

            typography: (theme) => ({
                DEFAULT: {
                    css: {
                        "--tw-prose-body": theme("colors.gray[700]"),
                        "--tw-prose-headings": theme("colors.gray[900]"),
                        "--tw-prose-links": theme("colors.primary.600"),
                        "--tw-prose-bold": theme("colors.gray[900]"),
                        "--tw-prose-code": theme("colors.primary.700"),
                        "--tw-prose-pre-bg": theme("colors.gray[900]"),
                        maxWidth: "none",
                    },
                },
            }),
        },
    },

    plugins: [require("@tailwindcss/typography"), require("@tailwindcss/forms")],
};
