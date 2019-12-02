/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

//require('./bootstrap');
import Vue from 'vue';
import Vuelidate from 'vuelidate';
//import route from './route';
//import lang from './lang';
import VueRouter from 'vue-router';
//import VueResource from 'vue-resource';
import axios from 'axios';
import VueAxios from 'vue-axios';
import router from './router';
import App from './components/App';

Vue.use(Vuelidate);
Vue.use(VueRouter);
Vue.use(VueAxios, axios);

axios.defaults.baseURL = 'http://127.0.0.1:8000/api';

Vue.router = router

Vue.use(require('@websanova/vue-auth'), {
    auth: require('@websanova/vue-auth/drivers/auth/bearer.js'),
    http: require('@websanova/vue-auth/drivers/http/axios.1.x.js'),
    router: require('@websanova/vue-auth/drivers/router/vue-router.2.x.js'),
});

App.router = Vue.router
new Vue(App).$mount('#app');
