require('./bootstrap');

// Vue
import Vue from 'vue';

// Vue router and axios
import VueRouter from 'vue-router';
import axios from 'axios';
import VueAxios from 'vue-axios';
Vue.use(VueRouter, VueAxios, axios);

// laravel Vue pagination
Vue.component('pagination', require('laravel-vue-pagination'));

// Sweetalert 2
import Swal from 'sweetalert2';
window.Swal = Swal;

// Date picker
import VueFlatPickr from 'vue-flatpickr-component';
import 'flatpickr/dist/flatpickr.css';
Vue.use(VueFlatPickr);
require("flatpickr/dist/themes/airbnb.css");

// CK Editor
import CKEditor from '@ckeditor/ckeditor5-vue';
Vue.use(CKEditor);

// Moment.js
window.moment = require('moment');

// Lazy image
import { VLazyImagePlugin } from "v-lazy-image";
Vue.use(VLazyImagePlugin);

// Custom directives
Vue.directive('tooltip', function(el, binding){
    $(el).tooltip({
        title: binding.value,
        placement: binding.arg,
        trigger: 'hover'
    })
});

// Toggle button
import ToggleButton from 'vue-js-toggle-button';
Vue.use(ToggleButton);

/***** Components *****/
import App from './App.vue';
import Index from "./components/Index";
import BooksIndex from "./components/books/BooksIndex";
import BooksShow from "./components/books/BooksShow";
import BooksMyIndex from "./components/books/BooksMyIndex";
import MyProfileIndex from "./components/profile/MyProfileIndex";

// Routes
const routes = [
    // Website routes
    { path: '/', name: 'Index', component: Index},
    { path: '/books', name: 'BooksIndex', component: BooksIndex},
    { path: '/books/:id', name: 'BooksShow', component: BooksShow},
    { path: '/my-books', name: 'BooksMyIndex', component: BooksMyIndex},
    { path: '/profile', name: 'MyProfileIndex', component: MyProfileIndex},
];

// Router
const router = new VueRouter({
    routes,
    mode: 'history'
});

const app = new Vue({
    el: '#app',
    router,
    render(h) { return h(App) }
});
