import Vue from 'vue';
import Vuex from 'vuex';
import App from './app.vue';

Vue.use(Vuex);

let store = require('./components/store').default;

Vue.config.productionTip = false

window.axios = require('axios').default;

new Vue({
  store,
  render: h => h(App)
}).$mount('#app')
