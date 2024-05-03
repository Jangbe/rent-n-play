import fs from 'fs';
import { defineConfig, loadEnv } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import Pages from 'vite-plugin-pages';
import Layouts from 'vite-plugin-vue-layouts';
import vueJsxPlugin from '@vitejs/plugin-vue-jsx';

export default ({ mode }) => {
    process.env = { ...process.env, ...loadEnv(mode, process.cwd()) };

    return defineConfig({
        plugins: [
            laravel({
                input: [
                    'resources/sass/app.scss',
                    'resources/js/app.js',
                ],
                refresh: true
            }),
            vue({
                template: {
                    transformAssetUrls: {
                        base: null,
                        includeAbsolute: false,
                    },
                },
            }),
            vueJsxPlugin(),
            Pages({ dirs: ['./resources/js/pages'] }),
            Layouts({
                layoutsDirs: './resources/js/layouts/',
            })
        ],
        server: detectServerConfig(process.env.VITE_PUSHER_HOST),
        resolve: {
            alias: {
                vue: 'vue/dist/vue.esm-bundler.js',
            },
        },
    });
}

function detectServerConfig(host) {
    let keyPath = process.env.VITE_SSL_LOCAL_PK
    let certificatePath = process.env.VITE_SSL_LOCAL_CERT

    if (!fs.existsSync(keyPath)) {
        return { watch: { usePolling: true } }
    }

    if (!fs.existsSync(certificatePath)) {
        return { watch: { usePolling: true } }
    }

    return {
        hmr: { host },
        host,
        watch: { usePolling: true },
        port: process.env.VITE_PORT,
        https: {
            key: fs.readFileSync(keyPath),
            cert: fs.readFileSync(certificatePath),
        },
    }
}
