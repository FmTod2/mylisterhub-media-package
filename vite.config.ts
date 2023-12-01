import { resolve } from 'path';
import { defineConfig } from 'vite';
import dts from 'vite-plugin-dts';
import pkg from './package.json' assert { type: 'json' }

export default defineConfig({
    build: {
        lib: {
            name: pkg.name,
            entry: resolve(__dirname, 'resources/js/main.ts'),
            fileName: (format) => `index.${format}.js`
        },
        outDir: resolve(__dirname, 'resources/dist'),
        rollupOptions: {
            external: [
                ...Object.keys(pkg.dependencies), // don't bundle dependencies
                /^node:.*/, // don't bundle built-in Node.js modules (use protocol imports!)
            ],
        },
        target: 'esnext', // transpile as little as possible
    },
    plugins: [dts()],
});
