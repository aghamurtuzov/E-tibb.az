<template>
    <div class="doctor-inner clinic-inner blog" >
        <breadcrumb name="Həkİmlər" icon="fa-user-md" :routes="[{'name':'Həkimlər'}]"></breadcrumb>
        <div class="row">
            <div class="col-md-12">
              <Search :isnormalsearch="isnormalsearch" v-on:SearchRequested = "search" />
            </div>
            <div class="col-md-12 clinic-position" v-if="DoctorData.doctor">
                <div class="row relative">
                    <div class="col-lg-6 col-md-12">
                        <div class="clinic-information">
                            <div class="block">
                                <div class="logo-login">
                                    <div class="clinic-basics">
                                        <img :src="DoctorData.doctor.thumb" alt="doctor-inner">
                                        <div class="name">
                                            <p>Adı</p>
                                            <h3>{{DoctorData.doctor.name}}</h3>
                                        </div>
                                    </div>
                                    <p>Qeydiyyat nömrəsi
                                        <span>{{DoctorData.doctor.user_id}}</span>
                                    </p>
                                </div>
                                <address>
                                    <ul class="list-unstyled">
                                        <li v-if="DoctorData.specialist && DoctorData.specialist.length > 0">
                                            <p>İxtisası</p>
                                            <span> {{ speciality }} </span>
                                        </li>
                                        <li>
                                            <p>İş təcrübəsi</p>
                                            <span>{{DoctorData.doctor.experience1}} il</span>
                                        </li>
                                        <li>
                                            <p>E-mail</p>
                                            <span>{{DoctorData.doctor.email}}</span>
                                        </li>
                                        <li>
                                            <p>Mobil telefon</p>
                                            <span v-if="DoctorData.phones && DoctorData.phones[1]">{{DoctorData.phones[1].number}}</span>
                                        </li>
                                        <li>
                                            <p>İş yeri</p>
                                            <span>{{workplaces_names}}</span>
                                        </li>
                                        <li>
                                            <p>Ünvan</p>
                                            <span>{{workplaces_addresses}}</span>
                                        </li>
                                    </ul>
                                </address>
                                <div class="clinic-edit">
                                    <button class="btn btn-blocked" @click="blockDoctor()">
                                        BLOCK ET
                                    </button>
                                    <router-link :to="{path:'/doctor/setting/' + id}" class="btn btn-edit mr-10">
                                        DÜZƏLİŞ et
                                    </router-link>
                                    <!--<button class="btn btn-premium">
                                        Premİum et
                                    </button>-->
                                    <button class="btn btn-confirm" @click="activeDoctor()">
                                        Təsdİq et
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12">
                        <div class="row">
                            <div class="col-lg-6 col-md-12">
                                <div class="clinic-notify pts-20">
                                    <div class="block">
                                        <p>
                                            Bİldİrİş göndər
                                        </p>
                                        <div class="input-group">
                                            <textarea name="notify" id="notify" cols="30" rows="11" placeholder="Bildiriş mətni"  class="form-control"></textarea>
                                        </div>
                                        <div class="btn-textemail">
                                            <button type="submit" class="btn btn-email">E-mail İlə</button>
                                            <button type="submit" class="btn btn-sms">SMS İlə</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12">
                                <div class="datepicker-block pts-20">
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
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--<div class="col-lg-6 col-md-6">
                                <div class="balance">
                                    <div class="block">
                                        <div class="balance-title">
                                            <p>Balans</p>
                                            <p class="cash">300 GÜN</p>
                                        </div>
                                        <div class="balance-counter">
                                            &lt;!&ndash;<p>Alınacaq və ya geri qaytarılacaq məbləğ (AZN - ilə)</p>&ndash;&gt;
                                            <div class="counter">
                                                <input class="amount text-center">
                                                &lt;!&ndash;<button type="button" class="btn">
                                                    <span class="decrease"><i class="fa fa-minus" aria-hidden="true"></i></span>5
                                                    <span class="increase"><i class="fa fa-plus" aria-hidden="true"></i></span>
                                                </button>&ndash;&gt;
                                            </div>
                                        </div>
                                        &lt;!&ndash; <p> 0 AZN</p>&ndash;&gt;
                                        <div class="input-group">
                                            <textarea id="reason" cols="30" rows="5" placeholder="Səbəb" class="form-control"></textarea>
                                        </div>
                                        <div class="balance-buttons">
                                            <button type="button" class="btn remove-balance">
                                                Artır
                                            </button>
                                            <button type="button" class="btn add-balance">
                                                Azalt
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>-->
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="tariffs-stocks">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="clinic-tariffs">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li role="presentation" class="active block">
                                        <a @click="tab = 'packages'" aria-controls="payments" role="tab" data-toggle="tab">PAKETLƏR</a>
                                    </li>
                                    <li role="presentation" class="block">
                                        <a @click="tab = 'promotions'" aria-controls="promotions" role="tab" data-toggle="tab">AKSİYALAR</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="information-blog">
                                <div class="block">
                                    <div class="tab-content">
                                        <div v-if="tab == 'packages'" >
                                            <table class="table table-hover table-striped" >
                                                <thead  v-if="payment_list.length > 0">
                                                <tr>
                                                    <th>Xidmət növü</th>
                                                    <th>Ödəniş</th>
                                                    <th>Paket</th>
                                                    <th>Xidmət</th>
                                                    <th>Xidmət tarixi</th>
                                                </tr>
                                                </thead>
                                                <thead v-else class="thead-bottom">
                                                <tr>
                                                    <th>Hal hazirda hec ne yoxdur</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr v-for="payment in payment_list">
                                                    <td>
                                                        {{payment.service_name}}
                                                    </td>
                                                    <td>
                                                        {{payment.payment_method}}
                                                    </td>
                                                    <td>
                                                        {{payment.package_name}}
                                                    </td>
                                                    <td>
                                                        {{payment.price}}
                                                    </td>
                                                    <td>
                                                        {{payment.services_duration}}
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            <pagination :paginations="paginations" :callback="getDoctorPayments" v-if="payment_list.length > 0" ></pagination>
                                        </div>
                                        <div v-else-if="tab == 'promotions'" >
                                            <table class="table table-hover table-striped" >
                                                <thead v-if="promotion_list.length > 0">
                                                <tr>
                                                    <th>
                                                        <label class="check-button">
                                                            <input type="checkbox" v-model="selectAll" @change="selectAllPromotions()">
                                                            <span class="checkmark"></span>
                                                        </label>
                                                    </th>
                                                    <th>Foto</th>
                                                    <th>Başlıq</th>
                                                    <th>Qiymət</th>
                                                    <th>Başlama tarixi</th>
                                                    <th>Bitmə tarixi</th>
                                                </tr>
                                                </thead>
                                                <thead v-else class="thead-bottom">
                                                <tr>
                                                    <th>Hal hazirda hec ne yoxdur</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr v-for="promotion in promotion_list" >
                                                    <td>
                                                        <label class="check-button">
                                                            <input type="checkbox" :checked="selectAll" v-model="promotion.checked">
                                                            <span class="checkmark"></span>
                                                        </label>
                                                    </td>
                                                    <td>
                                                        <img :src="promotion.thumb" alt="promotion_img">
                                                    </td>
                                                    <td>
                                                        {{promotion.headline}}
                                                    </td>
                                                    <td>
                                                        {{promotion.price}}
                                                    </td>
                                                    <td>
                                                        {{promotion.date_start}}
                                                    </td>
                                                    <td>
                                                        {{promotion.date_end}}
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            <pagination :paginations="paginations" :callback="getDoctorPromotions" v-if="promotion_list.length > 0"></pagination>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="clinic-tariffs-edit" :class="{ 'removeit': tab == 'packages' }" v-if="promotion_list.length > 0">
                                <div class="block">
                                    <button type="button" class="btn" @click="deletePromotion()">
                                        Sİl
                                    </button>
                                    <!--<button type="button" class="btn">
                                        YeNİLƏ
                                    </button>-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="col-md-4">
                 <div class="datepicker-block">
                     <div class="block">
                         <div class="get-date">
                             <div id="datepicker" data-date=" "></div>
                             <input type="hidden" id="my_hidden_input">
                         </div>
                     </div>
                 </div>
             </div>-->
        </div>
    </div>
</template>

<script>
    import {axios} from '../config/enterprise_axios';
    import Swal from 'sweetalert2';
    import Datepicker from 'vuejs-datepicker';
    import {az} from 'vuejs-datepicker/dist/locale';
    import Search from '../Search';
    export default {
        name: "Doctor",
        components:{
            Datepicker, Search
        },
        data:function(){
            return {
                az:az,
                id: this.$route.params.id,
                DoctorData: {},
                speciality: '',
                experience: '',
                payment_list:[],
                promotion_list:[],
                tab: 'packages',
                selectAll:false,
                removeit:'removeit',
                paginations: {},
                workplaces_names:'',
                workplaces_addresses:'',
                date: new Date(),
                dateIsSelected:false,
                months: ['Yanvar', 'Fevral','Mart','Aprel','May','İyun','İyul','Avqust','Senytabr','Oktyabr','Noyabr','Dekabr'],
                az_date:'',
                isnormalsearch: 0
            }
        },

        mounted() {
            this.getDoctorData();
            this.getDoctorPayments();
            this.getDoctorPromotions();
        },
        methods: {
            getDoctorData:function () {
                axios.get('/doctors/info/'+this.id).then(
                    response=> {
                        if (response.data.status !== 200) {
                            return Swal.fire({
                                type: 'error',
                                text: response.data.message()
                            })
                        }
                        //let date = new Date();
                        this.DoctorData=response.data.data;
                        // this.experience = parseInt(date.getFullYear()) - parseInt(this.DoctorData.setting.experience1)
                        //this.experience += ' il';
                        let speciality = [];
                        for (let i in this.DoctorData.specialist){
                            speciality.push(this.DoctorData.specialist[i].name)
                        }

                        this.speciality = speciality.join(', ');
                        let workplaces_names=[];
                        for(let i in this.DoctorData.workplaces){
                            workplaces_names.push(this.DoctorData.workplaces[i].name)
                        }
                        this.workplaces_names= workplaces_names.join(', ')

                        let workplaces_addresses=[];
                        for(let i in this.DoctorData.workplaces){
                            workplaces_addresses.push(this.DoctorData.workplaces[i].address)
                        }
                        this.workplaces_addresses= workplaces_addresses.join(', ')
                    })
            },
            getDoctorPayments:function (page=1) {
                axios.get('/doctors/payments/'+this.id+'?count='+this.$count+'&page='+page,this.payment_list).then(
                    response=> {
                        if (response.data.data !== null) {
                            this.payment_list= response.data.data.list;
                            this.paginations = response.data.data.pagination
                        }

                    })
            },
            getDoctorPromotions:function (page=1) {
                axios.get('promotions?id='+this.id+'&type=1'+'?count='+this.$count+'&page='+page,this.promotion_list).then(
                    response=> {
                        if (response.data.data !== null) {
                            this.promotion_list=response.data.data.list;
                            this.paginations = response.data.data.pagination
                        }else{
                            this.promotion_list = []
                        }
                    })
            },
            selectAllPromotions(){
                for(let i in this.promotion_list){
                    this.promotion_list[i].checked = this.selectAll
                }
            },
            deletePromotion:function () {
                let ids = [];
                for(let i in this.promotion_list){
                    if(this.promotion_list[i].checked) {
                        ids.push(this.promotion_list[i].id)
                    }
                }

                if(ids.length >0){
                    axios.post('/promotions/del',{ids}).then(
                        response=> {
                            this.getStocks()
                        });
                }

            },
            blockDoctor:function () {
                if(this.DoctorData.doctor.status =="0") return;
                axios.post('/doctors/block',{id: this.DoctorData.doctor.id}).then(
                    response=> {
                        this.getDoctorData()
                    });
            },
            activeDoctor:function () {
                if(this.DoctorData.doctor.status =="1") return;
                axios.post('/doctors/active',{id:this.DoctorData.doctor.id}).then(
                    response=> {
                        this.getDoctorData()
                    }
                )
            },
            selectedInputDate(){
                this.az_date=this.date.getDate() + ' ' + this.months[this.date.getMonth()];
                let dateformat = this.date.getFullYear() + '-' + ('0' + (1+this.date.getMonth())).slice(-2) + '-' + ('0' + this.date.getDate()).slice(-2)
                axios.get('/appointment/work-day?day='+dateformat + '&doctor_id='+this.id).then(
                    response=> {
                        this.user_list= response.data.data !== null ? response.data.data.list : [];
                        if(this.user_list.length > 0) this.dateIsSelected=true;
                    }
                )
            },
            search(query) {
              console.log(query);
            }
        }
    }
</script>

<style scoped>
  .mr-10 {
    margin-right: 10px !important;
  }
</style>
