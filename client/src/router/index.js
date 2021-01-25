import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

const routes = [
  {
    path:'/',
    name:"home",
    component: () => import("@/views/home.vue"),
    meta: {
      auth: false,
      navbar: true
    }
  },
  {
    path:'/checkmeout',
    name:"checkmeoutIndex",
    component: () => import("@/views/checkmeout/index.vue"),
    meta: {
      auth: false,
      navbar: true
    },
    children: [
      {
        path:'/',
        name:"checkmeout",
        component: () => import("@/views/checkmeout/checkmeout.vue"),
        meta: {
          auth: false,
          navbar: true
        },
      }
    ]
  },
  // Admin
  {
    path:'/waglogin',
    name:"login",
    component: () => import("@/views/login.vue"),
    meta: {
      auth: false,
      navbar: true
    }
  },
  {
    path:'/admproduct',
    name:"admProduct",
    component: () => import("@/views/admin/product.vue"),
    meta: {
      auth: true,
      navbar: true
    }
  }
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

export default router
