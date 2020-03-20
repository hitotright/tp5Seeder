<template>
  <!-- dialog选择用户开始 -->
  <el-dialog top="10px"
             width="40%"
    title="修改应用信息"
    :modal="true"
    @close="close()"
    :visible="true"
    class="close tab_dialog"
    :close-on-click-modal="false"
    :close-on-press-escape="false">
    <el-form label-width="100px" size="mini">
      <el-row>
        <el-col :span="17">
          <el-form-item label="应用名称：" required>
            <el-input clearable v-model="list.app_name" placeholder="请输入应用名称"></el-input>
          </el-form-item>
          <el-form-item label="最低版本：" required>
            <el-input clearable v-model="list.mini_up_version" placeholder="请输入最低版本"></el-input>
          </el-form-item>
        </el-col>
      </el-row>
    </el-form>
    <div slot="footer" v-show="footer_show">
      <el-button @click="close()" title="取消">取 消</el-button>
      <el-button type="primary" @click="handleConfirm()" title="确定提交">确 定</el-button>
    </div>
  </el-dialog>
</template>
<script>
    export default {
        props: {
            app_id: {
                type: Number,
                default: 0
            }
        },
        watch: {
            app_id: function (data) {
                this.app_id = data
            }
        },
        data() {
            return {
                list: [],
                footer_show: true,
            }
        },
        mounted() {
            this.initGetData();
        },
        methods: {
            initGetData() {
                let that = this;
                this.$ajax.post('website/app/getAppForEdit', {app_id: that.app_id}).then(function (res) {
                    if (res.data.error === 0) {
                        that.list = res.data.data;
                    } else {
                        that.$message(res.data.message)
                    }
                })
            },
            handleConfirm() {
                let that = this
                this.$ajax.post('website/app/edit', this.list).then(function (res) {
                    console.log(res)
                    if (res.data.error === 0) {
                        that.$emit('callback', true)
                    } else {
                        that.$message.error(res.data.message)
                    }
                })
            },
            close() {
                this.$emit('callback', false)
            },
        }
    }
</script>
