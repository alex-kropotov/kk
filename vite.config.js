import {defineConfig} from 'vite'
import path, {resolve} from 'path'
import {fileURLToPath} from 'url';
import fg from 'fast-glob';
import * as fs from "node:fs";

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

// Функция для получения динамических точек входа
function getPageEntries() {
  const pageFiles = fg.sync(['front/js/pages/**/*.js'], {
    absolute: true
  });

  let entries = {
    app: path.resolve(__dirname, 'front/app.js')
  };

  pageFiles.forEach(file => {
    const name = path.basename(file, '.js');
    entries[name] = file.replace(/\//g, '\\');
  });

  // Сохраняем содержимое entries в файл для отладки
  fs.writeFileSync(
      path.resolve(__dirname, 'debug-entries.json'),
      JSON.stringify(entries, null, 2)
  );

  return entries;
}

// https://vitejs.dev/config/
export default defineConfig({
  logLevel: 'error', // Показывать только ошибки
  css: {
    preprocessorOptions: {
      scss: {
        api: 'modern-compiler',
      },
    },
  },
  server: {
    proxy: {
      '/': {
        // change the URL according to your local web server environment
        target: 'http://kk:8083/',
        changeOrigin: true,
        secure: false
      },
    }
  },

  base: "./",

  publicDir: './front/assets',
  resolve: {
    alias: {
      '@': path.resolve(__dirname, 'front'),
      '@js': path.resolve(__dirname, 'front/js'),
      '@common': path.resolve(__dirname, 'front/js/common'),
      '@pages': path.resolve(__dirname, 'front/js/pages'),
      '@scss': path.resolve(__dirname, 'front/scss'),
      '@admin': path.resolve(__dirname, 'front/js/pages/admin')
    },
  },

  build: {
    manifest: true,
    minify: false,
    sourcemap: true,
    cssCodeSplit: true,
    // jsCodeSplit: true,

    rollupOptions: {

      input: {
        app: resolve(__dirname, 'front/app.js'),
        admin: path.resolve(__dirname, 'front/js/pages/adminLogin.js'),
      },
      output: {
        dir: 'public',
        manualChunks(id) {
          // fs.appendFileSync(
          //     path.resolve(__dirname, 'debug-entries.json'),
          //     JSON.stringify(id) + '\n'
          // );
          if (id.includes('node_modules')) {
            return 'vendor';
          }

          // if (id.includes('/js/common/')) {
          //
          //   return 'common';
          // }
          //
          // if (id.includes('/js/pages/')) {
          //   return path.basename(id, '.js');
          // }
        },

        entryFileNames: 'assets/js/[name]-[hash].js',
        chunkFileNames: 'assets/js/[name]-[hash].js',
        assetFileNames: 'assets/[ext]/[name]-[hash].[ext]'
        // assetFileNames: 'assets/[ext]/[name].[ext]'
      }
    },
  }
})
