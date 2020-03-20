<template>
    <el-select clearable  class="width_sp111" v-model="search" filterable collapse-tags placeholder="请选择部门" @change="change" >
        <el-option
                v-html="item.label"
                v-for="item in options"
                :key="item.value"
                :value="item.value"
                :label="item.label_raw">
        </el-option>
    </el-select>
</template>

<script>
    export default {
        props: ['initValue'],
        data() {
            return {
                search: this.initValue,
                options: []
            }
        },
        mounted() {
            this.init()
        },
        watch: {
            initValue: function (data, old) {
                this.initValue = data
                this.search = Number(this.initValue)
            },
        },
        methods: {
            init() {
                let that = this
                this.$ajax.get('admin/Department/index_select').then(function (res) {
                    that.options = res.data
                }).catch(function () {
                    that.$message.error('网络错误,请刷新')
                })
            },
            change() {
                this.$emit('callback',this.search)
            }
        }
    };
</script>
