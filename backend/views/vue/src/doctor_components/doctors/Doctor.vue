<template>
    <div class="doctor-inner clinic-inner blog" >
        <breadcrumb name="Həkİmlər" icon="fa-user-md" :routes="[{'name':'Həkimlər'}]"></breadcrumb>
        <div class="row">
            <div class="col-md-12">
                <div class="search">
                    <div class="block">
                        <select class="form-control selectpicker" name="money" title="Axtarış növünü seçin">
                            <option value="">Qeydiyyat nömrəsinə görə</option>
                            <option value="">Əraziyə görə</option>
                            <option value="">Kartla ödəniş</option>
                            <option value="">Nəğd ödəniş</option>
                            <option value="">Premium</option>
                        </select>
                        <div class="search-blog">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Axtarış sözü" aria-describedby="basic-addon1">
                            </div>
                            <button type="submit" class="btn-effect">
                                <span><i class="fa fa-search" aria-hidden="true"></i></span>AXTAR
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12" v-if="DoctorData.doctor">
                <div class="row">
                    <div class="col-md-6">
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
                                        <li v-if="DoctorData.workplaces && DoctorData.workplaces.length > 0">
                                            <p>İş yeri</p>
                                            <span>{{DoctorData.workplaces[0].name}}</span>
                                        </li>
                                        <li v-if="DoctorData.workplaces && DoctorData.workplaces.length > 0">
                                            <p>Ünvan</p>
                                            <span>{{DoctorData.workplaces[0].address}}</span>
                                        </li>
                                    </ul>
                                </address>
                                <div class="clinic-edit">
                                    <button class="btn btn-blocked" @click="blockDoctor()">
                                        BLOCK ET
                                    </button>
                                    <router-link :to="{path:'/doctor/setting/' + id}" class="btn btn-edit">
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
                    <div class="col-md-3">
                        <div class="clinic-notify">
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
                    <div class="col-md-3">
                        <div class="balance">
                            <div class="block">
                                <div class="balance-title">
                                    <p>Balans</p>
                                    <p class="cash">300 AZN</p>
                                </div>
                                <div class="balance-counter">
                                    <p>Alınacaq və ya geri qaytarılacaq məbləğ (AZN - ilə)</p>
                                    <div class="counter">
                                        <span class="amount">-300</span>
                                        <button type="button" class="btn">
                                            <span class="decrease"><i class="fa fa-minus" aria-hidden="true"></i></span>5
                                            <span class="increase"><i class="fa fa-plus" aria-hidden="true"></i></span>
                                        </button>
                                    </div>
                                </div>
                                <p>0 TL və ya 0 AZN</p>
                                <div class="input-group">
                                    <textarea id="reason" cols="30" rows="3" placeholder="Səbəb" class="form-control"></textarea>
                                </div>
                                <div class="balance-buttons">
                                    <button type="button" class="btn remove-balance">
                                        BALANSINDAN ÇIX
                                    </button>
                                    <button type="button" class="btn add-balance">
                                        bALANSA ƏLAVƏ ET
                                    </button>
                                </div>
                            </div>
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
                                            <ul v-if="payment_list.length > 0" class="list-inline information-head">
                                                <li>
                                                    <p>Xidmət növü</p>
                                                </li>
                                                <li>
                                                    <p>Ödəniş </p>
                                                </li>
                                                <li>
                                                    <p>Paket </p>
                                                </li>
                                                <li>
                                                    <p>Xidmət</p>
                                                </li>
                                                <li>
                                                    <p>Xidmət tarixi</p>
                                                </li>
                                            </ul>
                                            <ul v-else class="list-inline information-head">
                                                <li>
                                                   <p>Hal hazirda hec ne yoxdur</p>
                                                </li>
                                            </ul>
                                            <ul v-for="payment in payment_list" class="list-inline information-body">
                                                <li>
                                                    <p>{{payment.service_name}}</p>
                                                </li>
                                                <li>
                                                    <p>{{payment.payment_method}}</p>
                                                </li>
                                                <li>
                                                    <p>{{payment.package_name}}</p>
                                                </li>
                                                <li>
                                                    <p>{{payment.price}}</p>
                                                </li>
                                                <li>
                                                    <p>{{payment.services_duration}}</p>
                                                </li>
                                            </ul>
                                            <pagination :paginations="paginations" :callback="getDoctorPayments" v-if="payment_list.length > 0" ></pagination>
                                        </div>
                                        <div v-else-if="tab == 'promotions'" >
                                            <ul v-if="promotion_list.length > 0" class="list-inline information-head">
                                                <li>
                                                    <label class="check-button">
                                                        <input type="checkbox" v-model="selectAll" @change="selectAllPromotions()">
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </li>
                                                <li>
                                                    <p>Təşkilatçı</p>
                                                </li>
                                                <li>
                                                    <p>Başlıq</p>
                                                </li>
                                                <li>
                                                    <p>Qiymət </p>
                                                </li>
                                                <li>
                                                    <p>Başlama tarixi</p>
                                                </li>
                                                <li>
                                                    <p>Bitmə tarixi</p>
                                                </li>
                                            </ul>
                                            <ul v-else class="list-inline information-head" >
                                                <li>
                                                    <p>Hal hazirda hec ne yoxdur</p>
                                                </li>
                                            </ul>
                                            <ul v-for="promotion in promotion_list" class="list-inline information-body">
                                                <li>
                                                    <label class="check-button">
                                                        <input type="checkbox" :checked="selectAll" v-model="promotion.checked">
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </li>
                                                <li>
                                                    <p>{{promotion.organizer}}</p>
                                                </li>
                                                <li>
                                                    <p>{{promotion.headline}}</p>
                                                </li>
                                                <li>
                                                    <p>{{promotion.price}}</p>
                                                </li>
                                                <li>
                                                    <p>{{promotion.date_start}}</p>
                                                </li>
                                                <li>
                                                    <p>{{promotion.date_end}}</p>
                                                </li>
                                            </ul>
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
    import {axios} from '../config/axios';
    import Swal from 'sweetalert2';
    export default {
        name: "Doctor",
        data:function(){
            return {
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
                       // this.experience = parseInt(date.getFullYear()) - parseInt(this.DoctorData.doctor.experience1)
                        //this.experience += ' il';
                        let speciality = [];
                        for (let i in this.DoctorData.specialist){
                            speciality.push(this.DoctorData.specialist[i].name)
                        }

                        this.speciality = speciality.join(', ');
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

                axios.post('/promotions/del',{ids}).then(
                    response=> {
                        this.getDoctorPromotions()
                    });

            },
            blockDoctor:function () {
                axios.post('/doctors/block',{id: this.DoctorData.doctor.id}).then(
                    response=> {
                        /*this.DoctorData.doctor.id=response.data*/
                    });
            },
            activeDoctor:function () {
                axios.post('/doctors/active',{id:this.DoctorData.doctor.id}).then(
                    response=> {
                        // this.DoctorData.doctor.id=response.data
                    }
                )
            },
        }
    }
</script>

<style scoped>

</style>
