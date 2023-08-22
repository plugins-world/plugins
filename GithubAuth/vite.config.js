import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            publicDirectory: "../../../public/assets/plugins/GithubAuth/",
            hotFile: '../../../public/hot',
            buildDirectory: "../../../../extensions/plugins/GithubAuth/resources/assets/build/",
            input: [
                'resources/assets/js/app.js'
            ],
            refresh: true,
        }),
    ],
});
