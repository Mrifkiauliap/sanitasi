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
                sans: ["Inter", "ui-sans-serif", "system-ui", "sans-serif"], // Changed to Inter for cleaner look if available, fallback to system
                serif: ["Source Serif 4", "Georgia", "serif"],
                mono: ["Source Code Pro", "ui-monospace", "monospace"],
            },
            backgroundImage: {
                'gradient-sidebar': 'linear-gradient(180deg, #f0fdf4 0%, #ffffff 100%)',
                'gradient-hero':    'linear-gradient(135deg, #059669 0%, #0d9488 100%)',
                'gradient-card':    'linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%)',
            },
            boxShadow: {
                'sidebar': '2px 0 12px 0 rgba(5,150,105,0.06)',
                'card-hover': '0 8px 24px -4px rgba(5,150,105,0.12), 0 4px 8px -2px rgba(0,0,0,0.06)',
                'stat': '0 1px 3px 0 rgba(0,0,0,0.08), 0 1px 2px -1px rgba(0,0,0,0.06)',
            },
            colors: {
                // Base
                background: "#f0f9ff",         // sky-50
                foreground: "#0c1a2e",         // very dark blue

                // Surface
                card: "#ffffff",
                "card-foreground": "#0c1a2e",

                // Primary — Sky Blue (air, bersih, profesional)
                primary: "#0284c7",            // sky-600
                "primary-foreground": "#ffffff",

                // Secondary
                secondary: "#e0f2fe",          // sky-100
                "secondary-foreground": "#0c4a6e",

                // Accent
                accent: "#e0f2fe",             // sky-100
                "accent-foreground": "#075985", // sky-800

                // Sidebar
                "sidebar-background": "#ffffff",
                "sidebar-foreground": "#0c1a2e",
                "sidebar-primary": "#0284c7",  // sky-600
                "sidebar-primary-foreground": "#ffffff",
                "sidebar-accent": "#f0f9ff",   // sky-50
                "sidebar-accent-foreground": "#075985",
                "sidebar-border": "#e0f2fe",   // sky-100
                "sidebar-ring": "#38bdf8",     // sky-400
                "sidebar-muted": "#64748b",

                // Borders & Inputs
                border: "#e2e8f0", // Slate-200
                "input-border": "#e2e8f0",
                "input-background": "#ffffff",
                ring: "#10b981", // Emerald-500 ring
                radius: "0.5rem",

                // Semantic Colors
                success: "#22c55e", // Green-500
                "success-light": "#dcfce7", // Green-100

                danger: "#ef4444", // Red-500
                "danger-light": "#fee2e2", // Red-100
                "danger-lighter": "#fef2f2",
                "danger-hover": "#dc2626", // Red-600
                "danger-focus": "#b91c1c", // Red-700

                warning: "#f59e0b", // Amber-500
                "warning-light": "#fef3c7", // Amber-100

                info: "#3b82f6", // Blue-500 - Keep Blue for Info distinct from Success
                "info-light": "#dbeafe", // Blue-100

                // Gray shades (Slate) for neutrality
                "gray-50": "#f8fafc",
                "gray-100": "#f1f5f9",
                "gray-200": "#e2e8f0",
                "gray-300": "#cbd5e1",
                "gray-400": "#94a3b8",
                "gray-500": "#64748b",
                "gray-600": "#475569",
                "gray-700": "#334155",
                "gray-800": "#1e293b",
                "gray-900": "#0f172a",
                "gray-50-bg": "#f9fafb",
            },
        },
    },

    plugins: [forms],
};
