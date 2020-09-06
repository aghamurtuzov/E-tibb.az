<template>
    <div class="admin-block pl-0 pr-0">
        <div class="row">
            <div class="col-lg-3 col-md-4 col-sm-6">
                <router-link to="/stocks">
                    <div class="block">
                        <div class="admin-heading">
                            <p>AKSİYALAR</p>
                        </div>
                        <div class="admin-count">
                            <p>{{Dashboard.aksiyalar}}</p>
                            <img src="/admin/cp/img/megaphone.png" alt="megaphone" class="img-responsive">
                        </div>
                        <!--<p class="counting">Son əlavə sayı: <span>2</span></p>-->
                    </div>
                </router-link>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <router-link to="/reservations">
                    <div class="block">
                        <div class="admin-heading">
                            <p>REZERVASİYA</p>
                            <!--<span class="badge">01</span>-->
                        </div>
                        <div class="admin-count">
                            <p>{{Dashboard.rezervasiya}}</p>
                            <img src="/admin/cp/img/medical-history.png" alt="medical-history" class="img-responsive">
                        </div>
                        <!--<p class="counting">Son əlavə sayı: <span>2</span></p>-->
                    </div>
                </router-link>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <router-link to="/blogs">
                    <div class="block">
                        <div class="admin-heading">
                            <p>BLOQLAR</p>
                        </div>
                        <div class="admin-count">
                            <p>{{Dashboard.bloqlar}}</p>
                            <img src="/admin/cp/img/blog.png" alt="blog" class="img-responsive">
                        </div>
                        <!--<p class="counting">Son əlavə sayı: <span>1</span></p>-->
                    </div>
                </router-link>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <router-link to="/review">
                    <div class="block">
                        <div class="admin-heading">
                            <p>RƏYLƏR</p>
                            <!--<span class="badge">01</span>-->
                        </div>
                        <div class="admin-count">
                            <p>{{Dashboard.reyler}}</p>
                            <img src="/admin/cp/img/review.png" alt="review" class="img-responsive">
                        </div>
                        <!--<p class="counting">Son əlavə sayı: <span>6</span></p>-->
                    </div>
                </router-link>
            </div>
            <!--<div class="col-lg-3 col-md-4">
                <div class="block">
                    <div class="admin-heading">
                        <p>Ümumİ balans</p>
                    </div>
                    <div class="admin-count">
                        <p>{{Dashboard.balans}} <sup>M</sup></p>
                        <img src="/admin/cp/img/wallet.png" alt="letters" class="img-responsive">
                    </div>
                    &lt;!&ndash;<p class="counting">Son əlavə sayı: <span>1</span></p>&ndash;&gt;
                </div>
            </div>-->
            <div class="col-lg-3 col-md-4 col-sm-6">
                <router-link to="/questions">
                    <div class="block">
                        <div class="admin-heading">
                            <p>Sual-Cavab</p>
                            <!--<span class="badge">01</span>-->
                        </div>
                        <div class="admin-count">
                            <p>{{Dashboard.suallar}}</p>
                            <img src="/admin/cp/img/quiz.png" alt="quiz-img" class="img-responsive">
                        </div>
                        <!--<p class="counting">Son əlavə sayı: <span>12</span></p>-->
                    </div>
                </router-link>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 col-sm-6">
                <div class="datepicker-block">
                    <div class="block" :class="dateIsSelected ? 'showDatalist' : '' ">
                        <datepicker v-if="!dateIsSelected" :inline="true" @input="selectedInputDate()" v-model="date" :language="az"></datepicker>
                        <div class="date-inner" v-else>
                            <div class="view-dates">
                                <div class="date-heading">
                                    <p>{{az_date}}</p>
                                    <button class="btn transparent" @click="dateIsSelected=false"><i class="fa fa-times" aria-hidden="true"></i></button>
                                </div>
                            </div>
                            <div class="date-content">
                                <ul>
                                    <li v-for="item in user_list">
                                        <span class="hours">{{item.time}}</span>
                                        <p v-if="item.user">{{item.user}} <i class="fa fa-eye" aria-hidden="true"></i></p>
                                        <button v-if="item.deleted==1" class="active-date" @click="activeDate(item)">Aktive et</button>
                                        <button v-else-if="item.deleted==0" class="block-date" @click="blockDate(item)">Blok et</button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
                        if (response.data.data !== null) {
                          this.user_list=response.data.data.list;
                          if(this.user_list.length > 0) this.dateIsSelected=true;
                        } 
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

<style scoped>

</style>
