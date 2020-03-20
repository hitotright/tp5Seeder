<template>
  <div class="content">
    <el-tabs
      class="tabs"
      :value="activeTabId"
      @tab-remove="closeTab"
      @tab-click="setActiveTabId(activeTab)"
      type="border-card"
    >
      <el-tab-pane
        class="tabs1"
        v-for="item in tabList"
        :key="item.id"
        :name="item.id"
        :label="item.name"
        :closable="item.closable"
      >
        <component :is="item.component"></component>
      </el-tab-pane>
    </el-tabs>
    <a class="urlList">
      <el-dropdown
        trigger="click"
        @command="setActiveTabId"
      >
        <span class="el-dropdown-link"><i class="el-icon-more"></i></span>
        <el-dropdown-menu slot="dropdown">
          <template v-for="(item, index) in tabList">
            <el-dropdown-item :command="item.id">
              {{item.name}}
            </el-dropdown-item>
          </template>
        </el-dropdown-menu>
      </el-dropdown>
    </a>
    <ul id="rightMenu">
      <li @click="closeTab(activeTab)"><i class="el-icon-close"> 关闭本标签</i></li>
      <li @click="closeOtherTab(activeTab)"><i class="el-icon-close"> 关闭其它标签</i></li>
      <li @click="closeAllTab()"><i class="el-icon-close"> 关闭所有标签</i></li>
    </ul>
  </div>
</template>

<script>
import { mapState, mapMutations, mapGetters } from "vuex";
import Sortable from "sortablejs";
// cnpm install sortablejs --save

export default {
  name: "RightPane",
  data() {
    return {
      activeTab: "home"
    };
  },
  created() {},
  computed: {
    ...mapState("navTabs", ["tabList"]),
    ...mapGetters("navTabs", ["activeTabId"])
  },
  mounted() {
    let el = document.querySelectorAll(".tabs .el-tabs__nav")[0];
    let sortable = Sortable.create(el, {
      animation: 500,
      filter: "#tab-home",
      direction: "horizontal",
      scroll: true,
      scrollSensitivity: 0,
      scrollSpeed: 10
    });

    document.body.ondrop = function(event) {
      event.preventDefault();
      event.stopPropagation();
    };
    document.onclick = function() {
      if (document.getElementById("rightMenu") === null) {
        return;
      } else {
        document.getElementById("rightMenu").style.display = "none";
      }
    };
    let that = this;
    that.calculateTabContentHeight();
    window.addEventListener("resize", function() {
      that.calculateTabContentHeight();
    });
    document
      .querySelector(".el-tabs__header")
      .addEventListener("mouseover", this.mouseoverFunc);
  },
  methods: {
    ...mapMutations("navTabs", [
      "closeTab",
      "closeOtherTab",
      "closeAllTab",
      "setActiveTabId"
    ]),
    mouseoverFunc: function(e) {

      if (e.target.className.indexOf("el-tabs__item") > -1) {
        this.activeTab = e.target.id.substring(4);
        let rightMenu = document.getElementById("rightMenu");
        e.target.oncontextmenu = function(event) {
          let e = event || window.event;
          e.preventDefault();
          rightMenu.style.display = "block";
          rightMenu.style.left = e.pageX - 210 + "px";
        };
      }
    },
    calculateTabContentHeight() {
      // 设置标签页中内容的高度为content的总高度 - 标签页标题栏的高度
      let contentHeight = document.getElementsByClassName("content")[0]
        .offsetHeight;
      document.getElementsByClassName("el-tabs__content")[0].style.height =
        contentHeight - 32 + "px";
    }
  }
};
</script>
<style>
.content .el-tabs__nav-scroll {
  overflow: visible;
  overflow-y: hidden;
  overflow-x: hidden;
  margin-right: 10px;
}
</style>

