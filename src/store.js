import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex);

export default new Vuex.Store({
  state: {
    sideBar: false
  },
  mutations: {
    showHideSideBar(state) {
      state.sideBar = !state.sideBar;
    }
  },
  actions: {

  }
})
