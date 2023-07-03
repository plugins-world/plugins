import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            publicDirectory: "../../../public/assets/plugins/WechatLogin/",
            hotFile: '../../../public/hot',
            buildDirectory: "../../../../extensions/plugins/WechatLogin/resources/assets/build/",
            input: [
                'resources/assets/js/app.js'
            ],
            refresh: true,
        }),
    ],
});
