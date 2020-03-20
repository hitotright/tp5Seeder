<template>
    <div>

        <div class="header">
            <div class="Loading__bar___21yOt"
                 style="background: rgb(33, 186, 69); width: 0%;height:2px;transition: width .35s;position: fixed;top:0">
            </div>
            <el-row>
                <el-col :span="12">
                    <div class="logo">
                        <!--<img :src="this.b_home_ico">-->
                        <p style="margin-left: 15px">***-管理端</p>
                    </div>
                </el-col>

                <el-col :offset="2" :span="10" class="text_right">
                    <el-row>
                        <el-col :span="17">
                            <div class="backSuper school-info first_school">
                                <img src="../../../assets/img/user.png">
                                <span>{{ps_name}}</span>
                                <span style="margin-left: 10px">{{username}}</span>
                                <el-button
                                        style="margin-left: 10px"
                                        type="primary"
                                        plain
                                        v-show="is_Super"
                                        size="mini"
                                        @click="backSuper"
                                        class="backSuper col_df"

                                >返回原账号
                                </el-button>

                            </div>
                        </el-col>
                        <el-col :span="7">
                            <div class="user-info" style="margin-left: 10px">
                                <b class="fgx">丨</b>
                                <div
                                        class="el-dropdown-link"
                                        @click="handleCommand"
                                >
                                    <div class="navbar-right"></div>
                                    <span class="tc">退出</span>
                                </div>
                            </div>
                        </el-col>
                    </el-row>
                </el-col>

            </el-row>


        </div>

    </div>

</template>

<script>
    import {mapMutations} from "vuex";

    export default {
        data() {
            return {
                b_home_ico:'',
                initUsername: "游客登录",
                home_logo: ''
            };
        },
        computed: {
            username() {
                let myUsername = localStorage.getItem("org_user_name"); // 对象  没有是为null
                return myUsername !== null ? myUsername : this.initUsername;
            },
            ps_name() {
                let myUsername = localStorage.getItem("org_ps_name"); // 对象  没有是为null
                return myUsername !== null ? myUsername : '';
            },
            is_Super() {
                let isSuper = localStorage.getItem("is_Super"); // 对象  没有是为null
                if (isSuper !== null && isSuper === "true") {
                    return true;
                } else {
                    return false;
                }
            }
        },
        mounted() {
        },
        methods: {
            backSuper() {
                let that = this;
                this.$ajax.get("admin/User/super_login").then(function (res) {
                    if (res.data.error === 1) {
                        that.$message.error(res.data.msg);
                    } else {
                        localStorage.setItem("org_user_name", res.data.msg.user_name);
                        localStorage.setItem("org_ps_name", res.data.msg.ps_name);
                        localStorage.setItem("is_Super", false);
                        that.$router.go(0);
                    }
                });
            },
            handleCommand() {
                localStorage.removeItem("org_user_name");
                this.$ajax.get("/admin/Login/logout");
                this.$router.push("/");
            },
            ...mapMutations("navTabs", [
                // 下拉框选择所有已打开的标签
                "freshTabFunc"
            ]),
            ...mapMutations("navTabs", [
                // 下拉框选择所有已打开的标签
                "closeAllTabFunc"
            ])
        }
    };
</script>
<style>
    .navbar-right {
        width: 20px;
        height: 20px;
        margin: 4px 0 0 8px;
        vertical-align: middle;
        background: url(../../../assets/img/tc.png) no-repeat center center;
    }
</style>
