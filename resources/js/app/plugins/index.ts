import type { App } from 'vue'
import './md-editor-v3.plugin.ts'
import primeVuePlugin from './prime-vue.plugin'
import vueQueryPlugin from './vue-query.plugin'
import monacoEditorPlugin from './monaco-editor.plugin'

export function registerPlugins(app: App) {
    primeVuePlugin(app)
    vueQueryPlugin(app)
    monacoEditorPlugin(app)
}
