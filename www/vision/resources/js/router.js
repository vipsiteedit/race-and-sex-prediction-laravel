import VueRouter from 'vue-router';
import Page404 from './components/Page404.vue';
import FaceAdd from './components/FaceAdd';
import Home from './components/Home.vue';
import Dashboard from './components/Dashboard.vue';
import Register from './components/Register.vue';
import Login from './components/Login.vue';

export default new VueRouter({
    routes: [
        {
            path: '/register',
            name: 'register',
            component: Register
        },{
            path: '/login',
            name: 'login',
            component: Login
        },{
            path: '/face/new',
            name: 'add',
            component: FaceAdd,
            meta: {
                auth: true
            }
        },{
            path: '/face/:id',
            name: 'show',
            component: FaceAdd,
            meta: {
                auth: true
            }
        },{
            path: '/',
            name: 'dashboard',
            component: Dashboard,
            meta: {
                auth: true
            }
        },{
            path: '*',
            name: '404',
            component: Page404
        }
    ],
    //mode: 'history'
});
