import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import vuetify from './plugins/vuetify';
import axios from "axios"
import firebase from "firebase"

Vue.config.productionTip = false

axios.defaults.baseURL = "http://localhost:8000/api/v1/"

var firebaseConfig = {
  apiKey: "AIzaSyBzTYxthYFeI6HvlP0c5RWXxdtC0BPwCgI",
  authDomain: "wagproject-8a84e.firebaseapp.com",
  projectId: "wagproject-8a84e",
  storageBucket: "wagproject-8a84e.appspot.com",
  messagingSenderId: "424531402940",
  appId: "1:424531402940:web:ac0c53064822b4fdba70b9"
};
firebase.initializeApp(firebaseConfig)

new Vue({
  router,
  store,
  vuetify,
  render: h => h(App)
}).$mount('#app')
