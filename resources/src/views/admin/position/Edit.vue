<template>
    <!-- dialog选择用户开始 -->
    <el-dialog
            width="30%"
            title="添加职位"
            :modal="true"
            @close="close()"
            :visible="true"
            class="close">
        <el-form label-width="80px" size="mini" onsubmit="return false">
            <el-form-item label="职位名称:">
                <el-input clearable v-model="list.ps_name" ref="input" @keyup.enter.native="handleConfirm()"></el-input>
            </el-form-item>
        </el-form>
        <div slot="footer">
            <el-button @click="close()" title="取消">取 消</el-button>
            <el-button type="primary" @click="handleConfirm()" title="确定提交">确 定</el-button>
        </div>
    </el-dialog>
</template>
<script>
    export default {
        props: {
            list: {
                type: Object,
                default: {}
            }
        },
        watch: {
            list: function (data, old) {
                this.list = data
            },
        },
        data () {
            return {

            }
        },
        methods: {
            handleConfirm () {
                let that = this
                this.$ajax.post('admin/Position/edit', this.list).then(function (res) {
                    if (res.data.error === 0) {
                        that.$emit('callback', true)
                    } else {
                        that.$message(res.data.message)
                    }
                })
            },
            close () {
                this.$emit('callback', false);
            }
        }
    }
</script>