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
    path:'/about',
    name:"about",
    component: () => import("@/views/about.vue"),
    meta: {
      auth: false,
      navbar: true
    }
  },
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
    path:'/selfdiscovery',
    name:"selfdiscovery",
    component: () => import("@/views/selfdiscovery.vue"),
    meta: {
      auth: false,
      navbar: true
    }
  },
  {
    path:'/selfdiscovery/:id',
    name:"selfdiscoverydetail",
    component: () => import("@/views/selfdiscoverydetail.vue"),
    meta: {
      auth: false,
      navbar: true
    }
  },
  {
    path:'/glammersay',
    name:"glammersay",
    component: () => import("@/views/glammersay.vue"),
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
      },
      {
        path:':id',
        name:"checkmeoutdetail",
        component: () => import("@/views/checkmeout/detail.vue"),
        meta: {
          auth: false,
          navbar: true
        },
      }
    ]
  },
  {
    path:'/checkout',
    name:"checkout",
    component: () => import("@/views/checkout.vue"),
    meta: {
      auth: false,
      navbar: true
    }
  },
  {
    path:'/checkout/billing',
    name:"checkoutbilling",
    component: () => import("@/views/billing.vue"),
    meta: {
      auth: false,
      navbar: true
    }
  },
  {
    path:'/payment/:id',
    name:"oayment",
    component: () => import("@/views/payment.vue"),
    meta: {
      auth: false,
      navbar: true
    }
  },
  // Admin
  {
    path:'/waglogin',
    name:"login",
    component: () => import("@/views/login.vue"),
    meta: {
      auth: false,
      navbar: false
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
  },
  {
    path:'/admorder',
    name:"admOrder",
    component: () => import("@/views/admin/order.vue"),
    meta: {
      auth: true,
      navbar: true
    }
  },
  {
    path:'/admselfdiscovery',
    name:"admSelfDiscovery",
    component: () => import("@/views/admin/selfdiscovery.vue"),
    meta: {
      auth: true,
      navbar: true
    }
  },
  {
    path:'/admpromo',
    name:"admPromo",
    component: () => import("@/views/admin/promo.vue"),
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
