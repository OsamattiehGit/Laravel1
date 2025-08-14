import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/home.css',
                'resources/css/app.css',
                'resources/css/register.css',
                'resources/css/course.css',
                'resources/css/pricing.css',
                'resources/css/courses.css',
                'resources/css/admin.css',
                'resources/js/app.js',
                'resources/js/admin.js',
                'resources/js/course.js',
                'resources/js/courses.js',
                'resources/css/footer.css',
            ],
            refresh: true,
        }),
    ],
});
