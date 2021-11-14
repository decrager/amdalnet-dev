import '@/styles/index.scss';
import 'core-js';
import Vue from 'vue';
import Cookies from 'js-cookie';
import ElementUI from 'element-ui';
import App from './views/App';
import store from './store';
import router from '@/router';
import i18n from './lang'; // Internationalization
import '@/icons'; // icon
import '@/permission'; // permission control
import { LMap, LTileLayer, LMarker } from 'vue2-leaflet';
import 'leaflet/dist/leaflet.css';

import { Icon } from 'leaflet';
import 'leaflet/dist/leaflet.css';
// eslint-disable-next-line
delete Icon.Default.prototype._getIconUrl;
// eslint-disable-next-line
Icon.Default.mergeOptions({
  iconRetinaUrl: require('leaflet/dist/images/marker-icon-2x.png'),
  iconUrl: require('leaflet/dist/images/marker-icon.png'),
  shadowUrl: require('leaflet/dist/images/marker-shadow.png'),
});

Vue.component('l-map', LMap);
Vue.component('l-tile-layer', LTileLayer);
Vue.component('l-marker', LMarker);

import * as filters from './filters'; // global filters

Vue.use(ElementUI, {
  size: Cookies.get('size') || 'medium', // set element-ui default size
  i18n: (key, value) => i18n.t(key, value),
});

// register global utility filters.
Object.keys(filters).forEach(key => {
  Vue.filter(key, filters[key]);
});

Vue.config.productionTip = false;

Object.defineProperty(Vue.prototype, '$bus', {
  get() {
    return this.$root.bus;
  },
});

var bus = new Vue({});

new Vue({
  el: '#app',
  router,
  store,
  i18n,
  data: {
    bus: bus,
  },
  render: h => h(App),
});

