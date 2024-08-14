import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
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

// export default defineConfig({
//     plugins: [
//         vue(),
//     ],
//     resolve: {
//         alias: {
//             'vue': path.resolve(__dirname, 'node_modules/vue/dist/vue.esm-bundler.js'),
//         },
//     },
//     build: {
//         manifest: true,
//         outDir: 'public/build',
//     },
//     server: {
//         proxy: {
//             '/api': 'http://localhost:8001', // Adjust if you need to proxy API requests
//         },
//     },
// });
