<template>
  <page-wrap>
    <search-wrap>
      <el-form :model="search" label-width="72px" size="mini" onsubmit="return false" :inline="true">
        <div class="form_data">
          <el-form-item label="应用名称：">
            <el-input clearable v-model="search.app_name" placeholder="请输入" clearable
                      @keyup.enter.native="onSubmit()"></el-input>
          </el-form-item>
          <el-form-item label="审核状态：">
            <el-select class="width_sp111" v-model="search.status" clearable placeholder="请选择"
                       :filterable=true>
              <el-option v-for="item in TmpOptions1" :key="item.value" :label="item.label"
                         :value="item.value">
              </el-option>
            </el-select>
          </el-form-item>
          <el-button type="primary" size="mini" @click="onSubmit()" icon="el-icon-search" title="查询">
            查询
          </el-button>
          <el-button type="danger" size="mini" @click="onClear()" icon='el-icon-refresh'
                     title="重置搜索条件">重置
          </el-button>
          <el-button type="success" size="mini"
                     @click="appAdd = true"
                     icon='el-icon-plus' title="添加版本">添加版本
          </el-button>
        </div>
      </el-form>
    </search-wrap>
    <table-wrap>
      <el-table v-loading="loading" :data="tableData" :height="tabTableHeight" size="mini">
        <el-table-column label="编号" prop="v_id" align="center"></el-table-column>
        <el-table-column label="应用名称" min-width='100' prop="app_name" align="center"></el-table-column>
        <el-table-column label="应用版本" min-width='100' prop="version" align="center"></el-table-column>
        <el-table-column label="应用地址" min-width='100' align="center">
          <template slot-scope="scope">
            <div :title="scope.row.version_url">{{scope.row.version_url}}</div>
          </template>
        </el-table-column>
        <el-table-column label="上传时间" min-width='150' prop="add_time" align="center"></el-table-column>
        <el-table-column label="上传者" min-width='100' prop="upload_name" align="center"></el-table-column>
        <el-table-column label="上传者ip" min-width='100' prop="upload_ip" align="center"></el-table-column>
        <el-table-column label="审核人" min-width='100' prop="check_name" align="center"></el-table-column>
        <el-table-column label="审核时间" min-width='150' prop="check_time" align="center"></el-table-column>
        <el-table-column label="审核ip" min-width='100' prop="check_ip" align="center"></el-table-column>
        <el-table-column label="更新说明" min-width='100' prop="memo" align="center"></el-table-column>
        <el-table-column label="审核状态" min-width='100' align="center">
          <template slot-scope="scope">
            <span v-if="scope.row.status===1">未审核</span>
            <span v-if="scope.row.status===2">审核通过</span>
            <span v-if="scope.row.status===3">审核未通过</span>
          </template>
        </el-table-column>
        <el-table-column label="操作" align="center" width='100' fixed="right">
          <template slot-scope="scope">
<!--            v-if="scope.row.status===1"-->
            <el-button type="text" size="small" style="color:green" title="编辑审核" @click="appupd(scope.row)">编辑审核</el-button>
          </template>
        </el-table-column>
      </el-table>
    </table-wrap>
    <pagination :data="pagination" @submit="onSubmit"/>
    <edit v-if="appUpd" :v_id="updIndex" @callback="callbackUpd"></edit>
    <add v-if="appAdd" @callback="callbackAdd"></add>
  </page-wrap>
</template>
<script>
    import {mapState, mapMutations, mapGetters} from "vuex";
    import Add from "./Add.vue";
    import Edit from "./Edit.vue";
    export default {
        components: {
            Add,
            Edit,
        },
        data(){
            return{
                search:{app_name:'',status:''},
                pagination: {currentPage: 1, pageSize: 10, total: 0, last_page: 1}, // 分页相关数据
                tableData:[],
                TmpOptions1:[
                    {
                        value: 1,
                        label: "未审核"
                    },
                    {
                        value: 2,
                        label: "审核通过"
                    },
                    {
                        value: 3,
                        label: "审核未通过"
                    },
                ],
                loading: false,
                appAdd:false,
                appUpd:false,
                updIndex:{},
            }
        },
         computed: {
             ...mapGetters("navTabs", ["tabTableHeight"]),
         },
        mounted() {
            this.onSubmit();
        },
        methods:{
            onClear() {
                for (let key in this.search) {
                    this.search[key] = "";
                }
                this.onSubmit()
            },
            callbackAdd(data) {
                this.appAdd = false;
                if (data) {
                    this.$message({
                        type: "success",
                        message: "添加成功！"
                    });
                    this.onSubmit(this.pagination);
                }
            },
            callbackUpd(data) {
                this.appUpd = false;
                if (data) {
                    this.$message({
                        type: "success",
                        message: "审核成功！"
                    });
                    this.onSubmit(this.pagination);
                }
            },
            onSubmit(pagination = null){
                let that = this;
                if (pagination !== null) {
                    that.pagination = pagination;
                } else {
                    that.pagination.currentPage = 1;
                }
                this.$ajax
                    .post("/website/history/index", {
                        search: that.search,
                        paginate: that.pagination
                    })
                    .then(function (res) {
                        console.log(res)
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
            appupd(row){
                if (row.status === 1){
                    this.updIndex =row.v_id;
                    console.log(this.updIndex)
                    this.appUpd = true
                }else{
                    this.$message('该记录已经审核，请审核其他')
                }
            }
        }
    }
</script>
