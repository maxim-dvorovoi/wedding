import Vue from 'vue'
import Router from 'vue-router'
import Home from './views/Home.vue'
import NotFound from './views/NotFound.vue'
import vOutsideEvents from 'vue-outside-events'

Vue.use(Router);
Vue.use(vOutsideEvents);

export default new Router({
	mode: 'history',
	base: process.env.BASE_URL,
	routes: [
		{
			path: '/',
			name: 'home',
			component: Home
		},
		{
			path: '*',
			name: 'notFound',
			component: NotFound
		}
	]
})
