import Vue from 'vue'
import VueRouter from 'vue-router'
import App from './Enterprise_app'
import Profile from './enterprise_components/profile/Profile'

//Global components
import breadcrumb from './components/shared/Breadcrumb'
import pagination from './components/shared/Pagination'
Vue.component('breadcrumb', breadcrumb);
Vue.component('pagination', pagination);

//Global variables
Vue.prototype.$count = 10;


//Doctors components
import Doctors from './enterprise_components/doctors/Doctors'
import newDoctor from './enterprise_components/doctors/newDoctor'
import Doctor from './enterprise_components/doctors/Doctor'
import settingDoctor from './enterprise_components/doctors/settingDoctor'


//Stock routes
import Stocks from './enterprise_components/stocks/Stocks'
import newStock from './enterprise_components/stocks/newStock'
import Stock from './enterprise_components/stocks/Stock'

//Reviews routes
import Reviews from './enterprise_components/reviews/Reviews'

//Reservations routes
import Reservations from "./enterprise_components/reservations/Reservations"

//Blog routes
import Blogs from "./enterprise_components/blogs/Blogs"
import newBlog from './enterprise_components/blogs/newBlog'
import  Blog from './enterprise_components/blogs/Blog'

//Account routes
import Account from "./enterprise_components/account/Account"


//Setting routes
import settingEnterprises from './enterprise_components/setting/settingEnterprises'

//News routes
import News from "./enterprise_components/news/News"
import New from  "./enterprise_components/news/New"
import newNews from  "./enterprise_components/news/newNews"

//promocode routes

import PromoCodes from "./enterprise_components/promocode/PromoCodes"



Vue.config.productionTip = false
Vue.use(VueRouter)

const router =  new VueRouter ({
  routes: [
    {
      path: '/',
      component: Profile
    },
    /*{
      path: '/',
      component: Admin
    },*/
      //Doctors routes
    {
      path: '/doctors',
      component: Doctors
    },
    {
      path: '/doctors/add',
      component: newDoctor
    },
    {
      path: '/doctor/:id',
      component: Doctor
    },
    {
      path: '/doctor/setting/:id',
      component: settingDoctor
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
      //Reviews routes
    {
      path: '/review',
      component: Reviews
    },
      //Reservations routes
    {
      path: '/reservations',
      component: Reservations
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
      //Account routes
 /*   {
      path: '/account',
      component: Account
    },*/
      //News routes
    {
      path: '/news',
      component: News
    },
    {
      path:'/news/add',
      component:newNews
    },
    {
      path:'/news/:id',
      component:New
    },
    //Setting routes
    {
      path: '/setting',
      component: settingEnterprises
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
  render: h => h(App),
}).$mount('#enterprise_app')


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
