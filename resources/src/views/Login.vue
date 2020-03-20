<template>
    <div class="login-wrap" id="home">
        <div class="Loading__bar___21yOt"
             style="background: rgb(255,255,255); width: 0%;height:2px;transition: width .35s;position: fixed;top:0">
        </div>
        <div class="box">
            <div class="rightbox">
                <div style="margin-top: 20px; height: 200px;">
                    <!--<el-collapse-transition>-->
                    <div v-show="show_login==false" class="ervma">
                        <img src="../assets/img/zyewm.png"
                             style="width: 100px; height: 100px;transform: translate(95px,130px)">
                        <p style="text-align: center; color: rgba(0,0,0,0.35);transform:translate(2px,145px)">
                            扫描二维码下载APP
                        </p>
                    </div>
                    <!--</el-collapse-transition>-->
                </div>
                <div style="margin-top: 20px; height: 200px;">
                    <!--<el-collapse-transition>-->
                    <div v-show="show_login==true" class="left" style="    position: absolute;
    top: 0;
    left: 30px;">
                        <div class="logo_box">
                            <p>***-管理端</p>
                        </div>
                        <el-form :model="ruleForm" :rules="rules" ref="ruleForm" label-width="0px"
                                 class="demo-ruleForm">
                            <el-form-item prop="username">
                                <el-input clearable class="input_name" v-model="ruleForm.username"
                                          placeholder="请输入用户名/手机号码">
                                    <i slot="prefix" class="el-input__icon el-icon-user-solid"></i>
                                </el-input>
                            </el-form-item>
                            <el-form-item prop="password">
                                <el-input clearable @keyup.enter="submitForm('ruleForm')" class="input_pass"
                                          type="password" placeholder="请输入密码" v-model="ruleForm.password"
                                          @keyup.enter.native="submitForm('ruleForm')">
                                    <i slot="prefix" class="el-input__icon el-icon-pass"></i>
                                </el-input>
                            </el-form-item>

                            <div class="submit">
                                <el-button type="primary" @click="submitForm('ruleForm')">登录</el-button>
                            </div>
                        </el-form>
                    </div>
                    <!--</el-collapse-transition>-->
                </div>
            </div>
            <div class="footer" style="text-align: center;width: 436px;line-height: 20px;font-size: 12px">
                Copyright © 2020 All Rights Reserved.<br/>
            </div>
        </div>
    </div>
</template>
<style>
</style>
<script>
    import "../assets/css/login.css";
    import {mapMutations} from 'vuex'

    export default {
        data: function () {
            return {
                b_login_ico: '',
                b_name: "***-管理端",
                b_logo: '',
                ruleForm: {
                    username: "",
                    password: "",
                },
                rules: {
                    username: [
                        {
                            required: true,
                            message: "请输入用户名",
                            trigger: "blur"
                        },
                        {
                            min: 3,
                            message: "用户名必须大于3位",
                            trigger: "blur,change"
                        }
                    ],
                    password: [
                        {required: true, message: "请输入密码", trigger: "blur"},
                        {
                            min: 6,
                            message: "密码必须大于6位",
                            trigger: "blur,change"
                        }
                    ]
                },
                show_login: true
            };
        },
        mounted() {
            // this.getBus();
            let ruleForm_name = localStorage.getItem("ruleForm_name")
            let ruleForm_pass = localStorage.getItem("ruleForm_pass")
            if (ruleForm_pass !== '') {
                this.ruleForm.username = ruleForm_name
                this.ruleForm.password = ruleForm_pass
            }
        },
        beforeMount(){
        },
        methods: {
            ...mapMutations('navTabs', [
                'closeAllTab'
            ]),
            getBus() {
            },
            submitForm(formName) {
                const self = this;
                self.$refs[formName].validate(valid => {
                    if (valid) {
                        self.$ajax
                            .post("/admin/Login/login", self.ruleForm)
                            .then(function (res) {
                                if (res.data.error === 0) {
                                    localStorage.setItem("org_user_name", res.data.data.user_name);
                                     localStorage.setItem("org_ps_name", res.data.data.ps_name);
                                     localStorage.setItem("is_Super", false);
                                     localStorage.setItem('ruleForm_name', self.ruleForm.username)
                                     localStorage.setItem('ruleForm_pass', self.ruleForm.password)
                                    self.closeAllTab();
                                    self.$router.push("/index.html");
                                    // 记住密码
                                } else {
                                    self.$router.push("/index.html");

                                    self.$message.error(res.data.message);
                                }
                            })
                            .catch(function (err) {
                                self.$router.push("/index.html");

                                self.$message("网络错误！请联系管理员");
                            });
                    } else {
                        return false;
                    }
                });
            }
        }
    };
</script>
