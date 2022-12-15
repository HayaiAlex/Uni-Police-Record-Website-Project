import { defineConfig } from 'vite'
import { resolve } from "path";
import { svelte } from '@sveltejs/vite-plugin-svelte'

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [svelte()],
  build: {
    outDir: '../police/',
    assetsDir: './',
    emptyOutDir: true,
    rollupOptions: {
      input:
        resolve(__dirname, "index.html"),

      output: {
        // Prevent vendor.js being created
        manualChunks: undefined,
        entryFileNames: "[name].js",
        // chunkFileNames: "zzz-[name].js",
        // this got rid of the hash on style.css
        assetFileNames: "[name].[ext]",
      },
    },
    // Prevent vendor.css being created
    cssCodeSplit: false,
    // prevent some warnings
    chunkSizeWarningLimit: 60000,
  }
})
