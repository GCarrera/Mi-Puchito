/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
//require('./asset/popper.min.js');
//require('./asset/toastr.min.js');
//require('./asset/slick.min.js');
//require('./asset/bootstrap.bundle.min.js');
//require('./asset/bootstrap-select.min.js');
//require('./asset/bootstrap-autocomplete.min.js');
//require('./asset/datatables.min.js');

//require('./asset/scripts.js');
//require('./asset/moment.min.js');
//require('./asset/daterangepicker.min.js');

//require('./asset/jquery.lazy.min.js');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('despachos-almacen', require('./view/Despachos-almacen.vue').default);
Vue.component('piso-ventas', require('./view/PisoVentas.vue').default);
Vue.component('piso-ventas-ventas', require('./view/PisoVentasVentas.vue').default);
Vue.component('piso-ventas-cajas', require('./view/PisoVentasCajas.vue').default);
Vue.component('piso-ventas-inventario', require('./view/PisoVentasInventario.vue').default);
Vue.component('piso-ventas-despachos', require('./view/PisoVentasDespachos.vue').default);
Vue.component('piso-ventas-retiros', require('./view/PisoVentasRetiros.vue').default);
Vue.component('new-despacho', require('./view/NewDespacho.vue').default);
Vue.component('terminar-despacho', require('./view/TerminarDespacho.vue').default);
Vue.component('notify-sales', require('./components/NotifySales.vue').default);
Vue.component('notify-nav', require('./components/NotifyNav.vue').default);
Vue.component('comp-inventario', require('./components/Inventario.vue').default);
Vue.component('comp-bitacora', require('./components/Bitacora.vue').default);
Vue.component('view-inventario', require('./view/Inventario.vue').default);

import Vue from 'vue'
import {ModalPlugin, PaginationPlugin, AlertPlugin, BootstrapVue, IconsPlugin } from 'bootstrap-vue'

Vue.use(ModalPlugin)
Vue.use(PaginationPlugin)
Vue.use(AlertPlugin)
//import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

// Install BootstrapVue
Vue.use(BootstrapVue)
// Optionally install the BootstrapVue icon components plugin
Vue.use(IconsPlugin)

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    data: {
      sales: []
    }
});
