import Vue from 'vue'
import Vuex from 'vuex'
import createPersistedState from "vuex-persistedstate";
import user from "./modules/user"
import product from "./modules/product"
import search from "./modules/search"

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
  },
  mutations: {
  },
  actions: {
  },
  modules: {
    user,
    product,
    search
  },
  plugins: [createPersistedState()]
})
