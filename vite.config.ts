import { ConfigEnv, defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';
import path from 'path';

export default defineConfig(({ command }: ConfigEnv) => {
    return {
        base: command === 'build' ? '/dist/' : '',
        publicDir: false,
        build: {
            manifest: true,
            outDir: 'public/dist',
            rollupOptions: {
                input: {
                    app: 'resources/js/app.ts',
                },
            },
        },
        server: {
            strictPort: true,
            port: 3030,
            // https: true,
            hmr: {
                host: 'localhost',
            },
        },
        resolve: {
            alias: {
                '@': path.resolve(__dirname, './resources/js'),
            },
        },
        plugins: [vue()],
        optimizeDeps: {
            include: [
                '@inertiajs/inertia',
                '@inertiajs/inertia-vue3',
                'axios',
                'vue',
            ],
        },
    };
});
