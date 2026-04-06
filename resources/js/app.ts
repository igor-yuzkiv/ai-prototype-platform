import { createApp } from 'vue';
import App from './App.vue';
import primeVue from './plugins/primevue';
import 'primeicons/primeicons.css';

const app = createApp(App);

primeVue(app);

app.mount('#app');
