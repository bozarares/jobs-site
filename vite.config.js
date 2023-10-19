import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
export default defineConfig({
    // server: {
    //     https: true,
    // },
    optimizeDeps: {},
    plugins: [
        // mkcert(),
        laravel({
            input: 'resources/js/app.js',
            refresh: true,
            detectTls: 'jobs.test',
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
});
