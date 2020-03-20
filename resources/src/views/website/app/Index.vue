<template>
  <page-wrap>
    <search-wrap>
      <el-form :model="search" label-width="72px" size="mini" onsubmit="return false" :inline="true">
        <div class="form_data">
          <el-form-item label="应用名称：">
            <el-input clearable v-model="search.app_name" placeholder="请输入" clearable
                      @keyup.enter.native="onSubmit()"></el-input>
          </el-form-item>
          <el-button type="primary" size="mini" @click="onSubmit()" icon="el-icon-search" title="查询">
            查询
          </el-button>
          <el-button type="danger" size="mini" @click="onClear()" icon='el-icon-refresh'
                     title="重置搜索条件">重置
          </el-button>
          <el-button type="success" size="mini"
                     @click="appAdd = true"
                     icon='el-icon-plus' title="添加应用">添加应用
          </el-button>
        </div>
      </el-form>
    </search-wrap>
    <table-wrap class="col-sm-12">
      <el-table v-loading="loading" :data="tableData" size="mini" :height="tabTableHeight">
        <el-table-column label="编号" prop="app_id" align="center"></el-table-column>
        <el-table-column label="应用名称" prop="app_name" align="center"></el-table-column>
        <el-table-column label="测试版本" prop="test_version" align="center"></el-table-column>
        <el-table-column label="正式版本" prop="app_version" align="center"></el-table-column>
        <el-table-column label="下载地址" align="center">
          <template slot-scope="scope">
            <div :title="scope.row.app_url">{{scope.row.app_url}}</div>
          </template>
        </el-table-column>
        <el-table-column label="最低可更新版本" prop="mini_up_version" align="center"></el-table-column>
        <el-table-column label="操作" align="center" width='200' fixed="right">
          <template slot-scope="scope">
              <el-button type="text" size="small" style="color: blue;" title="编辑" @click="edit(scope.row)">编辑</el-button>
              <el-button type="text" size="small" style="color:red;" title="删除" @click="appdel(scope.row.app_id)">删除</el-button>
              <el-button type="text" size="small" style="color:green;" title="上传" @click="appupd(scope.row)">上传</el-button>
          </template>
        </el-table-column>
      </el-table>
    </table-wrap>
    <pagination :data="pagination" @submit="onSubmit"/>
    <upd v-if="appUpd" :app_id="updIndex" @callback="callbackUpd"></upd>
    <add v-if="appAdd" @callback="callbackAdd"></add>
    <edit v-if="appEdit" :app_id="editIndex" @callback="callbackEdit"></edit>
  </page-wrap>
</template>
<script>
    import {mapState, mapMutations, mapGetters} from "vuex";
    import Add from "./Add.vue";
    import Upd from "./Upd.vue";
    import Edit from "./Edit.vue";
    export default {
        components: {
            Add,
            Upd,
            Edit,
        },
        data(){
            return{
                search:{app_name:''},
                pagination: {currentPage: 1, pageSize: 10, total: 0, last_page: 1}, // 分页相关数据
                tableData:[],
                loading: false,
                appAdd:false,
                appUpd:false,
                appEdit:false,
                updIndex:{},
                editIndex:{},

            }
        },
         computed: {
             ...mapGetters("navTabs", ["tabTableHeight"]),
         },
        mounted() {
            this.onSubmit();
        },
        // watch: {
        //     param(val, oldVal) {
        //         console.log(val)
        //         if (val.reload === true) {
        //             this.onSubmit();
        //         }
        //     },
        // },
        methods:{
            // ...mapMutations("navTabs", ["addTab"]),
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
                        message: "上传成功！"
                    });
                    this.onSubmit(this.pagination);
                }
            },
            callbackEdit(data) {
                this.appEdit = false;
                if (data) {
                    this.$message({
                        type: "success",
                        message: "编辑成功！"
                    });
                    this.onSubmit(this.pagination);
                }
            },
            onSubmit(pagination = null){
                // console.log(this.search)
                let that = this;
                if (pagination !== null) {
                    that.pagination = pagination;
                } else {
                    that.pagination.currentPage = 1;
                }
                this.$ajax
                    .post("/website/app/index", {
                        search: that.search,
                        paginate: that.pagination
                    })
                    .then(function (res) {
                        // console.log(res)
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
            appdel(id){
                this.$confirm('此操作将永久删除该应用, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    let that = this;
                    this.$ajax
                        .post("/website/app/delete", {
                            id: id,
                        })
                        .then(function (res) {
                            // console.log(res)
                            if (res.data.error === 0) {
                                that.$message('删除成功');
                            }else{
                                that.$message('删除失败');
                            }
                            that.onSubmit();
                        })
                        .catch(function (err) {
                            that.$message("网络错误！请联系管理员");
                            that.loading = false;
                        });
                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: '已取消删除'
                    });
                });

            },
            edit(row){
                this.editIndex = row.app_id;
                this.appEdit = true
            },
            appupd(row){
                this.updIndex =row.app_id;
                this.appUpd = true
            }
        }
    }
</script>
