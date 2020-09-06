import Vue from 'vue'
import VueRouter from 'vue-router'
import Doctor_app from './Doctor_app.vue'
import Profile from './doctor_components/profile/Profile'
import Test from './doctor_components/test/Profile'
import VueMask from 'v-mask'

//Global components
import breadcrumb from './doctor_components/shared/Breadcrumb'
import pagination from './doctor_components/shared/Pagination'
Vue.component('breadcrumb', breadcrumb);
Vue.component('pagination', pagination);
import VTooltip from 'v-tooltip'
Vue.use(VTooltip)

Vue.use(VueMask);


//Stock routes
import Stocks from './doctor_components/stocks/Stocks'
import newStock from './doctor_components/stocks/newStock'
import Stock from './doctor_components/stocks/Stock'


//Reservations routes
import Reservations from "./doctor_components/reservations/Reservations"
import appoinment from "./doctor_components/reservations/appointment"

//Blog routes
import Blogs from "./doctor_components/blogs/Blogs"
import newBlog from './doctor_components/blogs/newBlog'
import  Blog from './doctor_components/blogs/Blog'

//Reviews routes
import Reviews from './doctor_components/reviews/Reviews'

//Questions routes
import Questions from './doctor_components/questions/Questions'

//Setting routes
import settingDoctor from './doctor_components/setting/settingDoctor'

//Account routes

// import Account from './doctor_components/account/Account'

//promocode routes

import PromoCodes from "./doctor_components/promocode/PromoCodes"

//Global variables
Vue.prototype.$count = 10

Vue.config.productionTip = false
Vue.use(VueRouter)

const router =  new VueRouter ({
  routes: [
    {
      path: '/',
      component: Profile
    },
    //Test
    {
      path: '/test',
      component: Test
    },
    //Stock routes
    {
      path: '/stocks',
      component: Stocks
    },
    {
      path: '/stocks/add',
      component: newStock
    },
    {
      path: '/stock/:id',
      component: Stock
    },
    {
      path: '/reservations',
      component: Reservations
    },

    {
      path: '/reservations/add',
      component: appoinment
    },
    //Blog routes
    {
      path: '/blogs',
      component: Blogs
    },
    {
      path: '/blogs/add',
      component: newBlog
    },
    {
      path: '/blogs/:id',
      component: Blog
    },
    //Reviews routes
    {
      path: '/review',
      component: Reviews
    },

    //Questions routes
    {
      path: '/questions',
      component: Questions
    },

      //Setting routes
    {
      path: '/setting',
      component: settingDoctor
    },

      //Account routes
    // {
    //   path:'/account',
    //   component: Account
    // },
    //Promocode routes
    {
      path: '/promocode',
      component: PromoCodes
    },
  ]
})

new Vue({
  router,
  render: h => h(Doctor_app),
}).$mount('#doctor_app')


/*export default new Router({  routes: [    {      path: '/',      name: 'home',      component: home    }  ]})*/

/*const routes = [
  { path: '/Admin', component: Admin },
]

export const router = new VueRouter({


  routes
})*/

/*
const app = new Vue({
  router
}).$mount('#app')*/
