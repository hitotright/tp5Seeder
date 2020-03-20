// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App.vue'
import router from './router'
import ElementUI from 'element-ui'
import './assets/css/element-variables.scss'

import axios from 'axios'
import echarts from 'echarts'
import 'echarts/map/js/china.js'
import Wrap from './components/base/Wrap'
import utils from './utils'
import store from './store'
import VueCropper from 'vue-cropper'


Vue.use(VueCropper)
Vue.use(ElementUI, { size: 'small', zIndex: 3000 })
Vue.config.productionTip = false
axios.defaults.baseURL = '/index.php'
// Add a request interceptor
axios.interceptors.request.use(function (config) {
    // Do something before request is sent
    let docu = document.querySelectorAll('.Loading__bar___21yOt')
    docu[0].style.transition = 'width .35s';

    docu[0].style.width = '10%';
    return config
}, function (error) {
    // Do something with request error
    return Promise.reject(error)
})

// Add a response interceptor
axios.interceptors.response.use(function (response) {
    // Do something with response data
    if (response.status === 200) {
        let docu = document.querySelectorAll('.Loading__bar___21yOt')
        docu[0].style.width = '100%';
        setTimeout(() => {
            docu[0].style.transition = 'width 0s';
            docu[0].style.width = '0%';
        }, 1000);
    }
    if (response.status === 203) {
        store.commit('pagePassValue/func_setRelogin', true)
        if (document.getElementsByClassName('el-notification').length === 0) {
            Vue.prototype.$notify({ title: '警告', message: '登录超时', position: 'top-left', type: 'warning' })
            if (document.getElementsByClassName('el-notification').length === 1) {
                router.push('/')
            }
        }
    }
    return response
}, function (error) {
    switch (error.response.status) {
        case 500:
            if (document.getElementsByClassName('el-notification').length === 0) {
                Vue.prototype.$notify.error({ title: '错误', message: '网络错误!', position: 'top-left' })
            }
            break;
        case 401:
            if (document.getElementsByClassName('el-notification').length === 0) {
                Vue.prototype.$notify.error({ title: '错误', message: '你没有权限!', position: 'top-left' })
            }
    }
    return Promise.reject(error)
})

Vue.prototype.$utils = utils
Vue.prototype.$ajax = axios
Vue.prototype.$echarts = echarts

Vue.prototype.$bus = new Vue() // 空的实例放到根组件下，所有的子组件都能调用
Vue.prototype.$apiUrl = "/"

router.beforeEach((to, from, next) => {
    /* 路由发生变化修改页面title */
    // if (to.meta.title) {
    //     document.title = to.meta.title;
    // }

    let targetProtocol = "https:";
    // if (window.location.protocol !== targetProtocol){
    //     window.location.href = targetProtocol +
    //         window.location.href.substring(window.location.protocol.length);
    // }

    next();
});

Vue.prototype.$upload_prefix = "/"

export default new Vue({
    el: '#body',
    router,
    store,
    template: '<App />',
    components: { App, ...Wrap },
    data: {
        Bus: new Vue() // 空的实例放到根组件下，所有的子组件都能调用
    }
})
