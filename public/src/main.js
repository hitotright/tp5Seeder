/**
 * Created by hitoTright on 2017/6/8.
 */
/* 引入vue和主页 */
import Vue from 'vue';
import App from  './App.vue';
import VueRouter from 'vue-router';
import VueResource from 'vue-resource';


Vue.use(VueRouter);
Vue.use(VueResource);
//Vue.http.headers.common['X-XSRF-TOKEN']=$('meta[name="csrf-token"]').attr('content');
Vue.filter('time',function (value) {
    if(value !== 0){
        return new Date(parseInt(value) * 1000).toLocaleString();
    }
});


const router = new VueRouter({
    mode: 'history',
    base: '/',
    routes: [
        { path: '/', meta:'首页'},
        { path: '/home', component: require('./components/Home.vue'),alias: '/homes',meta:'HOME页'}
    ]
});

router.beforeEach((to, from, next) => {
    /* 路由发生变化修改页面title */
    if (to.meta) {
        document.title = to.meta;
    }
    next();
});


new Vue({
    el: '#app',
    router: router,
    render: h => h(App),
});
