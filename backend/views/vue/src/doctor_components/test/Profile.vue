<template>
  <div class="admin-block">
    <p>test</p>
  </div>
</template>

<script>
  import {axios} from "../config/doctor_axios";
  import Datepicker from 'vuejs-datepicker';
  import {az} from 'vuejs-datepicker/dist/locale'

  export default {
    name: "Profile",
    components:{
      Datepicker
    },
    data:function () {
      return {
        az:az,
        /*id: this.$route.params.id,*/
        Dashboard:{},
        date: new Date(),
        dateIsSelected:false,
        months: ['Yanvar', 'Fevral','Mart','Aprel','May','İyun','İyul','Avqust','Senytabr','Oktyabr','Noyabr','Dekabr'],
        az_date:'',
        user_list:[]

      }
    },
    mounted() {
      this.getDashboard()
    },
    methods:{
      getDashboard(){
        axios.get('/dashboard').then(
          response=> {
            this.Dashboard=response.data.data
          }
        )
      },/*
            selectedDate(){
                console.log(this.date)
            },*/
      selectedInputDate(){
        this.az_date=this.date.getDate() + ' ' + this.months[this.date.getMonth()];
        let dateformat = this.date.getFullYear() + '-' + ('0' + (1+this.date.getMonth())).slice(-2) + '-' + ('0' + this.date.getDate()).slice(-2)
        axios.get('/appointment/work-day?day='+dateformat).then(
          response=> {
            this.user_list=response.data.data.list;
            if(this.user_list.length > 0) this.dateIsSelected=true;
          }
        )
      },
      activeDate(item){
        let dateformat = this.date.getFullYear() + '-' + ('0' + (1+this.date.getMonth())).slice(-2) + '-' + ('0' + this.date.getDate()).slice(-2)
        let formdata = new FormData();
        formdata.append('date',dateformat);
        formdata.append('time',item.time);
        axios.post('/appointment/work-time-active',formdata).then(
          response=> {
            item.deleted=(item.deleted==0) ? 0 : 1;
            this.selectedInputDate()
          }
        )
      },
      blockDate(item){
        let dateformat = this.date.getFullYear() + '-' + ('0' + (1+this.date.getMonth())).slice(-2) + '-' + ('0' + this.date.getDate()).slice(-2)
        let formdata = new FormData();
        formdata.append('date',dateformat);
        formdata.append('time',item.time);
        axios.post('/appointment/work-time-block',formdata).then(
          response=> {
            item.deleted=(item.deleted==1) ? 1 : 0;
            this.selectedInputDate()
          }
        )
      }
    }
  }
</script>


