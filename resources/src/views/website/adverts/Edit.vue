<template>
  <div style="margin-top: 20px">
    <el-row><el-col :span="22" :offset="1" style="padding-left: 27px;margin-bottom: 10px"><h3>基本信息</h3></el-col></el-row>
    <el-row>
      <el-col :span="22" :offset="1">
        <el-form label-width="100px" size="mini" style="margin-top: 10px;">
          <el-row>
            <el-col :span="7">
              <el-form-item label="广告名称：" required>
                <el-input clearable v-model="list.ad_title" placeholder="请输入广告名称" clearable></el-input>
              </el-form-item>
            </el-col>
            <el-col :span="7">
              <el-form-item label="广告位置：" required>
                <el-select class="width_sp111" v-model="list.ad_position" clearable placeholder="请选择"
                           :filterable=true>
                  <el-option v-for="item in TmpOptions2" :key="item.value" :label="item.label"
                             :value="item.value">
                  </el-option>
                </el-select>
              </el-form-item>
            </el-col>
            <el-col :span="7">
              <el-form-item label="客户端：" required>
                <el-select class="width_sp111" v-model="list.ad_type" clearable placeholder="请选择"
                           :filterable=true>
                  <el-option v-for="item in TmpOptions1" :key="item.value" :label="item.label"
                             :value="item.value">
                  </el-option>
                </el-select>
              </el-form-item>
            </el-col>
          </el-row>
          <el-row>
            <el-col :span="7">
              <el-form-item label="图片地址：" required>
                <el-input v-if="imgurl" @blur="beforeRemove1()" @click="beforeRemove()" clearable v-model="list.ad_image" placeholder="请输入图片链接"></el-input>
                <el-input v-if="!imgurl" disabled clearable v-model="list.ad_image" placeholder="请输入图片链接"></el-input>
              </el-form-item>
            </el-col>
            <el-col style="margin-left: 12px;" :span="7">
              <el-form-item label="请选择图片：" required>
                <el-upload
                  v-if="uplimg"
                  accept="image/*"
                  class="upload-demo"
                  action="/index.php/common/Upload/image"
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
                  accept="image/*"
                  class="upload-demo"
                  action="/index.php/common/Upload/image"
                  :on-remove="handleRemove"
                  :limit="1"
                  disabled
                  :on-success="handleAvatarSuccess"
                  :on-exceed="handleExceed">
                  <div @click="beforeRemove">
                    <el-button size="small" type="primary">点击上传</el-button>
                  </div>
                </el-upload>
              </el-form-item>
            </el-col>
            <el-col :span="7">
              <el-form-item label="跳转地址：" required>
                <el-input clearable v-model="list.ad_url" placeholder="请输入广告跳转链接"
                          clearable></el-input>
              </el-form-item>
            </el-col>
          </el-row>
          <el-row>
            <p><h6 style="margin-left: 50px;color: #4d5b71">备注：图片地址与选择图片二选一既可</h6></p>
          </el-row>
        </el-form>
      </el-col>
    </el-row>
    <el-row :gutter="24">
      <el-col :span="7" :offset="8" style="margin-top: 290px">
        <el-button @click="close()" title="取消">取 消</el-button>
        <el-button style="float: right" type="primary" @click="handleConfirm()" title="确定提交">确 定</el-button>
      </el-col>
    </el-row>
  </div>
</template>
<script>
    import {mapState, mapMutations, mapGetters} from "vuex";

    export default {

        data() {
            return {
                uplimg:true,
                imgurl:true,
                pd:true,
                list: {
                    ad_title: '',
                    ad_position: '',
                    ad_type: '',
                    ad_image: '',
                    ad_url: '',
                },
                b_id: '',
                TmpOptions1:[
                    {
                        value: 1,
                        label: "PC端官网"
                    },
                    {
                        value: 2,
                        label: "M端官网"
                    },
                    {
                        value: 3,
                        label: "APP"
                    },
                ],
                TmpOptions2:[
                    {
                        value: "h1",
                        label: "顶部"
                    },
                    {
                        value: "h2",
                        label: "轮播"
                    },
                    {
                        value: "h3",
                        label: "分类侧边"
                    },
                    {
                        value: "h4",
                        label: "中部推荐"
                    },
                    {
                        value: "l1",
                        label: "顶部"
                    },
                    {
                        value: "l2",
                        label: "侧边"
                    },
                ],

            }
        },
        watch: {},
        mounted() {
            this.b_id = localStorage.getItem('org_b_id');
            this.list = this.getParam("school_school_edit")()
        },
        methods: {
            ...mapGetters("navTabs", ["getParam"]),
            ...mapMutations("navTabs", ["closeTab"]),
            ...mapMutations("navTabs", ["addTab"]),
            handleAvatarSuccess(res){
                this.list.ad_image = res.data;
                this.imgurl = false
                this.pd = true
            },
            handleRemove(){
                this.list.ad_image = '';
                this.imgurl = true
            },
            beforeRemove1(){
                if (this.list.ad_image !== ''){
                    this.uplimg = false
                    let reg=/^([hH][tT]{2}[pP]:\/\/|[hH][tT]{2}[pP][sS]:\/\/)(([A-Za-z0-9-~]+)\.)+([A-Za-z0-9-~\/])+$/;
                    if(!reg.test(this.list.ad_image)){
                        this.pd = false;
                    }
                }
            },
            beforeRemove(){
                if(this.list.ad_image === '') {
                    this.uplimg = true
                    this.imgurl = true
                }
            },
            handleExceed() {
                this.$message.warning('当前只允许上传一张图片，若想重新上传请将已上传图片删除');
            },
            handleConfirm() {
                let that = this;
                if(that.list.ad_image === ''){
                    that.$message("请输入下载地址或上传文件");
                    return false
                }
                if (that.pd == false){
                    that.$message("下载地址格式不正确");
                    return false;
                }
                let reg=/^([hH][tT]{2}[pP]:\/\/|[hH][tT]{2}[pP][sS]:\/\/)(([A-Za-z0-9-~]+)\.)+([A-Za-z0-9-~\/])+$/;
                if(this.list.ad_url==""){
                    this.$message("请输入跳转网址");
                    this.pd = false
                }
                if(!reg.test(this.list.ad_url)){
                    this.$message("跳转地址格式不正确");
                    this.pd = false;
                }
                this.$ajax.post('/website/adverts/edit', this.list).then(function (res) {
                    // console.log(res)
                    if (res.data.error === 0) {
                        that.close()
                        that.$message({
                            type: "success",
                            message: "编辑广告成功!"
                        });
                    } else {
                        that.$message.error(res.data.message)
                    }
                })
            },
            close() {
                this.closeTab('website_adverts_add')
                this.addTab({id: 'website_adverts_Index',
                    name: '广告管理',
                    url: 'website/adverts/Index',
                    param: {reload: true}})
            },
            getDetail(msg){
                this.list.sc_detail = msg
            },

        }
    }
</script>
