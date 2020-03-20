<template>
    <page-wrap>
        <el-button type="primary" size="small" plain @click="addOne">添加一级部门</el-button>
        <el-tree
                :data="data"
                :props="defaultProps"
                node-key="id"
                default-expand-all
                :render-content="renderContent">
        </el-tree>
    </page-wrap>
</template>
<script>
    export default {
        data() {
            return {
                data: [],
                defaultProps: {
                    children: 'children',
                    label: 'label'
                }
            };
        },
        mounted() {
            this.init()
        },
        methods: {
            addOne() {
                let that = this
                this.$prompt('部门名称','提示',  {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                }).then(({value}) => {
                    that.$ajax.post('admin/department/addOne', {name: value}).then(function (res) {
                        let type = 'success'
                        if (res.data.error === 1) {
                            type = 'error';
                        } else {
                            that.init()
                        }
                        that.$message({
                            type: type,
                            message: res.data.message
                        });
                    })

                });
            },
            init(){
                let that = this
                this.$ajax.get('admin/Department/index').then(function (res) {
                    that.data = res.data
                }).catch(function () {
                    that.$message.error('网络错误,请刷新')
                })
            },
            append(data) {
                let that = this
                this.$prompt('部门名称','提示',  {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                }).then(({ value }) => {
                    that.$ajax.post('admin/Department/add',{name:value,id:data.id}).then(function (res) {
                        let type='success'
                        if(res.data.error === 1){
                            type='error';
                        }else{
                            that.init()
                        }
                        that.$message({
                            type: type,
                            message: res.data.message
                        });
                    })

                });
            },
            remove(data) {
                let that = this
                this.$confirm('您确定要删除此部门及其下子部门？', '警告', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                }).then(() => {
                    that.$ajax.get('admin/Department/delete?id='+data.id).then(function (res) {
                        let type='success'
                        if(res.data.error === 1){
                            type='error';
                        }else{
                            that.init()
                        }
                        that.$message({
                            type: type,
                            message: res.data.message
                        });
                    })

                });
            },
            renderContent(h, { node, data, store }) {
                let that = this;
                return h('span', [
                    h('div', [
                        h('span', {
                            domProps: {innerHTML:data.label},
                            style: {
                                fontSize: '13px'
                            }
                        }),
                        h('span', [
                                h('i', {
                                    on: {click:()=>that.append(data)},
                                    class: 'el-icon-circle-plus',
                                    style: {
                                        color: '#88d0e2',
                                        margin: '5px 10px',
                                        fontSize: '15px'
                                    }
                                }),
                                h('i', {
                                    on: {click:()=>that.remove(data)},
                                    class: 'el-icon-circle-close',
                                    style: {
                                        color: '#fd9d9d',
                                        margin: '5px 0px',
                                        fontSize: '15px'
                                    }
                                })
                            ]
                        )
                    ])
                ])
            }
        }
    };
</script>
<style >
.el-message-box__headerbtn {
    top: 7px;
}
</style>

