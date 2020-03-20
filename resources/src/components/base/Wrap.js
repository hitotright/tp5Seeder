import Vue from 'vue'
import {mapGetters, mapMutations} from 'vuex'

let PageWrap = Vue.component('PageWrap', {
    template: '<div css="pageWrap"><slot> </slot> </div>'
})

let SearchWrap = Vue.component('SearchWrap', {
    props: {
        borderColor: {
            type: String,
            default: 'lightblue'
        }
    },
    template: '<div class="searchWrap" :style="{borderColor: borderColor}"> <slot> </slot> </div>'
})

let TableWrap = Vue.component('TableWrap', {
    template: '<div class="tableWrap" :id="activeTabId"> <slot> </slot> </div>',
    computed: {
        ...mapGetters('navTabs', [
            'activeTabId', 'tabTableHeight'
        ])
    },
    mounted() {
        this.scrollPaneFunc()
        this.scrollTableFunc()
    },
    updated() {
        if (this.tabTableHeight === 0) {
            this.$nextTick(function () {
                this.setTabTableHeight({id:this.activeTabId,height:this.calculateTabTableHeight()})
            })
        }
        // console.log('up_tabTableHeight:'+this.tabTableHeight)
        // let that = this
        // window.onresize = function() {
        //     that.setTabTableHeight(that.calculateTabTableHeight())
        // }
    },
    methods: {
        ...mapMutations('navTabs', ['setTabTableHeight']),
        calculateTabTableHeight() {
            let currentTab = document.getElementById('pane-' + this.activeTabId) // 当前标签页
            // console.log('activeTabId:'+this.activeTabId)
            let contentHeight = document.getElementsByClassName('el-tabs__content')[0].offsetHeight // 当前标签页内容的高度
            // console.log('contentHeight:'+contentHeight)
            let topHeight = currentTab.getElementsByClassName('searchWrap').length > 0 ? currentTab.getElementsByClassName('searchWrap')[0].offsetHeight : 0 // 当前标签页表格上面搜索栏的高度
            // console.log('topHeight:'+topHeight)
            let widthTableHeaderHeight = currentTab.getElementsByClassName('withTableHeader').length > 0 ? currentTab.getElementsByClassName('withTableHeader')[0].offsetHeight : 0 // 当前标签页表格上面按钮栏的高度
            // console.log('widthTableHeaderHeight'+widthTableHeaderHeight)
            let tableHeaderHeight = currentTab.getElementsByClassName('el-table__header-wrapper').length > 0 ? currentTab.getElementsByClassName('el-table__header-wrapper')[0].offsetHeight : 0 // 当前标签页表格头的高度
            // console.log('tableHeaderWrapper'+tableHeaderHeight)
            let bottomHeight = currentTab.getElementsByClassName('paginationWrap').length >0? currentTab.getElementsByClassName('paginationWrap')[0].offsetHeight : 0// 当前标签页表格下面分页栏的高度
            // console.log('bottomHeight:'+bottomHeight)
let elheader = currentTab.getElementsByClassName('el-header').length > 0 ?currentTab.getElementsByClassName('el-header')[0].offsetHeight : 0
            let tabTableHeight = contentHeight - topHeight - bottomHeight - widthTableHeaderHeight -tableHeaderHeight-elheader + 20
            // console.log('tabTableHeight:'+tabTableHeight)
            return contentHeight>tabTableHeight?tabTableHeight:0
        },
        scrollTableFunc() {
            this.$bus.$on('triggerTable', (val) => {
                if (val) {
                    let currentTab = document.getElementById('pane-' + this.activeTabId)
                    let scrollTable = currentTab.querySelector('.el-table__body-wrapper')
                    scrollTable.scrollTop = 0
                }
            })
        },
        scrollPaneFunc() {
            let that = this
            let scrollPane = document.getElementsByClassName('el-tabs__content')[0]
            scrollPane.onscroll = function () {
                let paneTop = scrollPane.scrollTop
                let currentPane = document.getElementById('pane-' + that.activeTabId)
                if(currentPane.length!==0){
                    if (document.getElementById(that.activeTabId) !== null) {
                        let table = currentPane.getElementsByClassName('tableWrap')[0]
                        let currentTables = currentPane.getElementsByClassName('el-table')
                        for (let i = 0; i < currentTables.length; i++) {
                            if (currentTables[i].style.display !== 'none') {
                                let tableHeader = currentTables[i].getElementsByClassName('el-table__header-wrapper')[0]
                                if (table.offsetTop > 300 && paneTop > table.offsetTop - 60) {
                                    tableHeader.style.position = 'fixed'
                                    tableHeader.style.top = '66px'
                                    tableHeader.style.zIndex = 1
                                } else {
                                    tableHeader.removeAttribute('style')
                                }
                            }
                        }
                    }
                }
            }
        }
    }
})

let Pagination = Vue.component('Pagination', {
    template: '<div class="paginationWrap"> <el-pagination style="bottom:0px" :layout="layout"' +
    ' @size-change="handleSizeChange" @current-change="handleCurrentChange" :current-page="paginationData.currentPage"' +
    ' :page-size="paginationData.pageSize" :total="paginationData.total"> </el-pagination> </div>',
    props: {
        data: {
            type: Object,
            default: function () {
                return {currentPage: 1, pageSize: 50, total: 0}
            }
        },
        layout: {
            type: String,
            default: function () {
                return 'total, sizes, prev, pager, next, jumper'
            }
        }
    },
    watch: {
        data: function (data, old) {
            this.paginationData = data
        }
    },
    data() {
        return {
            paginationData: this.data
        }
    },
    methods: {
        handleSizeChange(val) {
            this.paginationData.currentPage = 1
            this.paginationData.pageSize = val
            this.$emit('submit', this.paginationData)
        },
        handleCurrentChange(val) {
            this.paginationData.currentPage = val
            this.$emit('submit', this.paginationData)
        }
    }
})

export default {PageWrap, SearchWrap, TableWrap, Pagination}
