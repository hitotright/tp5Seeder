
<template>
    <div >
        <h1>Basic</h1>
        <h1>{{dashboard_1}}_{{dashboard_2}}</h1>
        <ul>
            <li><router-link to="/">/</router-link></li>
            <li><router-link to="/home">/home</router-link></li>
            <router-link tag="li" to="/home" :event="['mousedown', 'touchstart']">
                <a>/home</a>
            </router-link>
        </ul>
        <router-view class="view"></router-view>
    </div>
</template>

<script>
    export default {
        components: {
        },
        replace: false,
        mounted(){

        },
        data(){
            return {
                dashboard_1: '监控平台',
                dashboard_2: '控制台'
            }
        },
        methods: {}

    }

</script>