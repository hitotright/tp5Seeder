<template>
    <page-wrap>
        <!-- 搜索栏 -->
        <search-wrap>
            <el-form :model="search" label-width="80px" size="mini" onsubmit="return false">
                <el-row>
                    <el-col :span="20">
                        <div class="bg">
                            <el-form-item label="职位名称:">
                                <el-input clearable v-model="search.ps_name" placeholder="请输入职位名称"
                                          @keyup.enter.native="onSubmit()"></el-input>
                            </el-form-item>
                            <el-button type="primary" size="mini" @click="onSubmit()" icon="el-icon-search" title="查询职位">查询</el-button>
                            <el-button type="danger" size="mini" @click="onClear()" icon='el-icon-refresh' title="重置职位">重置</el-button>
                            <el-button type="success" size="mini" @click="handleAdd = true" icon='el-icon-plus' title="添加职位">添加
                            </el-button>
                        </div>
                    </el-col>

                </el-row>
            </el-form>
        </search-wrap>
        <!-- 表格 -->
        <table-wrap>
            <el-table
                    v-loading="loading"
                    :data="tableData"
                    size="mini"
                    :height='tabTableHeight'>

                <el-table-column prop="ps_name" label="职位名称"></el-table-column>
                <el-table-column label="操作" min-width="100">
                    <template slot-scope="scope">
                        <el-button @click="onEdit(scope.row)" type="text" size="small"  title="职位">编辑</el-button>
                        <el-button @click="onDelete(scope.row)" type="text" size="small"  title="删除职位">删除</el-button>
                    </template>
                </el-table-column>
            </el-table>
        </table-wrap>
        <!-- 分页 -->
        <pagination :data="pagination" @submit="onSubmit"/>
        <add v-if="handleAdd" @callback="callbackAdd"/>
        <edit v-if="handleEdit" :list="rowIndex" @callback="callbackEdit" />
    </page-wrap>
</template>

<script>
    import Add from './Add.vue'
    import { mapState, mapGetters} from 'vuex'
    import Edit from './Edit.vue';

    export default {
        components: {
            Edit,
            Add
        },
        data() {
            return {
                search: {ps_id: '', ps_name: ''}, // 搜索词
                pagination: {currentPage: 1, pageSize: 50, total: 0}, // 分页相关数据
                loading: false,
                tableData: [],
                handleAdd: false,
                handleEdit: false,
                rowIndex:'',
            }
        },
        computed: {
            ...mapGetters('navTabs', ['tabTableHeight'])
        },
        mounted() {
            this.onSubmit()
        },
        methods: {
            onSubmit(pagination = null) {
                let that = this
                if (pagination !== null) {
                    that.pagination = pagination
                } else {
                    that.pagination.currentPage = 1
                }
                if (this.loading) {
                    return
                }
                this.$ajax.post('/admin/Position/index', {search: that.search, paginate: that.pagination})
                    .then(function (res) {
                        if (res.data.error === 0) {
                            that.tableData = res.data.data.data
                            that.pagination.total = res.data.data.total
                            that.loading = false
                        }
                    })
                    .catch(function (err) {
                        that.$message('网络错误！请联系管理员')
                        that.loading = false
                    })
            },
            onClear() {
                for (let key in this.search) {
                    this.search[key] = "";
                }
                this.onSubmit()
            },
            callbackAdd(data) {
                this.handleAdd = false
                if (data) {
                    this.onSubmit(this.pagination)
                    this.$message({
                        type: "success",
                        message: "添加成功!"
                    });
                }
            },
            callbackEdit(data) {
                this.handleEdit = false
                if (data) {
                    this.onSubmit(this.pagination)
                    this.$message({
                        type: "success",
                        message: "编辑成功!"
                    });
                }
            },
            onDelete(row) {
                let that = this
                this.$confirm('确定删除此职位【' + row.ps_name + '】吗?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    this.$ajax.post('admin/Position/delete', {ps_id: row.ps_id}).then(function (res) {
                        if (res.data.error === 0) {
                            that.$message({
                                type: 'success',
                                message: '删除成功!'
                            })
                            that.onSubmit(that.pagination)
                        } else {
                            that.$message(res.data.message)
                        }
                    })
                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: '已取消删除'
                    })
                })
            },
            onEdit(row) {
                let s = {};
                for (let key in row) {
                    s[key] = row[key];
                }
                this.rowIndex = s;
                this.handleEdit = true;
            }
        }
    }
</script>