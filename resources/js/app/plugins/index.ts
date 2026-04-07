import type { App } from 'vue'
import './md-editor-v3.plugin.ts'
import primeVuePlugin from '@/app/plugins/prime-vue.plugin'
import vueQueryPlugin from '@/app/plugins/vue-query.plugin'
import monacoEditorPlugin from '@/app/plugins/monaco/monaco-editor.plugin'

export function registerPlugins(app: App) {
    primeVuePlugin(app)
    vueQueryPlugin(app)
    monacoEditorPlugin(app)
}
