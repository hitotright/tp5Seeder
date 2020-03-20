<template>
  <div class="sidebar">
    <el-menu :collapse="false" :default-active="activeTabId" class="el-menu-vertical-demo" unique-opened>
      <template v-for="item in items">
        <template v-if="item.subs">
          <el-submenu :index="item.tab_id">
            <template slot="title">
              <i :class="item.icon" :style="{color: item.icon_color}" class="el-st"></i>
              <span>{{ item.name }}</span>
            </template>
            <el-menu-item v-for="(subItem, i) in item.subs"  :key="i" :index="subItem.tab_id" @click="addTab({id:subItem.tab_id, name:subItem.name,url:subItem.url})">
              {{ subItem.name }}
            </el-menu-item>
          </el-submenu>
        </template>
        <template v-else>
          <el-menu-item :index="item.tab_id" @click="addTab({id:item.tab_id, name:item.name,url:item.url})">
            <i :class="item.icon" :style="{color: item.icon_color}"></i>{{ item.name }}
          </el-menu-item>
        </template>
      </template>
    </el-menu>

    <el-dialog title="重新登录" :visible.sync="relogin" width="30%" size="mini" center>
      <el-form size="small" :model="ruleForm" :rules="rules" ref="ruleForm">
        <el-form-item prop="username" label="用户名：" label-width="100px">
          <el-input clearable v-model="ruleForm.username" class="dialog-input"></el-input>
        </el-form-item>
        <el-form-item prop="password" label="密码：" label-width="100px">
          <el-input clearable type="password" v-model="ruleForm.password" @keyup.enter.native="submitForm('ruleForm')" class="dialog-input"></el-input>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="relogin = false">取 消</el-button>
        <el-button type="primary" @click="submitForm('ruleForm')">确 定</el-button>
      </div>
    </el-dialog>

  </div>
</template>

<script>
import { mapMutations, mapGetters } from "vuex";

export default {
    data() {
        return {
            activeIndex: "home",
            items: [],
            ruleForm: {
                username: "",
                password: ""
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
                    { required: true, message: "请输入密码", trigger: "blur" },
                    {
                        min: 6,
                        message: "密码必须大于6位",
                        trigger: "blur,change"
                    }
                ]
            }
        };
    },
    computed: {
        relogin: {
            get() {
                return this.$store.state.pagePassValue.relogin;
            },
            set(value) {
                this.$store.commit("pagePassValue/setRelogin", value);
            }
        },
        ...mapGetters("navTabs", ["activeTabId"])
    },
    methods: {
        ...mapMutations("navTabs", ["addTab", "setActiveTabId"]),
        submitForm(formName) {
            const self = this;
            self.$refs[formName].validate(valid => {
                if (valid) {
                    self.$ajax
                        .post("/admin/Login/login", self.ruleForm)
                        .then(function(res) {
                            if (res.data.error === 0) {
                                localStorage.setItem(
                                    "org_user_name",
                                    res.data.data
                                );
                                location.replace("/index.html");
                                self.relogin = false;
                            } else {
                                self.$message.error(res.data.message);
                            }
                        })
                        .catch(function(err) {
                            self.$message("网络错误！请联系管理员");
                        });
                } else {
                    return false;
                }
            });
        },
        initMenu() {
            let menus = [
                {
                    tab_id: "home",
                    name: "工作台",
                    icon: "el-icon-menu",
                    icon_color: "#aeb9c2",
                    url: "Home"
                }
            ];
            this.$ajax
                .get("/admin/Home/menu")
                .then(function(res) {
                    if (res.data.length > 0) {
                        res.data.forEach((item, index) => {
                            menus.push(item);
                        });
                    }
                })
                .catch(function(err) {});
            return menus;
        }
    },
    created() {
        this.items = this.initMenu();
    }
};
</script>