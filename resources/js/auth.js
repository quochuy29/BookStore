import bearer from '@websanova/vue-auth/src/drivers/auth/bearer';
import axios from '@websanova/vue-auth/src/drivers/http/axios.1.x';
import router from '@websanova/vue-auth/src/drivers/router/vue-router.2.x';
const config = {
    auth: bearer,
    http: axios,
    router: router,
    tokenDefaultName: 'laravel-jwt-auth',
    tokenStore: ['localStorage'],
    rolesVar: 'role', //User model field which contains the user role details.

    // API endpoints used in Vue Auth.
    registerData: {
        url: '/login',
        method: 'POST',
        redirect: '/login'
    },
    loginData: {
        url: 'auth/login',
        method: 'POST',
        redirect: '',
        fetchUser: true
    },
    logoutData: {
        url: 'auth/logout',
        method: 'POST',
        redirect: '/',
        makeRequest: true
    },
    fetchData: {
        url: 'auth/user',
        method: 'GET',
        enabled: true
    },
    refreshData: {
        url: 'auth/refresh',
        method: 'GET',
        enabled: true,
        interval: 30
    }
}
export default config