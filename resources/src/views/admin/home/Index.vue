<template>
    <section class="page_box">
        <header-components></header-components>
        <el-row class="big_box_card">
            <el-col  style="width: 167px;">
                <sidebar-components></sidebar-components>
            </el-col>
            <el-col >
                <content-components></content-components>
            </el-col>
        </el-row>
    </section>
</template>

<script>
  import Header from './Header.vue'
  import Sidebar from './Sidebar.vue'
  import Content from './Content.vue'

  export default {
    components: {
      "header-components":Header,
      "sidebar-components":Sidebar,
      "content-components":Content,
    }
  }
</script>
