import Vue from 'vue'
import App from './App.vue'
import vuetify from './plugins/vuetify'
import axios from 'axios'
import store from './store'

axios.defaults.withCredentials = true
let baseurl = 'http://localhost'
if (process.env.NODE_ENV == "production") {
  baseurl = 'https://statsapi.kriekon.com'
}
axios.defaults.baseURL = baseurl
Vue.config.productionTip = false

new Vue({
  vuetify,
  store,
  render: h => h(App)
}).$mount('#app')
