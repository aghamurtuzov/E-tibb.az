// // The Vue build version to load with the `import` command
// // (runtime-only or standalone) has been set in webpack.base.conf with an alias.
// import Vue from 'vue'
// import App from './App'
// import router from './router'
//
// Vue.config.productionTip = false
//
// /* eslint-disable no-new */
// new Vue({
//   el: '#app',
//   router,
//   components: { App },
//   template: '<App/>'
// })

import Vue from 'vue'
import VueRouter from 'vue-router'
import App from './App.vue'
import Admin from './components/admin/Admin';
import vSelect from 'vue-select';
import 'vue-select/dist/vue-select.css';

//Global components
import breadcrumb from './components/shared/Breadcrumb'
import pagination from './components/shared/Pagination'
import Loading from './components/Loading';
Vue.component('breadcrumb', breadcrumb);
Vue.component('pagination', pagination);
Vue.component('loading', Loading);
Vue.component('v-select', vSelect);
import VTooltip from 'v-tooltip'
import VueMask from 'v-mask'
Vue.use(VTooltip)
Vue.use(VueMask);

//Global variables
Vue.prototype.$count = 10

//Doctors components
import Doctors from './components/doctors/Doctors'
import newDoctor from './components/doctors/newDoctor'
import Doctor from './components/doctors/Doctor'
import settingDoctor from './components/doctors/settingDoctor'

//Clinics components
import Clinic from './components/clinics/Clinic'


//Enterprises components
import Enterprise from './components/enterprises/Enterprise'
import Enterprises from './components/enterprises/Enterprises'
import newEnterprises from './components/enterprises/newEnterprises'
import settingEnterprises from './components/enterprises/settingEnterprises'


//Pharmacies components
/*import Pharmacies from './components/pharmacies/Pharmacies'
import newPharmacy from './components/pharmacies/newPharmacy'*/
import Pharmacy from './components/pharmacies/Pharmacy'

//Stock routes
import Stocks from './components/stocks/Stocks'
import newStock from './components/stocks/newStock'
import Stock from './components/stocks/Stock'

//Questions routes
import Questions from './components/questions/Questions'
import newQuestion from './components/questions/newQuestion'
import Question from './components/questions/Question'

//Reviews routes
import Reviews from './components/reviews/Reviews'

//Reservations routes
import Reservations from "./components/reservations/Reservations"
/*import newReservation from "./components/reservations/newReservation"
import Reservation from "./components/reservations/Reservation"*/

//Letters routes
import Letters from "./components/letters/Letters"

//Blog routes
import Blogs from "./components/blogs/Blogs"
import newBlog from './components/blogs/newBlog'
import  Blog from './components/blogs/Blog'

//Medical routes
import Medical from "./components/medical/Medical"

//Account routes
import Account from "./components/account/Account"

//StaticPages routes
import StaticPages from "./components/staticpages/StaticPages"
import Co_operation from "./components/staticpages/Co_operation"
import About from "./components/staticpages/About"
import Contact_us from "./components/staticpages/Contact_us"
import Rules from "./components/staticpages/Rules"

//Users routes
import Users from "./components/users/Users"
import User from  "./components/users/User"

//feedback routes
import feedback from "./components/feedback/feedback"
/*import User from  "./components/users/User"*/

//News routes
import NewsList from "./components/news/NewsList"
import NewsEdit from  "./components/news/NewsEdit"
import newNews from  "./components/news/newNews"

//Company routes
import Companies from "./components/companies/Companies"
import Company from "./components/companies/Company"

//Statics routes
import Statics from "./components/statics/Statics"

//promocode routes

import PromoCodes from "./components/promocode/PromoCodes"

//blocked routes

import BlockedPage from "./components/blocked/BlockedPage"

//Sider route

import Slider from "./components/slider/Slider"

//Donate routes
import donateList from './components/donate/donateList'
import donateCreat from './components/donate/donateCreat'
import donateEdit from './components/donate/donateEdit'

//Administrator
import administratorList from './components/administrator/Administrators'
import administratorCreate from './components/administrator/Add'
import administratorEdit from './components/administrator/Edit'

Vue.config.productionTip = false
Vue.use(VueRouter)

const router =  new VueRouter ({
  routes: [
    {
      path: '/',
      component: Admin
    },
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

    //Enterprises
    {
      path: '/enterprises',
      component: Enterprises
    },
    {
      path: '/enterprises/add',
      component: newEnterprises
    },
    {
      path: '/enterprises/setting/:id',
      component: settingEnterprises
    },
    {
      path: '/enterprises/clinics/:id',
      component: Clinic
    },
    {
      path: '/enterprises/pharmacy/:id',
      component: Pharmacy
    },
    {
      path: '/enterprises/:module/:id',
      component: Enterprise
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
    //Questions routes
    {
      path: '/questions',
      component: Questions
    },
    {
      path: '/questions/add',
      component: newQuestion
    },
    {
      path: '/question/:id',
      component: Question
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
    //Letters routes
    {
      path: '/letters',
      component: Letters
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
    //Medical routes
    {
      path: '/medical',
      component: Medical
    },
    //Account routes
    {
      path: '/account',
      component: Account
    },
    //StaticPages routes
    {
      path: '/staticpages',
      component: StaticPages
    },

    {
      path: '/staticpages/Blocked',
      component: BlockedPage
    },

    {
      path: '/staticpages/Co_operation',
      component: Co_operation
    },
    {
      path: '/staticpages/About',
      component: About
    },

    {
      path: '/staticpages/Contact_us',
      component: Contact_us
    },

    {
      path: '/staticpages/Rules',
      component: Rules
    },

    //Users routes
    {
      path: '/users',
      component: Users
    },
    {
      path:'/user/:id',
      component:User
    },

    //Users routes
    {
      path: '/feedback',
      component: feedback
    },
    {
      path:'/user/:id',
      component:User
    },
    //News routes
    {
      path: '/newsList',
      component: NewsList
    },
    {
      path:'/news/add',
      component:newNews
    },
    {
      path:'/news/:id',
      component:NewsEdit
    },
    //Company routes
    {
      path: '/companies',
      component: Companies
    },
    {
      path: '/company/:id',
      component: Company
    },
    //Statics routes
    {
      path: '/statics',
      component: Statics
    },

    //Promocode routes
    {
      path: '/promocode',
      component: PromoCodes
    },

    //Blocked
    {
      path: '/blocked',
      component:BlockedPage
    },

    //Slider
    {
      path: '/slider',
      component:Slider
    },

    //Donate routes
    {
      path: '/donate',
      component: donateList
    },
    {
      path: '/donate/add',
      component: donateCreat
    },
    {
      path: '/donate/:id',
      component: donateEdit
    },
    //Administrator routes
    {
      path: '/administrators',
      component: administratorList
    },
    {
      path: '/administrator/add',
      component: administratorCreate
    },
    {
      path: '/administrator/:id',
      component: administratorEdit
    }
  ]
});



import axios from "axios";
new Vue({
    el: "#app",
    router,
    render: h => h(App),
    data: {
        isLoading: false,
        axiosInterceptor: null
    },
    watch:{
        isLoading: function(newVal, oldVal) {
            this.isLoading = newVal;
        }
    },
    created() {
        this.enableInterceptor();
    },
    methods: {
      enableInterceptor() {
          this.axiosInterceptor = axios.interceptors.request.use((config) => {
              this.isLoading = true;
              return config
          }, (error) => {
              this.isLoading = false;
              return Promise.reject(error)
          })

          axios.interceptors.response.use((response) => {
              this.isLoading = false;
              return response
          }, (error) => {
              this.isLoading = false;
              return Promise.reject(error)
          })
      },

      disableInterceptor() {
          axios.interceptors.request.eject(this.axiosInterceptor)
      },
    },
});


