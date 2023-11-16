import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import AutoImport from 'unplugin-auto-import/vite';
import { defineConfig } from 'vite';

export default defineConfig({
    optimizeDeps: {},

    plugins: [
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
        AutoImport({
            include: [
                /\.vue$/,
                /\.vue\?vue/, // .vue
            ],
            imports: ['vue'],
        }),
    ],
});
