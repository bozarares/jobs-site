import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    optimizeDeps: {},

    plugins: [
        laravel({
            ssr: 'resources/js/ssr.js',
            input: 'resources/js/app_ssr.js',
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
    ssr: {
        format: 'esm',
        noExternal: ['@inertiajs/server', '@vueup/vue-quill'],
    },
});
