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
    login (state, payload) {
      state.user = payload
      state.logged_in = true
    },
    logout (state) {
      state.logged_in = false
    }
  },
  actions: {
    login (context, payload) {
      context.commit('login', payload)
    },
    logout (context) {
      context.commit('logout')
    }
  }
})
