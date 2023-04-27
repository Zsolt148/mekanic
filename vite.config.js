import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue2';
import laravel, { refreshPaths } from 'laravel-vite-plugin';
const path = require("path");

export default defineConfig({

    server: {
        hmr: {
            host: 'localhost'
        },
        usePolling: true
    },

    plugins: [
        laravel({
            input: [
                'resources/js/app.js'
            ],
            refresh: [
                ...refreshPaths
            ],
            buildDirectory: '/build'
        }),
        vue(),
    ],
    build: {
        outDir: 'public/build',
        assetsDir: 'assets',
    },
    resolve: {
        extensions: [
            ".mjs",
            ".js",
            ".ts",
            ".jsx",
            ".tsx",
            ".json",
            ".vue",
            ".scss",
        ],
        alias: {
            '@': path.resolve(__dirname, "./resources/js"),
            'img': path.resolve(__dirname, "./resources/images"),
            'scss': path.resolve(__dirname, "./resources/scss")
        }
    }
});
