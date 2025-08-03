import { fileURLToPath, URL } from 'node:url';

import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';
import vueJsx from '@vitejs/plugin-vue-jsx';
import VueDevTools from 'vite-plugin-vue-devtools';
import Unocss from 'unocss/vite';
import { presetAttributify, presetUno } from 'unocss'; // Presets

export default defineConfig({
  plugins: [
    vue(), // Vue 3 plugin for Vite
    vueJsx(), // Vue JSX plugin for Vite
    VueDevTools(), // Vue DevTools integration plugin for Vite
    Unocss({
      presets: [presetAttributify(), presetUno()] // UNO.css integration with presets
    })
  ],
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url)) // Alias for the src directory
    }
  }
});
