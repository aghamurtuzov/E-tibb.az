<template>
    <div class="reservation blog">
        <breadcrumb name="Promokodlar" icon="fa-ticket" :routes="[{'name':'Promokodlar'}]"></breadcrumb>
        <div class="row">
            <Search :isnormalsearch="isnormalsearch" :errors_promocode="errors_promocode" v-on:SearchRequestedPromocode = "getPromoCodes" />
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="search mt-20">
                    <div class="block">
                        <div class="search-blog promo">
                            <div class="input-group mbx-10">
                                <input type="text" class="form-control" placeholder="Promo kodu daxil edin" aria-describedby="basic-addon1" v-model="promoCode">
                            </div>
                            <button type="submit" class="btn-effect"  @click="checkPromoCode()">YOXLA</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="information-blog">
                    <div class="block">
                        <table class="table table-hover table-striped data-null">
                            <thead v-if="promocodes.length > 0">
                                <tr>
                                    <th>Promo kod</th>
                                    <th>Aksiya</th>
                                    <th>Təşkilatçı</th>
                                    <th>İstifadəçi</th>
                                    <th>İstifadə tarixi</th>
                                </tr>
                            </thead>
                            <thead v-else class="thead-bottom">
                                <tr>
                                    <th>Məlumat yoxdur</th>
                                </tr>
                            </thead>
                            <tbody>
                            <tr v-for="promocode in promocodes">
                                <td>{{promocode.promocode}}</td>
                                <td>{{promocode.promotion_headline}}</td>
                                <td>{{promocode.byPromotion}}</td>
                                <td>
                                    {{promocode.used_userName}}
<!--                                    <router-link class="doctor__name" target="_blank" :to="{path: 'users/'+review.connect_id}">-->
<!--                                        {{promocode.used_userName}}-->
<!--                                    </router-link>-->
                                </td>
                                <td>{{formatDatetime(promocode.created_at)}}</td>
                            </tr>
                            </tbody>
                        </table>
                        <pagination v-if="paginations" :paginations="paginations" :callback="getPromoCodes"></pagination>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-block" v-if="promoCodeExists">
            <div class="modal fade in" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="promoCodeExists=false">
                            <span aria-hidden="true">
                                <img src="/admin/cp/img/popup-close.png" alt="popup-close">
                            </span>
                            </button>
                            <h4 class="modal-title" v-if="userExists">İstifadəçi tapıldı!</h4>
                            <h4 class="modal-title unknown-title" v-else>İstifadəçi tapılmadı!</h4>
                        </div>
                        <div class="modal-body">
                            <div class="left-body">
                                <div class="modal-inn">
                                    <span>Adı, Soyadı</span>
                                    <p v-if="userExists">{{name}}</p>
                                    <p v-else>Naməlum Şəxs</p>
                                </div>
                                <div class="modal-proma">
                                    <div class="modal-inn" v-if="userExists">
                                        <span>Qeydiyyat nömrəsi</span>
                                        <p>{{user_id}}</p>
                                    </div>
                                    <div class="modal-inn">
                                        <span>Promo kodu</span>
                                        <p>{{promoCode}}</p>
                                    </div>
                                </div>
                            </div>
                            <img src="/admin/cp/img/promo-active.png" alt="promo-active" v-if="userExists">
                            <img src="/admin/cp/img/promo-unknown.png" alt="promo-unknown" v-else>
                        </div>
                        <div class="modal-footer" v-if="userExists">
                            <button type="button" class="btn-effect" @click="usePromoCode()" data-dismiss="modal">İstİfadə et</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-backdrop fade in"></div>
        </div>
    </div>
</template>

<script>
    import {axios} from "../config/axios";
    import Search from '../Search';
    export default {
        name: "PromoCodes",
        data:function () {
            return {
                promocodes:[],
                paginations: {},
                promoCodeExists:false,
                promoCode:'',
                name:'',
                user_id:'',
                userExists:false,
                used:true,
                isnormalsearch: 'promocode',
                errors_promocode: {
                    organizer: false,
                    date_start: false,
                    date_end: false
                },
                isSearched: 0,
                organizer: '',
                dates_start: '',
                date_start: '',
                dates_end: '',
                date_end: '',
                monthNames: ["Yanvar", "Fevral", "Mart", "Aprel", "May", "İyun",
                    "İyul", "Avqust", "Sentyabr", "Oktyabr", "Noyabr", "Dekabr"
                ]
            }
        },
        mounted() {
            this.getPromoCodes()
        },
        components: {
            Search
        },
        methods: {
            formatDatetime(date) {
                let d = new Date(date);
                return d.getDate() + ' ' + this.monthNames[d.getMonth()] + ' ' + d.getFullYear() + ' ' + d.getHours() + ':' + (d.getMinutes() < 10 ? '0'+ d.getMinutes() : d.getMinutes());
            },
            resetErrorsPromocode: function(){
                this.errors_promocode = {
                    organizer: false,
                    date_start: false,
                    date_end: false
                }
            },
            getPromoCodes(page, query, isSearched) {
                if(isSearched) {
                    this.isSearched = isSearched;
                }

                if(this.$route.query.page) {
                    page = this.$route.query.page;
                }
                else {
                    page = 1;
                }
                this.page = page;
                let count = 50;

                if(this.isSearched === 1) {
                    this.resetErrorsPromocode();
                    if(query) {
                        this.organizer = query.organizer;
                        this.dates_start = query.date_start ? new Date(query.date_start) : '';
                        this.date_start = this.dates_start !== '' ? this.dates_start.getFullYear() + "-" + ('0' + (this.dates_start.getMonth() + 1)).slice(-2) + "-" +('0'+this.dates_start.getDate()).slice(-2) : '';
                        this.dates_end = query.date_end ? new Date(query.date_end) : '';
                        this.date_end = this.dates_end !== '' ? this.dates_end.getFullYear() + "-" + ('0' + (this.dates_end.getMonth() + 1)).slice(-2) + "-" +('0'+this.dates_end.getDate()).slice(-2) : '';
                    }
                    axios.get(`/promotions/used-list-search?page=${page}&count=${count}&organizer=${this.organizer}&date_start=${this.date_start}&date_end=${this.date_end}&status=all`)
                        .then(response => {
                            if(response.status === 200) {
                                if(response.data.data!==null) {
                                    if (response.data.data.list != null) {
                                        this.paginations = response.data.data.pagination;
                                        this.paginations.perPage = 10;
                                        this.promocodes = response.data.data.list;
                                    }
                                    else {
                                        this.promocodes = [];
                                        this.paginations = {};
                                    }
                                }
                                else {
                                    this.promocodes = [];
                                    this.paginations = {};
                                }
                            }
                        })
                        .catch(error => {
                            if (error.response.data.data !== null) {
                                if(error.response.data.data) {
                                    error.response.data.data.organizer ? this.errors_promocode.organizer = error.response.data.data.organizer[0] : '';
                                    error.response.data.data.date_start ? this.errors_promocode.date_start = error.response.data.data.date_start[0] : '';
                                    error.response.data.data.date_end ? this.errors_promocode.date_end = error.response.data.data.date_end[0] : '';
                                }
                            }
                            else {
                                this.promocodes = [];
                                this.paginations = {};
                            }
                        })
                }
                else {
                    axios.get('/promotions/used-list'+'?count='+ count + '&page='+page).then(
                        response=> {
                            if (response.data.data !== null) {
                                this.promocodes = response.data.data.list;
                                this.paginations = response.data.data.pagination
                            }
                        }
                    )
                }
            },
            checkPromoCode (){
                if(this.promoCode.trim()){
                    axios.post('/promotions/check',{promocode:this.promoCode}).then(
                        response=> {
                            this.promoCodeExists = true;
                            if (response.data.status == 200) {
                                this.userExists = true;
                                this.name = response.data.data.name;
                                this.user_id = response.data.data.user_id
                            }
                        }).catch(response => {
                        this.promoCodeExists = true;
                        this.userExists = false;
                    })

                }
            },
            usePromoCode(){
                axios.post('/promotions/check',{promocode:this.promoCode,used:this.used}).then(
                    response=> {
                        this.promoCodeExists = false;
                        this.getPromoCodes()
                    })
            }
        }
    }
</script>

<style scoped>

</style>
