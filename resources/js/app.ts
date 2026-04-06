import { createApp } from 'vue';
import App from '@/App.vue';
import {registerPlugins} from '@/app/plugins';
import 'primeicons/primeicons.css';

const app = createApp(App);

registerPlugins(app);

app.mount('#app');
