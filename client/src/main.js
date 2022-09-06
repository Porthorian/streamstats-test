import { createApp } from 'vue'
import App from './App.vue'
import vuetify from './plugins/vuetify'
import { loadFonts } from './plugins/webfontloader'
import Vue3EasyDataTable from 'vue3-easy-data-table';
import 'vue3-easy-data-table/dist/style.css';

loadFonts()

createApp(App)
  .use(vuetify)
  .component('EasyDataTable', Vue3EasyDataTable)
  .mount('#app')
