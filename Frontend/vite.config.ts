import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'

//FOR DOCKER DEVELOPMENT PURPOSES

export default defineConfig({
  plugins: [react()],
  server: {
    port: 5173,
    strictPort: true,
    host: true,
    proxy: {
      '/student': {
        target: 'http://api:8000',
        changeOrigin: true
      },
      '/admin': {
        target: 'http://api:8000',
        changeOrigin: true
      }
    }

  },
  test: {
    globals: true,
    environment: 'jsdom',
    setupFiles: './src/test/setup.ts'
  }
})



//FOR LOCAL DEVELOPMENT PURPOSES

// export default defineConfig({
//   plugins: [react()],
//   server: {
//     port: 3030,
//     strictPort: true,
//     open: true,
//     proxy: {
//       '/student': 'http://localhost:8000',
//       '/admin': 'http://localhost:8000'
//     },
//     historyApiFallback: true   
//   },
//   test: {
//     globals: true,
//     environment: 'jsdom',
//     setupFiles: ['./src/test/setup.ts']
//   }
// });

