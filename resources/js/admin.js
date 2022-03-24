require('./bootstrap');

import Vue from 'vue'
import VueRouter from 'vue-router';
import routesAdmin from './routesAdmin';
import App from './app-client.vue';
import VModal from 'vue-js-modal';
// import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'

// Import Bootstrap an BootstrapVue CSS files (order is important)
// import 'bootstrap/dist/css/bootstrap.css'
// import 'bootstrap-vue/dist/bootstrap-vue.css'
import Select2 from 'v-select2-component';
import { library } from '@fortawesome/fontawesome-svg-core'
import { fas } from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import Toaster from 'v-toaster'

// You need a specific loader for CSS files like https://github.com/webpack/css-loader
import 'v-toaster/dist/v-toaster.css'
import CatCarousel from 'vue-cat-carousel'
import VueGlide from 'vue-glide-js'
import 'vue-glide-js/dist/vue-glide.css'
import 'vue-awesome/icons/flag'

// or import all icons if you don't care about bundle size
import 'vue-awesome/icons'

/* Register component with one of 2 methods */

import Icon from 'vue-awesome/components/Icon'
import VueScrollReveal from 'vue-scroll-reveal';
import Page404 from './vue/errors/404'

window.Vue = require('vue').default;
const router = new VueRouter(routesAdmin)
Vue.use(VueRouter);
Vue.use(CatCarousel)
Vue.use(require('vue-moment'));
Vue.use(VueGlide)
Vue.component('v-icon', Icon)
Vue.use(VueScrollReveal, {
    class: 'v-scroll-reveal', // A CSS class applied to elements with the v-scroll-reveal directive; useful for animation overrides.
    duration: 2000,
    scale: 1,
    distance: '40px',
    mobile: false
});
Vue.use(VModal, { dialog: true }, {
    dynamicDefaults: {
        draggable: true,
        resizable: true,
        height: 'auto'
    }
});
library.add(fas)


// optional set default imeout, the default is 10000 (10 seconds).
Vue.use(Toaster, { timeout: 5000 })
Vue.component('font-awesome-icon', FontAwesomeIcon)
Vue.component('select2', Select2);
Vue.component('pagination', require('laravel-vue-pagination'));

function loggedIn() {
    return localStorage.getItem('token')
}

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        if (loggedIn() == null) {
            next({
                path: '/login',
                query: { redirect: to.fullPath }
            })
        } else {
            next()
        }
    } else if (to.matched.some(record => record.meta.guest)) {
        if (loggedIn()) {
            next({

                components: Page404
            })
        } else {
            next()
        }
    } else {
        next()
    }
})

const app = new Vue({
    el: '#app',
    router: router,
    components: { App }
})