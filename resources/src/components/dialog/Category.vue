<template>
<!-- dialog选择分类开始 -->
<el-dialog
        width="835px"
        title="选择分类"
        class="class-list-dialog"
        @close="close()"
        :visible.sync="handle">
    <div class="class-list clearfix">
        <ul class="two">
            <li class="all">选择类别</li>
            <template v-for="item in cateList">
                <li :key="item.ca_id" :ca_id="item.ca_id" :title="item.ca_name" :class="[item.ca_name === p_id ? 'selectClassTypeTrue' : 'selectClassTypeFalse']" @click="change(item.ca_id)">
                    {{item.ca_name}}
                    <span class="triggle" v-show="item.ca_name === p_id"></span>
                </li>
            </template>
        </ul>
        <template v-for="items in cateList">
            <ul class="three" v-show="items.ca_id === p_id">
                <li class="all">{{items.ca_name}}</li>
                <template v-for="item in items.child">
                    <li :title="item.ca_name">
                        <span class="left" :key="item.ca_id" :ca_id="item.ca_id" :class="item.ca_id == p_id ? 'selectClassOneTrue' : 'selectClassOneFalse'" @click="click(item)">{{item.ca_name}}</span>
                    </li>
                </template>
            </ul>
        </template>
    </div>
    <div slot="footer">
        <el-button size="mini" type="primary" @click="close()" >确 定</el-button>
    </div>
</el-dialog>
<!-- dialog选择分类结束 -->
</template>
<script>
    export default {
        props: {
            handle: {
                type: Boolean,
                default: false
            },
            ca_id: {
                type:String,
                default:0
            }
        },
        watch: {
            handle: function(data, old) {
                this.handle = data;
            },
        },
        mounted() {
            this.getCategory();
        },
        data() {
            return {
                cateList:{},
                p_id:'',
                row:{}
            };
        },
        methods: {
            change(ca_ca_id){
                this.p_id = ca_ca_id
            },
            getCategory(){
                let that = this
                this.$ajax.get('website/category/getAllCategory').then(function (res) {
                    if(res.data.error === 0){
                        that.cateList = res.data.data
                    }
                })
            },
            click(row){
                this.row = row;
                this.close()
            },
            close() {
                this.$emit("close", this.row);
            }
        }
    };
</script>
<style>
    /* 选择分类 */
    .class-list {

    }
    .class-list > ul {
        display: inline-block;
        font-size: 13px;
        float: left;
    }
    .class-list > ul.one {
        width: 52px;
        color: #fff;
        background-color: #647b8b;
    }
    .class-list > ul.one li {
        border-bottom: 1px solid #fff;
        text-align: center;
    }
    .class-list > ul.one li:last-child {
        border-bottom: 0;
    }
    .class-list > ul.one > li.all {
        height: 30px;
        line-height: 30px;
        background-color: #596168;
    }
    .class-list > ul.one > li.a {
        height: 90px;
        padding: 20px 3px;
    }
    .class-list > ul.one > li.b {
        height: 120px;
        padding: 40px 3px;
    }
    .class-list > ul.one > li.c {
        height: 90px;
        padding: 20px 3px;
    }
    .class-list > ul.two {
        background-color: #eee;
        width: 100px;
    }
    .class-list > ul.two > li {
        cursor: pointer;
        height: 30px;
        line-height: 30px;
        text-align: center;
        border-bottom: 1px solid #ddd;
    }
    .class-list > ul.two > li.all {
        background-color: #98a1a8;
        color: #fff;
    }
    .class-list > ul.three {
        position: relative;
        background-color: #fff;
        width: 650px;
        height: 330px;
        overflow-y: auto;
    }
    .class-list > ul.three > li.all {
        position: sticky;
        top: 0;
        height: 30px;
        line-height: 30px;
        text-align: left;
        padding: 0px 21px;
        background-color: #d7dee4;
        border-bottom: 1px solid #ddd;
        font-weight: bold;
    }
    .class-list > ul.three > li {
        border-bottom: 1px dashed #e4e5e6;
        padding: 5px 15px;

    }
    .class-list > ul.three > li > span.left {
        display: inline-block;
        cursor: pointer;
        height: 100%;
        text-align: center;
        vertical-align: middle;
        color: #2796b1;
        border: 1px solid #fff;
        padding: 0 5px;
        border-radius: 3px;
    }
    .class-list > ul.three > li > ul.right {
        display: inline-block;
        width: 80%;
        vertical-align: middle;
    }

    .class-list > ul.three > li > ul.right > li {
        display: inline-block;
        cursor: pointer;
        width: 100px;
        text-align: center;
        margin: 4px 6px;
        height: 25px;
        line-height: 25px;
        border: 1px solid #d7dee4;
        border-radius: 3px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .selectClassTypeTrue {
        background-color: rgb(138, 174, 183);
        color: #fff;
    }
    .selectClassTypeFalse {
        background-color: #eee;
        color: #606266;
    }
    .selectClassOneTrue {
        border: 1px solid #2796b1!important;
        background-color: #eee;
    }
    .selectClassOneFalse {
        border: 1px solid #eee;
        background-color: #fff;
        color: #2796b1;
    }
    .selectClassTwoTrue {
        border: 1px solid #eee;
        background-color: #6f8ba2;
        color: #fff;
    }
    .selectClassTwoFalse {
        border: 1px solid #eee;
        background-color: #fff;
        color: #606266;
    }
</style>
