import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    logged_in: false,
    user: null
  },
  getters: {
    isLoggedIn: state => {
      return state.logged_in
    },
    getUser: state => {
      return state.user
    }
  },
  mutations: {
    setUser (state, payload) {
      state.user = payload
    },
    login (state) {
      state.logged_in = true
    },
    logout (state) {
      state.logged_in = false
    }
  },
  actions: {
    setUser (context) {
      context.commit('setUser')
    },
    login (context) {
      context.commit('login')
    },
    logout (context) {
      context.commit('logout')
    }
  }
})
