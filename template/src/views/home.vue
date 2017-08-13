<style>
    .layout{
        border: 1px solid #d7dde4;
        background: #f5f7f9;
        position: absolute;
        border-radius: 4px;
        overflow: hidden;
        width: 100%;
        height: 100%;
        min-height: 100%;
    }
    .layout-logo{
        width: 30px;
        height: 30px;
        float: left;
        margin-left: 30px;
    }
    .layout-copy{
        position: absolute;
        height: 50px;
        width: 100%;
        bottom: 0;
        text-align: center;
        padding: 10px 0 20px;
        color: #9ea7b4;
    }
    .layout-header{
        background: #464c5b;
        padding: 10px 0;
        overflow: hidden;
    }
    .layout-body{
        position: absolute;
        height: 100%;
        width: 100%;
    }
    .layout-sidebar{
        position: absolute;
        top: 0;
        left: 0;
        padding-top: 50px;
        min-height: 100%;
        width: 230px;
        z-index: 810;
    }
    .layout-theme{

    }
    .layout-wrapper{
        position: absolute;
        width: 100%;
        height:100%;
        padding-left: 230px;
    }
    .layout-wrapper-header{
        position: absolute;
        margin: 10px auto 5px 10px;
    }
    .layout-wrapper-body{
        position: absolute;
        float: left;
        margin-top: 36px;
        margin-right: 10px;
        height: 100%;
        /* width: 100%; */
        padding: 5px 10px 5px 10px;
    }
    .layout-ceiling-main{
        text-align: center;
        float: right;
        margin-right: 15px;
        padding-top: 7px;
        height: 30px;
    }
    .layout-ceiling-main a{
        color: #9ba7b5;
    }
</style>
<template>
    <div class="layout">
        <div class="layout-header">
            <img class="layout-logo" src="https://raw.githubusercontent.com/iview/iview/master/assets/logo.png">
            <div class="layout-ceiling-main">
                <a href="#">注册登录</a>
                <a href="#">帮助中心</a>
                <a href="#">安全中心</a>
                <a href="#">服务大厅</a>
            </div>
        </div>
        <div class="layout-body">
                <div class="layout-sidebar">
                    <Menu :theme="menu_theme" :width="menu_width" >
                        <router-link to="/"><Menu-item name="1-1">Go Back</Menu-item></router-link>
                        <router-link to="/home/user"><Menu-item name="1-1">用户列表</Menu-item></router-link>
                        <Submenu name="1">
                            <template slot="title">
                                <Icon type="ios-paper"></Icon>
                                内容管理
                            </template>
                            <Menu-item name="1-1">文章管理</Menu-item>
                            <Menu-item name="1-2">评论管理</Menu-item>
                            <Menu-item name="1-3">举报管理</Menu-item>
                        </Submenu>
                        <Submenu name="2">
                            <template slot="title">
                                <Icon type="ios-people"></Icon>
                                用户管理
                            </template>
                            <Menu-item name="2-1">新增用户</Menu-item>
                            <Menu-item name="2-2">活跃用户</Menu-item>
                        </Submenu>
                        <Submenu name="3">
                            <template slot="title">
                                <Icon type="stats-bars"></Icon>
                                统计分析
                            </template>
                            <Menu-group title="使用">
                                <Menu-item name="3-1">新增和启动</Menu-item>
                                <Menu-item name="3-2">活跃分析</Menu-item>
                                <Menu-item name="3-3">时段分析</Menu-item>
                            </Menu-group>
                            <Menu-group title="留存">
                                <Menu-item name="3-4">用户留存</Menu-item>
                                <Menu-item name="3-5">流失用户</Menu-item>
                            </Menu-group>
                        </Submenu>
                    </Menu>
                   <div class="layout-theme">
                       <p>切换主题</p>
                       <Radio-group v-model="menu_theme">
                           <Radio label="light"></Radio>
                           <Radio label="dark"></Radio>
                       </Radio-group>
                   </div>
                </div>
                <div class="layout-wrapper">
                    <div class="layout-wrapper-header">
                        <Breadcrumb>
                            <Breadcrumb-item href="/">Home</Breadcrumb-item>
                            <Breadcrumb-item href="/components/breadcrumb">Components</Breadcrumb-item>
                            <Breadcrumb-item>Breadcrumb</Breadcrumb-item>
                        </Breadcrumb>
                    </div>
                    <div class="layout-wrapper-body">
                        <router-view></router-view>
                    </div>
                </div>
        </div>
        <div class="layout-copy">
            2011-2016 &copy; TalkingData
        </div>
    </div>
</template>
<script>
    export default {
        data () {
            return {
                menu_theme: 'light',
                menu_width: 'auto'
            }
        }
    }
</script>
