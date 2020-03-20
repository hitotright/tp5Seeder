<template>
  <!-- dialog选择用户开始 -->
  <el-dialog top="10px"
             width="40%"
    title="添加应用"
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
                <el-input clearable v-model="formPost.app_name" placeholder="请输入应用名称"></el-input>
              </el-form-item>
            </el-col>
            <el-col :span="17">
              <el-form-item label="应用版本号：" required>
                <el-input clearable v-model="formPost.test_version" placeholder="请输入应用版本号"></el-input>
              </el-form-item>
            </el-col>
            <el-col :span="17">
              <el-form-item label="下载地址：" required>
                <el-input v-if="imgurl" clearable @blur="beforeRemove1()" v-model="formPost.test_url" placeholder="请输入下载地址"></el-input>
                <el-input v-if="!imgurl" disabled clearable v-model="formPost.test_url" placeholder="请输入下载地址"></el-input>
              </el-form-item>
            </el-col>
            <el-col style="margin-left: 20px" :span="5">
              <el-upload
                v-if="uplimg"
                accept="apk/*"
                class="upload-demo"
                action="/index.php/common/Upload/apk"
                :on-remove="handleRemove"
                :limit="1"
                :on-success="handleAvatarSuccess"
                :on-exceed="handleExceed">
                <div @click="beforeRemove">
                  <el-button size="small" type="primary">点击上传</el-button>
                </div>
              </el-upload>
              <el-upload
                v-if="!uplimg"
                accept="apk/*"
                class="upload-demo"
                action="/index.php/common/Upload/apk"
                :on-remove="handleRemove"
                :limit="1"
                disabled
                :on-success="handleAvatarSuccess"
                :on-exceed="handleExceed">
                <div @click="beforeRemove">
                  <el-button size="small" type="primary">点击上传</el-button>
                </div>
              </el-upload>
            </el-col>
            <el-col :span="17">
              <el-form-item label="签名秘钥：" required>
                <el-input clearable v-model="formPost.sign_secret" placeholder="请输入签名秘钥"></el-input>
              </el-form-item>
            </el-col>
          </el-row>
        </el-form>
    <div style="margin-left: 55px;">备注：输入下载地址和上传文件二选一既可</div>
    <div slot="footer" v-show="footer_show">
      <el-button @click="close()" title="取消">取 消</el-button>
      <el-button type="primary" @click="handleConfirm()" title="确定保存">保 存</el-button>
    </div>
  </el-dialog>
</template>
<script>

    export default {
        data() {
            return {
                formPost: {
                    app_name: '',
                    test_version: '',
                    test_url: '',
                    sign_secret: '',
                },
                footer_show: true,
                imgurl:true,
                uplimg:true,
                pd:true,
            }
        },
        methods: {
            handleRemove(){
                this.formPost.test_url = '';
                this.imgurl = true
            },
            handleExceed() {
                this.$message.warning('当前只允许上传一个版本文件，若想重新上传请将已上传版本删除');
            },
            handleAvatarSuccess(res){
                this.formPost.test_url = res.data;
                this.imgurl = false
                this.pd = true
            },
            beforeRemove1(){
                if (this.formPost.test_url !== ''){
                    this.uplimg = false
                    let reg=/^([hH][tT]{2}[pP]:\/\/|[hH][tT]{2}[pP][sS]:\/\/)(([A-Za-z0-9-~]+)\.)+([A-Za-z0-9-~\/])+$/;
                    if(!reg.test(this.formPost.test_url)){
                        this.pd = false;
                    }
                }
            },
            beforeRemove(){
                if(this.formPost.test_url === '') {
                    this.uplimg = true
                    this.imgurl = true
                }
            },
            handleConfirm() {
                let that = this
                if(that.formPost.test_url === ''){
                    that.$message("请输入下载地址或上传文件");
                    return false
                }
                if(that.pd === false){
                    that.$message("下载地址格式不正确");
                    return false
                }
                this.$ajax.post('website/app/add', this.formPost).then(function (res) {
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
