<template>
    <page-wrap>
        <!-- 搜索栏 -->
        <search-wrap>
            <el-form :model="search" label-width="40px" size="mini" onsubmit="return false" :inline="true">
                    <el-form-item label="姓名:">
                        <el-input clearable v-model="search.user_name" placeholder="请输入"
                                  @keyup.enter.native="onSubmit()"></el-input>
                    </el-form-item>
                    <el-form-item label="部门:">
                        <el-select v-model="search.dp_id" class="width_sp111" filterable clearable collapse-tags
                                   placeholder="请选择">
                            <el-option
                                    v-html="item.label"
                                    v-for="item in options_dp"
                                    :key="item.value"
                                    :value="item.value"
                                    :label="item.label_raw">
                            </el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="职位:">
                        <el-select v-model="search.ps_id" class="width_sp111" filterable placeholder="请选择" clearable>
                            <el-option
                                    v-for="item in options_ps"
                                    :key="item.value"
                                    :label="item.label"
                                    :value="item.value"
                            >
                            </el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="电话:">
                        <el-input clearable v-model="search.phone" placeholder="请输入"
                                  @keyup.enter.native="onSubmit()"></el-input>
                    </el-form-item>
                    <el-button type="primary" size="mini" @click="onSubmit()" icon="el-icon-search"
                               title="查询员工">查询
                    </el-button>
                    <el-button type="danger" size="mini" @click="onClear()" icon="el-icon-refresh"
                               title="重置查询员工">重置
                    </el-button>
                    <el-button type="success" size="mini" @click="handleAdd = true" icon="el-icon-plus"
                               title="添加员工">添加
                    </el-button>
            </el-form>
        </search-wrap>
        <!-- 表格 -->
        <table-wrap>
            <el-table v-loading="loading" :data="tableData" size="mini" :height='tabTableHeight'
                      @sort-change="tableSortChange">
                <el-table-column prop="user_id" sortable label="编号" min-width='100'></el-table-column>
                <el-table-column prop="user_name" label="姓名" min-width='100'></el-table-column>
                <el-table-column prop="login_name" label="登录名" min-width='100'></el-table-column>
                <el-table-column prop="ps_name" label="职位" min-width='120'></el-table-column>
                <el-table-column prop="dp_name" label="部门" min-width='120'></el-table-column>
                <el-table-column prop="phone" label="电话" min-width='120'></el-table-column>
                <el-table-column prop="email" label="邮箱" min-width='140'></el-table-column>
                <el-table-column label="添加时间" min-width='120'>
                <template slot-scope="scope">
                <span v-if="scope.row.add_time.length>=10">{{scope.row.add_time.slice(0,10)}}</span>
                </template>
                </el-table-column>
                <el-table-column label="最后登录时间" min-width='140'>
                    <template slot-scope="scope">
                        <span v-if="scope.row.last_login==='0000-00-00 00:00:00'"></span>
                        <span v-else-if="scope.row.last_login.length>=10">{{scope.row.last_login.slice(0, 10)}}</span>
                    </template>
                </el-table-column>
                <el-table-column label="操作" min-width="270px" fixed="right">
                    <template slot-scope="scope">
                        <el-button-group>
                            <el-button @click="onSuperLogin(scope.row)" type="text" size="small" title="模拟登录"
                            >模拟登录
                            </el-button>
                            <el-button @click="onChangePassword(scope.row)" type="text" size="small" title="修改密码"
                            >修改密码
                            </el-button>
                            <el-button @click="onEdit(scope.row)" type="text" size="small"
                                       title="编辑员工">编辑员工
                            </el-button>
                            <el-button @click="onDelete(scope.row)" type="text" size="small"
                                       style="color:red"
                                       title="删除员工">删除
                            </el-button>
                        </el-button-group>
                    </template>
                </el-table-column>
            </el-table>
        </table-wrap>

        <el-dialog width="400px" title="修改密码" :modal="true" @close="editPasswordShow=false" :visible="editPasswordShow"
                   class="close1">
            <el-form label-width="120px" size="mini">
                <el-form-item label="新密码:">
                    <el-input clearable v-model="editPasswordForm.newPassword1"></el-input>
                </el-form-item>
                <el-form-item label="再次输入新密码:">
                    <el-input clearable v-model="editPasswordForm.newPassword2"></el-input>
                </el-form-item>
            </el-form>
            <div slot="footer">
                <el-button @click="editPasswordShow=false">取 消</el-button>
                <el-button type="primary" @click="handleEditPassword()">确 定</el-button>
            </div>
        </el-dialog>
        <!-- 分页 -->
        <pagination :data="pagination" @submit="onSubmit"/>
        <add v-if="handleAdd" @callback="callbackAdd"/>
        <edit v-if="handleEdit" :list="rowIndex" @callback="callbackEdit"/>
    </page-wrap>
</template>

<script>
    import Add from "./Add.vue";
    import Edit from "./Edit.vue";
    import {mapState, mapGetters} from "vuex";

    export default {
        components: {
            Add,
            Edit,
        },
        data() {
            return {
                search: {user_name: '', phone: '', ps_id: '', dp_id: ''}, // 搜索词
                pagination: {currentPage: 1, pageSize: 50, total: 0}, // 分页相关数据
                loading: false,
                tableData: [],
                handleAdd: false,
                handleEdit: false,
                rowIndex: {},
                options_dp: [],
                options_ps: [],
                editPasswordShow: false,
                editPasswordForm: {
                    user_id: "",
                    newPassword1: "",
                    newPassword2: ""
                },
            };
        },
        computed: {
            ...mapGetters("navTabs", ["tabTableHeight"])
        },
        mounted() {
            this.onSubmit();
            this.initdp()
            this.initps()
        },
        methods: {
            initps() {
                let that = this;
                this.$ajax.get('admin/User/ps_select').then(function (res) {
                    that.options_ps = res.data.data
                }).catch(function () {
                    that.$message.error('网络错误,请刷新')
                })
            },
            initdp() {
                let that = this
                this.$ajax.get('admin/Department/index_select').then(function (res) {
                    that.options_dp = res.data
                }).catch(function () {
                    that.$message.error('网络错误,请刷新')
                })
            },
            onSubmit(pagination = null) {
                let that = this;
                if (pagination !== null) {
                    that.pagination = pagination;
                } else {
                    that.pagination.currentPage = 1;
                }
                if (this.loading) {
                    return;
                }
                this.$ajax
                    .post("/admin/User/index", {
                        search: that.search,
                        paginate: that.pagination
                    })
                    .then(function (res) {
                        if (res.data.error === 0) {
                            that.tableData = res.data.data.data;
                            that.pagination.total = res.data.data.total;
                            that.loading = false;
                        }
                    })
                    .catch(function (err) {
                        that.$message("网络错误！请联系管理员");
                        that.loading = false;
                    });
            },
            onClear() {
                for (let key in this.search) {
                    this.search[key] = "";
                }
                this.sc_name = '';
                this.onSubmit()
            },
            callbackAdd(data) {
                this.handleAdd = false;
                if (data) {
                    this.onSubmit(this.pagination);
                }
            },
            callbackEdit(data) {
                this.handleEdit = false;

                if (data) {
                    this.onSubmit(this.pagination);
                }
            },
            onEdit(row) {
                if (row.ps_id == null) {
                    row.ps_id = 0
                }
                let arr = ['dp_id', 'ps_id'];
                let s = {};
                for (let key in row) {
                    if (arr.includes(key)) {
                        s[key] = parseInt(row[key]);
                        if (s[key] === 0) {
                            s[key] = ''
                        }
                    } else {
                        s[key] = row[key]
                    }
                }
                this.rowIndex = s;
                this.handleEdit = true;
            },
            onDelete(row) {
                let that = this;
                this.$confirm(
                    "确定删除此员工【" + row.user_name + "】吗?",
                    "提示",
                    {
                        confirmButtonText: "确定",
                        cancelButtonText: "取消",
                        type: "warning"
                    }
                )
                    .then(() => {
                        this.$ajax
                            .post("admin/User/delete", {user_id: row.user_id})
                            .then(function (res) {
                                if (res.data.error === 0) {
                                    that.$message({
                                        type: "success",
                                        message: "删除成功!"
                                    });
                                    that.onSubmit(that.pagination);
                                } else {
                                    that.$message(res.data.message);
                                }
                            });
                    })
                    .catch(() => {
                        this.$message({
                            type: "info",
                            message: "已取消删除"
                        });
                    });
            },
            onChangePassword(row) {
                this.editPasswordShow = true;
                this.editPasswordForm.user_id = row.user_id;
            },
            tableSortChange(val) {
                this.pagination.prop = val.prop;
                this.pagination.order = val.order;
                this.onSubmit();
            },
            handleEditPassword() {
                let that = this;
                this.$ajax
                    .post("admin/User/editPwd", this.editPasswordForm)
                    .then(function (res) {
                        if (res.data.error === 0) {
                            that.$message({
                                type: "success",
                                message: "修改成功!"
                            });
                            that.editPasswordShow = false;
                        } else {
                            that.$message(res.data.message);
                        }
                    });
            },
            onSuperLogin(row) {
                let that = this;
                this.$ajax
                    .get("admin/User/super_login?id=" + row.user_id)
                    .then(function (res) {
                        if (res.data.error === 1) {
                            that.$message.error(res.data.msg);
                        } else {
                            localStorage.setItem("org_user_name", res.data.msg.user_name);
                            localStorage.setItem("org_ps_name", res.data.msg.ps_name);
                            localStorage.setItem("org_school_name", res.data.msg.sc_name);
                            localStorage.setItem("is_Super", true);
                            localStorage.setItem("org_user_type", res.data.msg.user_type);
                            localStorage.setItem("org_sc_id", res.data.msg.sc_id);
                            that.$router.go(0);
                        }
                    })
                    .catch(function () {
                        that.$message.error("网络错误，请刷新!");
                    });
            }
        }
    };
</script>
<style>
    .close1 .el-dialog__headerbtn {
        top: 9px;
    }

    .close1 .el-dialog__headerbtn .el-dialog__close {
        color: #fff;
    }
</style>
