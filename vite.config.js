import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                // Page-specific modules (only those WITHOUT Blade directives)
                'resources/js/modules/songs.js',
                'resources/js/modules/admin-radio-servers.js',
                'resources/js/modules/admin-radio-server-edit.js',
                'resources/js/modules/admin-radio-server-create.js',
                'resources/js/modules/admin-rss-feeds.js',
                'resources/js/modules/djs.js',
                'resources/js/modules/radio-page.js',
                'resources/js/modules/song-requests.js',
                'resources/js/modules/message-show.js',
                'resources/js/modules/stations.js',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
