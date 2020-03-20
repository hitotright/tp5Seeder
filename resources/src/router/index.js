import Vue from 'vue'
import Router from 'vue-router'
import Login from './../views/Login.vue'
import NullFind from './../views/404.vue'
import Index from './../views/admin/home/Index.vue'

Vue.use(Router)

export default new Router({
    mode: 'history',
    routes: [
        {
            path: '/',
            component: Login
        },
        {
            path: '/login.html',
            name: 'login.html',
            component: Login
        },
        {
            path: '/404.html',
            name: '404.html',
            component: NullFind
        },
        {
            path: '/index.html',
            name: 'index.html',
            component: Index
        },
        {
            path: '*',
            redirect: {path: '/404.html'}
        }
    ]
})