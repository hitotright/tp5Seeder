<template>
  <div id="app">
    <!-- 这里有一个区分路由， 区分登录页面和框架页面 -->
    <router-view></router-view>
  </div>
</template>

<script>
export default {
  created() {
    if (localStorage.getItem("org_user_name") === null) {
      this.$router.push("/login.html");
    }
  }
};
</script>

<style lang="scss">
@import "./assets/css/basic.css";
@import "./assets/css/normal.css";
@import "./assets/css/frame.css";
@import "./assets/css/class/currency.css";
@import "./assets/css/component/component.css";
</style>
