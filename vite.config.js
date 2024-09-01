import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue2';
export default defineConfig({
  resolve: {
    alias: {
      'vue/dist/vue.esm': 'vue/dist/vue.js',
      'vue': "vue/dist/vue.js"
    },
  },
  plugins: [
    laravel({
      buildDirectory: 'vendor/canvas',
      input: [
        'resources/sass/app.scss',
        'resources/js/app.js',
      ],
      refresh: true,
    }),
    vue({
      template: {
        transformAssetUrls: {
          base: null,
          includeAbsolute: false,
        },
      },
    }),
  ],

});
