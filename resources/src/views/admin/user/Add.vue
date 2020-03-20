<template>
    <!-- dialog选择用户开始 -->
    <el-dialog
            width="30%"
            title="添加员工"
            :modal="true"
            @close="close()"
            :visible="true"
            class="close"
            :close-on-click-modal="false"
            :close-on-press-escape="false">
        <el-form label-width="100px" size="mini">
            <el-form-item label="员工名称:" required>
                <el-input clearable v-model="user_name"></el-input>
            </el-form-item>
            <el-form-item label="登录名:" required>
                <el-input clearable v-model="login_name"></el-input>
            </el-form-item>
            <el-form-item label="部门:">
                <DpSelect @callback="dpCallBack"></DpSelect>
            </el-form-item>
            <el-form-item label="职位:" >
                <PsSelect @callback="psCallBack"></PsSelect>
            </el-form-item>
            <el-form-item label="电话:">
                <el-input clearable v-model="phone"></el-input>
            </el-form-item>
            <el-form-item label="邮箱:">
                <el-input clearable v-model="email"></el-input>
            </el-form-item>
            <el-form-item label="密码:" required>
                <el-input clearable v-model="password"></el-input>
            </el-form-item>
        </el-form>
        <div slot="footer">
            <el-button  @click="close()" title="取消">取 消</el-button>
            <el-button  type="primary" @click="handleConfirm()" title="确定提交">确 定</el-button>
        </div>
    </el-dialog>
</template>
<script>
    import DpSelect from './../../../components/select/DpSelect'
    import PsSelect from './../../../components/select/PsSelect'

    export default {
        components: {
            DpSelect, PsSelect
        },
        props: {
            handle: {
                type: Boolean,
                default: false
            }
        },
        watch: {
            handle: function (data, old) {
                this.handle = data
            }
        },
        data() {
            return {
                user_name: '',
                login_name: '',
                phone: '',
                password: '',
                email: '',
                ps_id: 0,
                dp_id: 0,
                handle_sc: false,
            }
        },
        methods: {
            handleConfirm() {
                let that = this
                this.$ajax.post('admin/User/add', {
                    user_name: this.user_name,
                    phone: this.phone,
                    login_name: this.login_name,
                    email: this.email,
                    password: this.password,
                    ps_id: this.ps_id,
                    dp_id: this.dp_id,
                }).then(function (res) {
                    if (res.data.error === 0) {
                        that.$message({
                            type: 'success',
                            message: '添加成功'
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
                this.ps_id = data
            },
            dpCallBack(data) {
                this.dp_id = data
            },
        }
    }
</script>