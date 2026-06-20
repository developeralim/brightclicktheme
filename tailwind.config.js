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
                    50: "var(--color-primary-50,  #eff6ff)",
                    100: "var(--color-primary-100, #dbeafe)",
                    500: "var(--color-primary-500, #3b82f6)",
                    600: "var(--color-primary-600, #2563eb)",
                    700: "var(--color-primary-700, #1d4ed8)",
                    900: "var(--color-primary-900, #1e3a8a)",
                },
                accent: "var(--color-accent, #f59e0b)",
                surface: "var(--color-surface, #ffffff)",
                muted: "var(--color-muted, #f3f4f6)",
                body: "var(--color-body-bg, #f9fafb)",
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
