import type { App } from 'vue'
import primeVuePlugin from '@/app/plugins/prime-vue.plugin'
import vueQueryPlugin from '@/app/plugins/vue-query.plugin'

export function registerPlugins(app: App) {
    primeVuePlugin(app)
    vueQueryPlugin(app)
}
