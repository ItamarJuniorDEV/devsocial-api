import { defineConfig } from '#q-app/wrappers'

export default defineConfig(() => {
  return {
    boot: ['i18n', 'axios'],

    css: ['app.scss'],

    extras: ['material-icons'],

    build: {
      target: {
        browser: ['es2022', 'firefox115', 'chrome115', 'safari14'],
        node: 'node20'
      },

      vueRouterMode: 'history',

      vitePlugins: [
        ['@intlify/unplugin-vue-i18n/vite', {
          ssr: false,
          include: [new URL('./src/i18n', import.meta.url).pathname]
        }],
        ['vite-plugin-checker', {
          eslint: {
            lintCommand: 'eslint -c ./eslint.config.js "./src*/**/*.{js,mjs,cjs,vue}"',
            useFlatConfig: true
          }
        }, { server: false }]
      ]
    },

    devServer: {
      open: false,
      port: 9000,
      proxy: {
        '/api': {
          target: 'http://localhost:8000',
          changeOrigin: true
        }
      }
    },

    framework: {
      config: {
        dark: true
      },

      iconSet: 'material-icons',

      plugins: ['Notify', 'Loading', 'Dark']
    },

    animations: [],

    ssr: {
      pwa: false,
      prodPort: 3000,
      middlewares: ['render']
    }
  }
})
