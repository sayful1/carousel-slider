import Vue from 'vue';
import VueRouter from 'vue-router';
import Home from './views/Home.vue';
import Documentation from './views/Documentation.vue';
import Slider from './views/Slider.vue';

Vue.use(VueRouter);

const routes = [
	{path: '/', name: 'Home', component: Home},
	{path: '/help', name: 'Documentation', component: Documentation},
	{path: '/:id', name: 'Slider', component: Slider},
];

export default new VueRouter({
	routes // short for `routes: routes`
});
