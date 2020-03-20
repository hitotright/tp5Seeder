const Home = resolve => require(['../views/Home'], resolve)

const state = {
    activeTabId: 'home',
    tabList: [{
        name: '工作台',
        id: 'home',
        tabTableHeight: 0,
        disabled: false,
        closable: false,
        param: {},
        component: Home
    }]
}

const mutations = {

    setActiveTabId(state, id) {
        state.activeTabId = id
    },
    setParam(state, obj) {
        let i = state.tabList.filter(f => f.id === obj.id)[0]
        let k = state.tabList.indexOf(i)
        state.tabList[k].param = obj.value
    },

    setTabTableHeight(state,obj) {
        let i = state.tabList.filter(f => f.id === obj.id)[0]
        let k = state.tabList.indexOf(i)
        state.tabList[k].tabTableHeight = obj.height
    },

    addTab(state, obj) {
        let id = obj.id
        let name = obj.name
        let url = obj.url
        let param = obj.param
        if (id !== 'home') {
            let tab = state.tabList.filter(f => f.id === id)
            if (tab.length > 0) {
                state.tabList.splice(state.tabList.indexOf(tab[0]), 1)
            }
            let component = resolve => require([`../views/${url}`], resolve)
            state.tabList.push({
                id: id,
                name: name,
                disabled: false,
                closable: true,
                param: param,
                tabTableHeight: 0,
                component: component
            })
        }
        state.activeTabId = id
    },
    closeTab(state, id) {
        if (id === 'Home') {
            return
        }
        let tab = state.tabList.filter(f => f.id === id)[0]
        let index = state.tabList.indexOf(tab)
        state.tabList.splice(index, 1)
        state.activeTabId = state.tabList.slice(-1)[0].id
    },
    closeOtherTab(state, id) {
        state.tabList = state.tabList.filter(f => f.id === id || f.id === 'home')
        state.activeTabId = state.tabList[1].id
    },
    closeAllTab(state) {
        state.activeTabId = 'home'
        state.tabList = state.tabList.filter(f => f.id === 'home')
    }
}

const getters = {
    activeTabId: (state) => {
        return state.activeTabId
    },
    tabTableHeight:(state)=>{
        return state.tabList.filter(f => f.id === state.activeTabId)[0].tabTableHeight
    },
    getParam: (state) => (id = null) => {
        id = id === null ? state.activeTabId : id
        return state.tabList.filter(f => f.id === id)[0].param
    }
}


export default {
    namespaced: true,
    state,
    mutations,
    getters
}