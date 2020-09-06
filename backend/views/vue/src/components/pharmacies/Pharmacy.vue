<template>
    <div class="pharmacy-inner clinic-inner blog">
        <breadcrumb name="apteklər" icon="fa-plus" :routes="[{'name': 'Apteklər'}]"></breadcrumb>
        <div class="row">
            <div class="col-md-12 clinic-position"  v-if="PharmacyData.enterprise">
                <div class="row relative">
                    <div class="col-lg-6 col-md-12">
                        <div class="clinic-information">
                            <div class="block">
                                <div class="logo-login">
                                    <img v-if="PharmacyData.enterprise.thumb" :src="PharmacyData.enterprise.thumb" alt="klinika" class="img-responsive">
                                    <img v-else src="/admin/cp/img/object-new.png" alt="klinika" class="img-responsive">

                                    <p>Qeydiyyat nömrəsi
                                        <span>{{PharmacyData.enterprise.user_id}}</span>
                                    </p>
                                </div>
                                <div class="clinic-basics">
                                    <p>Adı</p>
                                    <h3>{{PharmacyData.enterprise.name}}</h3>
                                </div>
                                <address>
                                    <ul class="list-unstyled">
                                        <li>
                                            <p>E-mail</p>
                                            <span>xataiestetik@gmail.com</span>
                                        </li>
                                        <li v-if="phones.a1.length > 0">
                                            <p>Mobil telefon</p>
                                            <span >{{ phones.a1.join(", ") }}</span>
                                        </li>
                                        <li v-if="phones.a0.length > 0">
                                            <p>Is telefonu</p>
                                            <span >{{ phones.a0.join(", ") }}</span>
                                        </li>
                                        <li v-if="phones.a2.length > 0">
                                            <p>Whatsapp</p>
                                            <span >{{phones.a2.join(", ")  }}</span>
                                        </li>
                                        <li>
                                            <p>Ünvan</p>
                                            <span>{{pharmacy_address}}</span>
                                        </li>
                                    </ul>
                                </address>
                                <div class="clinic-edit" >
                                    <button class="btn btn-blocked" @click="blockEnterprise()" v-if="PharmacyData.enterprise.status == 1">
                                        BLOCK ET
                                    </button>
                                    <button class="btn btn-confirm" @click="activeEnterprise()" v-else>
                                        Təsdİq et
                                    </button>
                                    <router-link :to="{path:'/enterprises/setting/' + id+'?id=6'}" class="btn btn-edit">
                                        DÜZƏLİŞ et
                                    </router-link>
                                    <!--<button class="btn btn-premium">
                                        Premİum et
                                    </button>-->
                                </div>
                            </div>
                        </div>
                    </div>
                   <div class="col-lg-6 col-md-12">
                       <div class="row">
                           <div class="col-lg-12 col-md-12">
                               <div class="clinic-notify pts-20">
                                   <div class="block pharmacy-edit-block">
                                       <p>
                                           Bİldİrİş göndər
                                       </p>
                                       <div class="input-group">
                                           <textarea name="notify" id="notify" cols="30" rows="12" placeholder="Bildiriş mətni" v-model="messageText" class="form-control"></textarea>
                                       </div>
                                       <div class="btn-textemail">
                                           <button type="submit" class="btn btn-email" @click="sendMessages('/enterprises/mail')">E-mail İlə</button>
                                           <button type="submit" class="btn btn-sms" @click="sendMessages('/enterprises/sms')">SMS İlə</button>
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
                                            <pagination :paginations="paginations" :callback="getEnterprisePayments" v-if="payment_list.length > 0" ></pagination>
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
                                                    <p>Başlıq</p>
                                                </li>
                                                <li>
                                                    <p>Qiymət </p>
                                                </li>
                                                <li>
                                                    <p>Endirim</p>
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
                                                    <p>{{promotion.headline}}</p>
                                                </li>
                                                <li>
                                                    <p>{{promotion.price}}</p>
                                                </li>
                                                <li>
                                                    <p>{{promotion.discount}}</p>
                                                </li>
                                                <li>
                                                    <p>{{promotion.date_start}}</p>
                                                </li>
                                                <li>
                                                    <p>{{promotion.date_end}}</p>
                                                </li>
                                            </ul>
                                            <pagination :paginations="paginations" :callback="getPromotions" v-if="promotion_list.length > 0"></pagination>
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
                <!--<div class="tariffs-stocks">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="clinic-tariffs">
                                <div class="block active">
                                    PAKETLƏR
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="clinic-tariffs">
                                <div class="block">
                                    AKSİYALAR
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="information-blog">
                                <div class="block">
                                    <ul class="list-inline information-head">
                                        <li>
                                            <label class="check-button">
                                                <input type="checkbox" >
                                                <span class="checkmark"></span>
                                            </label>
                                        </li>
                                        <li>
                                            <p>Əlaqələndirici<p>
                                            <p>şəxsin adı, soyadı</p>
                                        </li>
                                        <li>
                                            <p>Əlaqələndirici </p>
                                            <p>şəxs telefonu</p>
                                        </li>
                                        <li>
                                            <p>Əlaqələndirici </p>
                                            <p>şəxs E-mail</p>
                                        </li>
                                        <li>
                                            <p>Ödəniş</p>
                                        </li>
                                        <li>
                                            <p>Paket</p>
                                        </li>
                                        <li>
                                            <p>Xidmət</p>
                                        </li>
                                        <li>
                                            <p>Xidmət tarixi</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="clinic-tariffs-edit">
                                <div class="block">
                                    <button type="button" class="btn">
                                        Sİl
                                    </button>
                                   &lt;!&ndash; <button type="button" class="btn">
                                        YeNİLƏ
                                    </button>&ndash;&gt;
                                </div>
                            </div>
                        </div>
                    </div>
                </div>-->
            </div>
            <!--<div class="col-md-4">
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
    import {sendMessage} from '../config/helper';
    import Swal from 'sweetalert2';
    export default {
        name: "Pharmacy",
        data:function () {
            return {
                id: this.$route.params.id,
                PharmacyData:{},
                phones: {
                    a0: [],
                    a1: [],
                    a2: []
                },
                pharmacy_address:'',
                payment_list:[],
                selectAll:false,
                promotion_list:[],
                paginations: {},
                tab: 'packages',
                isnormalsearch: 0,
                messageText: ''
            }
        },
        mounted:function () {
            this.getPharmacyData();
            this.getPharmacyPayments();
            this.getPromotions();
            console.log('ilahasas 12345')
        },
        methods:{
            getPharmacyData:function () {
                axios.get('/enterprises/info/'+this.id).then(
                    response=> {
                        if (response.data.status !== 200) {
                            return Swal.fire({
                                type: 'error',
                                text: response.data.message()
                            })
                        }
                        this.PharmacyData=response.data.data;

                        if(!this.phone_check) {
                            for(let i of this.PharmacyData.phones){
                                this.phones["a"+i.number_type].push(i.number)
                            }
                            this.phone_check=true;
                        }

                        let pharmacy_address = [];
                        for (let i in this.PharmacyData.addresses){
                            pharmacy_address.push(this.PharmacyData.addresses[i].name)
                        }
                        this.pharmacy_address = pharmacy_address.join(', ');
                    })
            },
            getPharmacyPayments:function (page=1) {
                axios.get('/enterprises/payments/'+this.id+'?count='+this.$count+'&page='+page,this.payment_list).then(
                    response=> {
                        if (response.data.data !== null) {
                            this.payment_list= response.data.data.list;
                            this.paginations = response.data.data.pagination
                        }

                    })
            },
            getPromotions:function (page=1) {
                axios.get('promotions?id='+this.id+'&type=2'+'?count='+this.$count+'&page='+page,this.promotion_list).then(
                    response=> {
                        if (response.data.data !== null) {
                            this.promotion_list=response.data.data.list;
                            this.paginations = response.data.data.pagination
                        }
                    })
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
                            this.getPromotions()
                        });
                }
            },

            blockEnterprise:function () {
                if(this.PharmacyData.enterprise.status =="0") return;
                axios.post('/enterprises/block ',{id: this.PharmacyData.enterprise.id}).then(
                    response=> {
                        this.PharmacyData.enterprise.status= (this.PharmacyData.enterprise.status == "1") ? 1 : 0;
                        this.getPharmacyData()
                    });
            },
            activeEnterprise:function () {
                if(this.PharmacyData.enterprise.status =="1") return;
                axios.post('/enterprises/active',{id:this.PharmacyData.enterprise.id}).then(
                    response=> {
                        this.PharmacyData.enterprise.status= (this.PharmacyData.enterprise.status == "0") ? 0 : 1;
                        this.getPharmacyData()
                    }
                )
            },
            sendMessages(url) {
                let id = this.PharmacyData.enterprise.id;
                let message = this.messageText;
                if (!message) {
                    Swal.fire({
                        title: "Bildiriş!",
                        text: "Mesaj xanası boşdur!",
                        icon: "warning",
                        buttons: true,
                        showCancelButton: true,
                        dangerMode: true,
                    })
                }
                else {
                    sendMessage(url, id, message)
                        .then(data => (data === 'error') ? (  Swal.fire({
                            title: "Xəta!",
                            text: "Mesajınız göndərilə bilmədi!",
                            icon: "warning",
                            buttons: true,
                            showCancelButton: false,
                            dangerMode: true,
                        })) : (Swal.fire({
                            title: "Uğurlu!",
                            text: "Mesajınız göndərildi!",
                            icon: "success",
                            buttons: true,
                            showCancelButton: false,
                            dangerMode: true,
                        })
                            .then((result) => {
                                if (result.value) {
                                    this.messageText = '';
                                }
                            })));
                }

            }
        }
    }
</script>

<style scoped>

</style>
