import Vue from 'vue'
import App from './App.vue'
import router from './router'

import Buefy from 'buefy'
import 'buefy/dist/buefy.css'

import axios from 'axios'
Vue.use(Buefy)

Vue.prototype.$http = axios
Vue.prototype.$api = 'http://localhost:8888'

Vue.config.productionTip = false

new Vue({
  router,
  render: h => h(App)
}).$mount('#app')
