<template>
    <Row>
        <Col span="24">
            <router-link to="/"><Button type="primary">Go Back</Button></router-link>
        </Col>
        <Col span="24">
            <Table :data="tableData.data" :columns="tableColumns" stripe></Table>
            <div style="margin: 10px;overflow: hidden">
                <div style="float: right;">
                    <Page placement="top" :total="tableData.total" :current="tableData.current_page" :page-size="tableData.per_page" @on-change="changePage" @on-page-size-change="changeSize" show-total show-elevator show-sizer></Page>
                </div>
            </div>
        </Col>
    </Row>
</template>
<script>
    export default {
        mounted () {
            this.mockTableData1();
        },
        data () {
            return {
                tableData: {
                    total:null,
                    current_page:1,
                    per_page:10,
                    last_page:1,
                    order:'',
                    field:'asc',
                    data:[]
                },
                tableColumns: [
                    {
                        title: '编号',
                        key: 'user_id'
                    },
                    {
                        title: '用户名',
                        key: 'user_name'
                    },
                    {
                        title: '密码',
                        key: 'password'
                    },
                    {
                        title: '年龄',
                        key: 'age'
                    },

                ]
            }
        },
        methods: {
            mockTableData1 () {
                this.Util.ajax.post('/api.php/user',{page:this.tableData.current_page,limit:this.tableData.per_page,total:this.tableData.total})
                .then(response=>{
                    this.$set(this.tableData,'data', response.data.data);
                    this.$set(this.tableData,'total',response.data.total);
                    this.$set(this.tableData,'current_page', response.data.current_page);
                    this.$set(this.tableData,'per_page', response.data.per_page);
                    this.$set(this.tableData,'last_page', response.data.last_page);
                },response=>{
                    this.$Message.error('数据库访问失败！');
                });
            },
            changePage (page) {
                this.tableData.current_page=page;
                this.mockTableData1();
            },
            changeSize(size){
                this.tableData.per_page=size;
                this.mockTableData1();
            }

        }
    }
</script>
