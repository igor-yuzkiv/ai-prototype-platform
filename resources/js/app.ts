import { createApp } from 'vue';
import App from '@/App.vue';
import primeVuePlugin from '@/plugins/prime-vue.plugin';
import 'primeicons/primeicons.css';

const app = createApp(App);

primeVuePlugin(app);

app.mount('#app');
