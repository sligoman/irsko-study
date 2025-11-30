import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        vue(),
        // ensure tailwind plugin runs before the laravel plugin so CSS imports are handled
        tailwindcss(),
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            // use the esm-bundler build so runtime template compilation is available when needed
            'vue': 'vue/dist/vue.esm-bundler.js',
        },
    },
    server: {
        host: '0.0.0.0',
        port: 5197, // Match the port in your error (5182)
        cors: true,
        hmr: {
            host: 'irskostudy.local', // Use your local domain here
            port: 5197,
            protocol: 'ws',
        },
    },
});
