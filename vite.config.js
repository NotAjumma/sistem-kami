import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    build: {
        rollupOptions: {
            input: {
                style: 'resources/css/app.css',
                app: 'resources/js/app.js',
            },
            output: {
                entryFileNames: (chunkInfo) => {
                    if (chunkInfo.name === 'style') {
                        return 'css/style.js'; // Kita rename ke .js dulu, Vite compile CSS jadi JS wrapper
                    }
                    return 'js/[name].js';
                },
                assetFileNames: (assetInfo) => {
                    if (assetInfo.name.endsWith('.css')) {
                        return 'css/[name][extname]';
                    }
                    return 'assets/[name][extname]';
                }
            },
        },
        outDir: 'public',
        emptyOutDir: true,
    }
});
