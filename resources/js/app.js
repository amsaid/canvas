import { store } from './store';
import Toasted from 'vue-toasted';
import Vue from 'vue';
import moment from 'moment';
import request from './mixins/request';
import router from './router';
import App from './App.vue';

import 'bootstrap';
import Popper from 'popper.js';

window.Popper = Popper;

Vue.prototype.moment = moment;

Vue.use(Toasted, {
  position: 'bottom-right',
  theme: 'bubble',
  duration: 2500,
});

Vue.mixin(request);

Vue.config.productionTip = false;

new Vue({
  el: '#canvas',
  router,
  store,
  render: (h) => h(App),
});
