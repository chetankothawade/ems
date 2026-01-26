/// <reference types="vitest/globals" />

import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'

export default defineConfig({
  plugins: [react()],
  server: {
    port: 3030,
    strictPort: true,
    open: true,
    proxy: {
      '/student': 'http://localhost:8080',
      '/admin': 'http://localhost:8080'
    },
    historyApiFallback: true   
  },
  test: {
    globals: true,
    environment: 'jsdom',
    setupFiles: ['./src/test/setup.ts']
  }
});

