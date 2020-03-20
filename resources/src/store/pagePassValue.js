const state = {
    relogin: false,
    // isSlide: false,
}

const mutations = {
    setRelogin(state, bool) {
        state.relogin = bool
    }
    // openWindow(state, call_id) {
    //     state.isSlide = true
    // }
}

export default {
    namespaced: true,
    state,
    mutations
}