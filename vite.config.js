import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
  plugins: [
    laravel({
      input: ['resources/css/app.css', 'resources/js/app.js'],
      refresh: true,
    }),
  ],
  css: {
    postcss: false, // PostCSSを無効化して-35エラーを回避
  },
  server: {
    host: true, // 0.0.0.0
    watch: {
      usePolling: true,
      interval: 1000,
      ignored: ['**/node_modules/**', '**/.git/**']
    }, // ← これが -35 の回避に効きます
    hmr: {
      host: 'localhost',
      protocol: 'ws',
      port: 5173,
      overlay: false // エラーオーバーレイを無効化
    },
  },
});
