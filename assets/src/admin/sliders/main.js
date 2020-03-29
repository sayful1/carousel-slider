import Vue from 'vue';
import App from './App.vue'
import router from './routers.js';
import store from './store.js';
import menuFix from "./utils/admin-menu-fix.js";
import {Dialog} from 'shapla-confirm-dialog';

Vue.use(Dialog);

jQuery.ajaxSetup({
	beforeSend: function (xhr) {
		xhr.setRequestHeader('X-WP-Nonce', window.carouselSliderSettings.nonce);
	}
});

let el = document.querySelector('#carousel-slider-admin');
if (el) {
	new Vue({el: el, router: router, store: store, render: h => h(App)});
}

// fix the admin menu for the slug "carousel-slider"
menuFix('carousel-slider');
