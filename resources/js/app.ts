import { createApp } from 'vue'
import App from '@/app/App.vue'
import { registerPlugins } from '@/app/plugins'
import router from '@/app/router'
import 'primeicons/primeicons.css'

const app = createApp(App)

registerPlugins(app)
app.use(router)

app.mount('#app')
