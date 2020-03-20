<template>
    <el-select clearable class="width_sp111" v-model="search" filterable placeholder="请选择职位"  @change="change">
        <el-option
                v-for="item in options"
                :key="item.value"
                :label="item.label"
                :value="item.value"
               >
        </el-option>
    </el-select>
</template>

<script>
    export default {
        props: ['initValue'],
        data() {
            return {
                options: [],
                search: this.initValue
            }
        },
        mounted() {
            this.init()
        },
        watch: {
            initValue: function (data, old) {
                this.initValue = data
                this.search = this.initValue
            },
        },
        methods: {
            init() {
                let that = this;
                this.$ajax.get('admin/User/ps_select').then(function (res) {
                    that.options = res.data.data
                }).catch(function () {
                    that.$message.error('网络错误,请刷新')
                })
            },
            change() {
                this.$emit("callback", this.search)
            }
        }
    };
</script>
