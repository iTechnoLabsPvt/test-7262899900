import { createApp } from 'vue';
import 'bootstrap/dist/css/bootstrap.min.css';
import router from './router';
import App from './components/App.vue';

const app = createApp(App);

app.use(router);
app.mount('#app');
