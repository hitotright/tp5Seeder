<template>
  <div>
    <search-wrap>
      <el-form :model="search" label-width="72px" size="mini" onsubmit="return false" :inline="true">
        <div class="form_data">
          <el-form-item label="广告名称：">
            <el-input clearable v-model="search.ad_name" placeholder="请输入" clearable
                      @keyup.enter.native="onSubmit()"></el-input>
          </el-form-item>
          <el-form-item label="客户端：">
            <el-select class="width_sp111" v-model="search.ad_type" clearable placeholder="请选择"
                       :filterable=true>
              <el-option v-for="item in TmpOptions1" :key="item.value" :label="item.label"
                         :value="item.value">
              </el-option>
            </el-select>
          </el-form-item>
          <el-form-item label="广告位置：">
            <el-select class="width_sp111" v-model="search.ad_local" clearable placeholder="请选择"
                       :filterable=true>
              <el-option v-for="item in TmpOptions2" :key="item.value" :label="item.label"
                         :value="item.value">
              </el-option>
            </el-select>
          </el-form-item>
          <el-form-item label="图片地址：">
            <el-input clearable v-model="search.ad_img" placeholder="请输入" clearable
                      @keyup.enter.native="onSubmit()"></el-input>
          </el-form-item>
          <el-form-item label="跳转地址：">
          <el-input clearable v-model="search.ad_url" placeholder="请输入" clearable
                    @keyup.enter.native="onSubmit()"></el-input>
        </el-form-item>
          <el-button type="primary" size="mini" @click="onSubmit()" icon="el-icon-search" title="查询">
            查询
          </el-button>
          <el-button type="danger" size="mini" @click="onClear()" icon='el-icon-refresh'
                     title="重置搜索条件">重置
          </el-button>
          <el-button type="success" size="mini"
                     @click="addTab({id:'website_adverts_add', name:'添加广告',url:'website/adverts/Add'})"

                     icon='el-icon-plus' title="添加广告">添加广告
          </el-button>

        </div>
      </el-form>
    </search-wrap>
    <div class="col-sm-12">
      <el-table v-loading="loading" :data="tableData" height="100%" size="mini" style="min-height: 400px;">
        <el-table-column label="编号" prop="ad_id" align="center"></el-table-column>
        <el-table-column label="广告名称" prop="ad_title" align="center"></el-table-column>
        <el-table-column label="广告位置" align="center">
          <template slot-scope="scope">
            <span v-if="scope.row.ad_position==='h1'">顶部</span>
            <span v-if="scope.row.ad_position==='h2'">轮播</span>
            <span v-if="scope.row.ad_position==='h3'">分类侧边</span>
            <span v-if="scope.row.ad_position==='h4'">中部推荐</span>
            <span v-if="scope.row.ad_position==='l1'">顶部</span>
            <span v-if="scope.row.ad_position==='l2'">侧边</span>
          </template>
        </el-table-column>
        <el-table-column label="图片地址" align="center" >
          <template slot-scope="scope">
           <div :title="scope.row.ad_image">{{scope.row.ad_image}}</div>
          </template>
        </el-table-column>
        <el-table-column label="跳转地址" align="center">
          <template slot-scope="scope">
            <div :title="scope.row.ad_url">{{scope.row.ad_url}}</div>
          </template>
        </el-table-column>
        <el-table-column label="客户端" prop="ad_type"  align="center">
          <template slot-scope="scope">
            <span v-if="scope.row.ad_type===1">PC端官网</span>
            <span v-if="scope.row.ad_type===2">M端官网</span>
            <span v-if="scope.row.ad_type===3">APP</span>
          </template>
        </el-table-column>
        <el-table-column label="添加时间" prop="add_time" align="center"></el-table-column>
        <el-table-column label="操作" align="center">
          <template slot-scope="scope">
            <el-button-group>
              <el-button type="text" size="small" style="color: blue" title="编辑" @click="edit(scope.row)">编辑</el-button>
              <el-button type="text" size="small" style="color:red" title="删除" @click="addel(scope.row.ad_id)">删除</el-button>
            </el-button-group>
          </template>
        </el-table-column>
      </el-table>
      <pagination :data="pagination" style="margin-bottom: 20px" @submit="onSubmit"/>
    </div>
  </div>
</template>
<script>
    import {mapState, mapMutations, mapGetters} from "vuex";
  export default {
      data(){
        return{
            search:{ad_type:'',ad_name:'',ad_local:'',ad_img:'',ad_url:''},
            pagination: {currentPage: 1, pageSize: 10, total: 0, last_page: 1}, // 分页相关数据
            tableData:[],
            loading: false,
            TmpOptions1:[
                {
                    value: "1",
                    label: "PC端官网"
                },
                {
                    value: "2",
                    label: "M端官网"
                },
                {
                    value: "3",
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
      computed: {
          ...mapGetters("navTabs", ["tabTableHeight"]),
          ...mapGetters("navTabs", ["getParam"]),
          param() {
              return this.getParam("website_adverts_Index");
          },
      },
      mounted() {
          this.onSubmit();
      },
      watch: {
          param(val, oldVal) {
              if (val.reload === true) {
                  this.onSubmit();
              }
          },
      },
      methods:{
          ...mapMutations("navTabs", ["addTab"]),
          onClear() {
              for (let key in this.search) {
                  this.search[key] = "";
              }
              this.onSubmit()
          },
          onSubmit(pagination = null){
              let that = this;
              if (pagination !== null) {
                  that.pagination = pagination;
              } else {
                  that.pagination.currentPage = 1;
              }
              this.$ajax
                  .post("/website/adverts/index", {
                      search: that.search,
                      paginate: that.pagination
                  })
                  .then(function (res) {
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
          addel(id){
              this.$confirm('此操作将永久删除该广告, 是否继续?', '提示', {
                  confirmButtonText: '确定',
                  cancelButtonText: '取消',
                  type: 'warning'
              }).then(() => {
                  let that = this;
                  this.$ajax
                      .post("/website/adverts/delete", {
                          id: id,
                      })
                      .then(function (res) {
                          console.log(res)
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
          edit(val){
              let arr = [];
              let s = {};
              for (let key in val) {
                  if (arr.includes(key)) {
                      s[key] = parseInt(val[key]);
                      if (s[key] === 0) {
                          s[key] = ''
                      }
                  } else {
                      s[key] = val[key]
                  }
              }
              s.ad_image = ''
              this.addTab({id: 'website_adverts_edit', name: '编辑广告', url: 'website/adverts/Edit', param: s})
          }
      }
  }
</script>
