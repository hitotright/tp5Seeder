<template>
    <div>
        <h3>CATEGORIES</h3>
        <p>{{categories}}</p>
        <button  v-on:click="changeBash">changeBash</button>
    </div>
</template>

<script>
    export default {
        mounted () {
            this.fetchCategories()
        },
        data () {
            return {
                categories:'categories',
                title:'home'
            }
        },
        methods: {
            fetchCategories() {
                this.$http({url: '/api.php/api/categories', method: 'GET'}).then(function (response) {
                    this.$set(this,'categories', response.data)
                })
            },
            changeBash: function (event) {
               alert('ss');
            }
        }
    }
</script>