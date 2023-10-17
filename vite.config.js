import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import fs from 'fs';
import basicSsl from '@vitejs/plugin-basic-ssl';
import mkcert from 'vite-plugin-mkcert';
export default defineConfig({
    // server: {
    //     https: true,
    // },

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
