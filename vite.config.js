import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { VitePWA } from 'vite-plugin-pwa';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
        VitePWA({
            registerType: 'autoUpdate',
            injectRegister: 'auto',
            workbox: {
                // On met en cache tous les assets pour le mode offline
                globPatterns: ['**/*.{js,css,html,ico,png,svg,woff2}'],
                // Cette option permet de garder l'app fonctionnelle même si l'URL change (SPA)
                navigateFallback: '/',
            },
            manifest: {
                name: 'Parking Marché Pro',
                short_name: 'Parking',
                description: 'Gestion de parking hors-ligne pour les agents',
                theme_color: '#3b82f6', // Le bleu par défaut de Tailwind/Laravel
                background_color: '#ffffff',
                display: 'standalone',
                scope: '/',
                start_url: '/',
                icons: [
                    {
                        src: '/icons/icon-192x192.png',
                        sizes: '192x192',
                        type: 'image/png',
                        purpose: 'any maskable'
                    },
                    {
                        src: '/icons/icon-512x512.png',
                        sizes: '512x512',
                        type: 'image/png',
                        purpose: 'any maskable'
                    }
                ]
            }
        })
    ],
});