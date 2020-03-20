<template>
    <el-dialog
            width="30%"
            title="编辑员工"
            :modal="true"
            @close="close()"
            :visible="true"
            class="close">
        <el-form label-width="80px" size="mini">
            <el-form-item label="员工名称:" required>
                <el-input clearable v-model="list.user_name"></el-input>
            </el-form-item>
            <el-form-item label="登录名:" required>
                <el-input clearable v-model="list.login_name"></el-input>
            </el-form-item>
            <el-form-item label="电话:">
                <el-input clearable v-model="list.phone"></el-input>
            </el-form-item>
            <el-form-item label="邮箱:">
                <el-input clearable v-model="list.email"></el-input>
            </el-form-item>
            <el-form-item label="部门:">
                <el-select clearable class="width_sp111" v-model="list.dp_id" placeholder="请选择部门">
                    <el-option
                            v-html="item.label"
                            v-for="item in options11"
                            :key="item.value"
                            :value="item.value"
                            :label="item.label_raw">
                    </el-option>
                </el-select>
            </el-form-item>
            <el-form-item label="职位:">
                <PsSelect @callback="psCallBack" :initValue="list.ps_id"></PsSelect>
            </el-form-item>
        </el-form>
        <div slot="footer">
            <el-button  @click="close()" title="取消">取 消</el-button>
            <el-button  type="primary" @click="handleConfirm()" title="确定提交">确 定</el-button>
        </div>
    </el-dialog>
</template>
<script>
    import PsSelect from './../../../components/select/PsSelect'

    export default {
        components: {
            PsSelect
        },
        props: {
            list: {
                type: Object,
                default: {}
            },
            handle_sc: false,
            sc_name: '',
        },
        watch: {
            list: function (data, old) {
                this.list = data
                if (!this.list.dp_id) {
                    this.list.dp_id = ''
                }
                if (!this.list.ps_id) {
                    this.list.ps_id = ''
                }
            },
        },
        data() {
            return {
                options11: [],
            }
        },
        mounted() {
            this.initLabelOptions2()
        },
        methods: {
            initLabelOptions2() {
                let that = this;
                this.$ajax.get('admin/Department/index_select').then(function (res) {
                    that.options11 = res.data
                }).catch(function () {
                    that.$message.error('网络错误,请刷新')
                })
            },
            handleConfirm() {
                let that = this
                this.$ajax.post('admin/User/edit', that.list)
                    .then(function (res) {
                        if (res.data.error === 0) {
                            that.$message({
                                type: 'success',
                                message: '编辑成功'
                            })
                            that.$emit('callback', true)
                        } else {
                            that.$message(res.data.message)
                        }
                    })
            },
            close() {
                this.$emit('callback', false)
            },
            psCallBack(data) {
                this.list.ps_id = data
            },
            dpCallBack(data) {
                this.list.dp_id = data
            }
        }
    }
</script>