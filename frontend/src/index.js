import Vue from 'vue';
import Vuex from 'vuex';
import App from './app.vue';
import $ from 'jquery';
import 'bootstrap';
import 'popper.js';

Vue.use(Vuex);

let store = require('./components/store').default;

// Vue.config.productionTip = false
Vue.config.devtools = true;

new Vue({
  store,
  render: h => h(App)
}).$mount('#app')
