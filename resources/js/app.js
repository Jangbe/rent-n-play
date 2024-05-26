import './bootstrap';
import { createApp } from 'vue';
import { createPinia } from 'pinia';
import router from './plugins/router';
import DataTable from 'datatables.net-vue3';
import DataTablesCore from 'datatables.net-bs5';
import VueCountdown from '@chenfengyuan/vue-countdown';
import StarRating from 'vue-star-rating'
import App from './App.vue';

DataTable.use(DataTablesCore);

const pinia = createPinia();
const app = createApp(App);
app.use(router);
app.use(pinia);
app.component('DataTable', DataTable);
app.component(VueCountdown.name, VueCountdown);
app.component('StarRating', StarRating);
app.mount('#app');
