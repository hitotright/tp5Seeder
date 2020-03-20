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
            <el-input clearable v-model="list.app_name" disabled></el-input>
          </el-form-item>
          <el-form-item label="审核版本：" required>
            <el-input clearable v-model="list.version" disabled></el-input>
          </el-form-item>
          <el-form-item label="下载地址：" required>
            <el-input clearable v-model="list.version_url" disabled></el-input>
          </el-form-item>
          <el-form-item label="版本说明：" required>
            <el-input type="textarea" clearable v-model="list.memo" disabled></el-input>
          </el-form-item>
        </el-col>
      </el-row>
    </el-form>
    <div slot="footer" v-show="footer_show">
      <el-button type="primary" @click="handleConfirm(2)" title="通过审核">通 过</el-button>
      <el-button @click="handleConfirm(3)" title="拒绝审核">拒 绝</el-button>
      <el-button @click="close()" style="margin-left: 90px" title="取消">取 消</el-button>
    </div>
  </el-dialog>
</template>
<script>
    export default {
        props: {
            v_id: {
                type: Number,
                default: 0
            }
        },
        watch: {
            v_id: function (data) {
                this.v_id = data
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
                this.$ajax.post('website/history/getHistoryForEdit', {v_id: that.v_id}).then(function (res) {
                    console.log(res)
                    if (res.data.error === 0) {
                        that.list = res.data.data;
                    } else {
                        that.$message(res.data.message)
                    }
                })
            },
            handleConfirm(type) {
                let that = this
                this.list.type = type
                this.$ajax.post('website/history/edit', this.list).then(function (res) {
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
