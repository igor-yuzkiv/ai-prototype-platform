import { install as VueMonacoEditorPlugin } from '@guolao/vue-monaco-editor'
import type { App } from 'vue'

export default function (app: App) {
    app.use(VueMonacoEditorPlugin)
}
