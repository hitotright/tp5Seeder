<template>
    <page-wrap>

        <div
                style="height: 36px;width: 100%;background:#fdf6ec;color:#e6a23c;border-radius:10px;position: fixed;
    z-index: 100;"
                shadow="never"
        >
            <el-breadcrumb
                    separator-class="el-icon-arrow-right"
                    style="line-height: 36px;"
            >
                <span style="float: left;margin:0 20px">Tip:操作步骤</span>
                <el-breadcrumb-item>选择菜单</el-breadcrumb-item>
                <el-breadcrumb-item>选择职位</el-breadcrumb-item>
                <el-breadcrumb-item>选择权限</el-breadcrumb-item>
                <el-breadcrumb-item>选择范围</el-breadcrumb-item>
                <el-button
                        type="danger"
                        @click="pmSave()"

                >保存
                </el-button>
            </el-breadcrumb>
        </div>
        <el-row :gutter="6" style="padding-top: 36px">
            <el-col :span="6">
                <el-card
                        style="width: 100%"
                        class="clearfixT"
                >
                    <div
                            slot="header"
                            class="clearfix"
                            style=""
                    >
                        <span>菜单</span>
                    </div>
                    <el-tree
                            :data="pmData"
                            node-key="id"
                            highlight-current
                            default-expand-all
                            @node-click="pmSelect"
                    ></el-tree>
                </el-card>
            </el-col>
            <el-col :span="6">
                <el-card
                        style="width: 100%"
                        class="clearfixT"
                >
                    <div
                            slot="header"
                            class="clearfix"
                    >
                        <span>职位</span>
                    </div>
                    <el-table
                            ref="permission_ps_table"
                            v-loading="loading"
                            :data="psData"
                            size="mini"
                            highlight-current-row
                            @current-change="psChange"
                    >
                        <el-table-column
                                label="职位名称"
                        >
                            <template slot-scope="scope">
                                <div style="width:100%" @click="Checkbox_lh(scope)">{{ scope.row.ps_name }}</div>
                            </template>
                        </el-table-column>
                    </el-table>
                </el-card>
            </el-col>
            <el-col :span="6">
                <el-card
                        style="width: 100%"
                        class="clearfixT"
                >
                    <div
                            slot="header"
                            class="clearfix"
                    >
                        <span>权限</span>
                        <el-button-group style="float: right;">
                            <el-button
                                    @click="actionCancel()"

                            >取消
                            </el-button>
                            <el-button
                                    @click="actionAll()"

                            >全选
                            </el-button>
                        </el-button-group>
                    </div>

                    <el-checkbox-group
                            v-model="action"
                            @change="actionChange"
                    >

                        <el-row
                                v-for="item in actionData"
                                :key="item.action_id"
                        >
                            <el-col :span="24">
                                <el-checkbox :label="item.action_name"></el-checkbox>
                            </el-col>

                        </el-row>

                    </el-checkbox-group>

                </el-card>
            </el-col>
            <el-col :span="6" v-show="false">
                <el-card
                        style="width: 100%"
                        class="clearfixT"
                >
                    <div
                            slot="header"
                            class="clearfix1"
                    >
                        <span style="padding-left:20px">范围</span>
                        <el-tree
                                ref="permission_dp_tree"
                                :data="dpData"
                                node-key="id"
                                default-expand-all
                                show-checkbox
                                :check-on-click-node="true"
                                @node-click="dpChange"
                                @check="dpChange2"
                        ></el-tree>
                    </div>
                </el-card>
            </el-col>
        </el-row>
    </page-wrap>
</template>
<script>
    export default {
        data() {
            return {
                psData: [],
                pmData: [],
                actionData: [],
                dpData: [],
                pmId: 0,
                psId: [],
                psInfo: {},
                actionPsId: 0,
                action: [],
                loading: false,
            };
        },
        mounted() {
            this.initData();
        },
        methods: {
            Checkbox_lh(re){
                if( this.psId.indexOf(re.row)==-1){
                    this.psId.push(re.row)
                    this.$refs.permission_ps_table.toggleRowSelection(re.row);
                }
            },
            initData() {
                let that = this;
                this.$ajax
                    .get("admin/Permission/index")
                    .then(function(res) {
                        that.psData = res.data.psData;
                        that.pmData = res.data.pmData;
                        that.actionData = res.data.actionData;
                        that.dpData = res.data.dpData;
                    })
                    .catch(function(res) {
                        that.$message("网络错误！请联系管理员");
                    });
            },
            pmSave() {
                let that = this;
                for (let i in this.psInfo) {
//        if (this.psInfo[i].range.length === 0) {
//          this.$message.error("有职位未配置权限范围");
//          return;
//        }
//        if (this.psInfo[i].action.length === 0) {
//          this.$message.error("有职位未配置任何权限");
//          return;
//        }
                }
                this.$ajax
                    .post("admin/Permission/editPermission", {
                        psInfo: this.psInfo,
                        psId: this.psId,
                        pmId: this.pmId
                    })
                    .then(function(res) {
                        if (res.data.error === 0) {
                            that.$notify({
                                title: "成功",
                                message: "所有修改已生效！",
                                type: "success",
                                duration: 2000
                            });
                        } else {
                            that.$notify.error({
                                title: "错误",
                                message: res.data.message,
                                duration: 2000
                            });
                        }
                    });
            },
            actionAll() {
                let id = [];
                let name = [];
                this.actionData.forEach(t => {
                    name.push(t.action_name);
                    id.push(t.action_id);
                });
                if(this.actionPsId === 0){
                    this.$message({
                        message:'请点击选择职位',type:'warning'
                    })
                    return
                }
                this.psInfo[this.actionPsId].action = id;
                this.action = name;
            },
            actionCancel() {
                if(this.actionPsId === 0){
                    this.$message({
                        message:'请点击选择职位',type:'warning'
                    })
                    return
                }
                this.psInfo[this.actionPsId].action = [];
                this.action = [];
            },
            dpChange(dp, tree) {
                var arr = this.$refs.permission_dp_tree.getCheckedKeys()
                let dpArr = [];
                if (dp.id === 0) {
                    dpArr = [0];
                } else if (dp.id === -1) {
                    dpArr = [-1];
                } else {
                    dpArr = arr.filter(f => f !== 0 && f !== -1);
                }
                this.$refs.permission_dp_tree.setCheckedKeys(dpArr);
                this.psInfo[this.actionPsId].range = dpArr;
            },
            dpChange2(dp, tree) {
                var arr = this.$refs.permission_dp_tree.getCheckedKeys()
                let dpArr = [];
                if (dp.id === 0) {
                    dpArr = [0];
                } else if (dp.id === -1) {
                    dpArr = [-1];
                } else {
                    dpArr = tree.checkedKeys.filter(f => f !== 0 && f !== -1);
                }
                this.$refs.permission_dp_tree.setCheckedKeys(dpArr);
                this.psInfo[this.actionPsId].range = dpArr;
            },
            actionChange(val) {
                if(this.actionPsId === 0){
                    this.$message({
                        message:'请点击选择职位',type:'warning'
                    })
                    return
                }
                let tmp = this.actionData.filter(f => val.indexOf(f.action_name) >= 0);
                let a = [];
                tmp.forEach(t => a.push(t.action_id));
                this.psInfo[this.actionPsId].action = a;
            },
            psCancel() {
                this.$refs.permission_ps_table.clearSelection();
            },
            psSelect(val) {
                let ps = [];
                val.forEach(v => {
                    if (v !== undefined) {
                        ps.push(v.ps_id);
                    }
                });
                this.psId = val;
            },
            psChange(val) {
                if (val === null) {
                    return;
                }
                this.actionPsId = val.ps_id;
                if (this.psInfo[val.ps_id] === undefined) {
                    this.psInfo[val.ps_id] = {
                        ps_id: val.ps_id,
                        action: [],
                        range: [0]
                    };
                    this.action = [];
                } else {
                    let label = this.actionData.filter(
                        f => this.psInfo[val.ps_id].action.indexOf(f.action_id) >= 0
                    );
                    let tmp = [];
                    label.forEach(l => {
                        tmp.push(l.action_name);
                    });
                    this.action = tmp;
                }
                let dp = [];
                if (this.psInfo[val.ps_id].range_type === 3) {
                    dp = [-1];
                }
                if (this.psInfo[val.ps_id].range_type === 1) {
                    dp = [0];
                }
                this.psInfo[val.ps_id].range.forEach(d => {
                    dp.push(d);
                });
                this.$refs.permission_dp_tree.setCheckedKeys(dp);
            },
            pmSelect(val) {
                let that = this;
                that.$refs.permission_ps_table.setCurrentRow(null);
                that.$refs.permission_ps_table.clearSelection();
                this.pmId = val.id;
                this.$ajax
                    .get("admin/Permission/indexPosition?id=" + val.id)
                    .then(function (res) {
                        that.psInfo = res.data;
                        for (let k in res.data) {
                            let r = that.psData.filter(f => f.ps_id == k)[0];
                            that.$refs.permission_ps_table.toggleRowSelection(r);
                        }
                    })
                    .catch(function (e) {
                        that.$message(e);
                    });
            }
        }
    };
</script>
<style>
</style>
