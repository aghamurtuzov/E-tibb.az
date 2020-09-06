<template>
    <div class="stocks blog">
        <breadcrumb name="Aksİyalar" icon="fa-certificate" :routes="[{'name':'Aksiyalar'}]"></breadcrumb>
        <div class="row">
            <div class="col-md-12">
                <div class="appointment statics mb-20">
                    <div class="static-blocks doctor-static">
                        <div class="block cursor" @click="isActiveOrDeactive(1)" :class="isactivestocks === 1 ? 'active' : '' ">
                            AKTİV AKSİYALAR
                        </div>
                        <div class="block cursor" @click="isActiveOrDeactive(0)" :class="isactivestocks === 0 ? 'active' : '' ">
                            DEAKTİV AKSİYALAR
                        </div>
                        <div class="block cursor mbx-0" @click="isActiveOrDeactive('')" :class="isactivestocks === 'all' ? 'active' : '' ">
                            BÜTÜN AKSİYALAR
                        </div>
                    </div>
                </div>
            </div>

            <Search :isnormalsearch="isnormalsearch" :errors_stocks="errors_stocks" v-on:SearchRequestedStocks = "getStocks" />

            <div class="col-md-12 pharmacy">
                <div class="information-blog">
                  <div class="notification-bar">
                    Cədvəl tam görünmürsə sağa doğru sürüşdürün və ya cihazınızın "ekran fırlanması" özəlliyini aktivləşdirin
                  </div>
                  <div class="block mtx-0">
                    <div class="table__Container">
                        <table class="table table-hover table-striped">
                            <thead>
                            <tr>
                                <th>
                                    <label class="check-button">
                                        <input type="checkbox" v-model="selectAll" @change="selectAllPromotions(); selectPromotions();">
                                        <span class="checkmark"></span>
                                    </label>
                                </th>
                                <th>Foto</th>
                                <th>Aksiya keçirən Adı, Kodu</th>
                                <th>Aksiya adı</th>
                                <th>Qiymət</th>
                                <th>Endirim %</th>
                                <th>Başlanğıc Tarix</th>
                                <th>Bitiş Tarix</th>
                                <th>Status</th>
                                <th style="min-width:280px;">
                                    <router-link tag="button" class="btn-add" to="/stocks/add">
                                        <span><i class="fa fa-plus" aria-hidden="true"></i></span>YENİ
                                    </router-link>
                                    <span v-if="user_role == 'superadmin'">
                                            <button type="button" class="btn-trash" v-tooltip.top="hard_remove_message" @click="delete__custom('/promotions/all-base-delete','all')">
                                                <span><i class="fa fa-minus-square" aria-hidden="true"></i></span>
                                            </button>
                                        </span>
                                    <span v-else>
                                        </span>
                                    <button type="button" class="btn-trash" v-tooltip.auto="remove_message" @click="delete__custom('/promotions/all-delete', 'all')">
                                        <span><i class="fa fa-trash" aria-hidden="true"></i></span>
                                    </button>
                                </th>
                            </tr>
                            </thead>
                            <tbody v-if=" stocks.length>0">
                            <tr v-for="stock in stocks">
                                <td>
                                    <label class="check-button">
                                        <input type="checkbox" :checked="selectAll" v-model="stock.checked" @change="selectPromotions()">
                                        <span class="checkmark"></span>
                                    </label>
                                </td>
                                <td>
                                    <img v-if="stock.photo" :src="stock.thumb" alt="aksiya">
                                    <img v-else src="/admin/cp/img/bg-object.png" alt="aksiya">
                                </td>
                                <td>{{stock.byPromotion}}</td>
                                <td>{{stock.headline}}</td>
                                <td>{{stock.price}} Azn</td>
                                <td>{{stock.discount}}</td>
                                <td>{{stock.date_start}}</td>
                                <td>{{stock.date_end}}</td>
                                <td><p :class="getClassByStatus(stock.status)">{{stock.status_name}}</p></td>
                                <td>
                                    <span v-if="user_role == 'superadmin'">
                                        <button type="button" class="btn btn-remove-doctor" @click="delete__custom('/promotions/base-delete-one', donate.id)">
                                            <span><i class="fa fa-minus-square" aria-hidden="true"></i>Həmişəlik sil</span>
                                        </button>
                                    </span>
                                    <span v-else=""></span>
                                    <button type="button" class="btn btn-remove-doctor" @click="delete__custom('/promotions/delete-one', donate.id)">
                                        <span><i class="fa fa-trash" aria-hidden="true"></i>Sil</span>
                                    </button>
                                    <router-link :to="{path: 'stock/'+stock.id, }" class="btn btn-view">
                                        <span><i class="fa fa-eye" aria-hidden="true"></i></span>Bax
                                    </router-link>
                                </td>
                            </tr>
                            </tbody>
                            <tbody v-else class="thead-bottom" >
                            <tr>
                                <td colspan="10">Məlumat yoxdur</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <pagination :paginations="paginations" :callback="getStocks"></pagination>
                  </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {axios} from '../config/axios';
    import Search from '../Search';
    import {axiosGet, axiosPost} from '../config/helper';
    import Swal from "sweetalert2";
    export default {
        name: "Stocks",
        data: function () {
            return {
                stocks: [],
                paginations: {},
                date_end: '',
                date_start: '',
                discount: '',
                price: '',
                status_name: '',
                thumb: '',
                id: '',
                byPromotion: '',
                status: '',
                selectAll:false,
                isnormalsearch: 'stocks',
                isactivestocks: 1,
                errors_stocks:{
                    organizer:false,
                    date_start:false
                },
                isSearched: 0,
                organizer: '',
                dates: '',
                selectedId: '',
                user_role: '',
                remove_message: 'Toplu sil',
                hard_remove_message: 'Həmişəlik sil (Toplu)',
                monthNames: ["Yanvar", "Fevral", "Mart", "Aprel", "May", "İyun",
                    "İyul", "Avqust", "Sentyabr", "Oktyabr", "Noyabr", "Dekabr"
                ]
            }
        },
        mounted: function () {
            this.getStocks();
            axiosGet('/api/site/user').then(data => this.user_role = data.role.item_name);
        },
        components: {
          Search
        },
        methods: {
            resetErrorsStocks: function(){
                this.errors_stocks = {
                    organizer:false,
                    date_start:false
                }
            },

            getStocks(page, query, isSearched) {
                if(isSearched) {
                    this.isSearched = isSearched;
                }
                else {
                    console.log('sdsdsdsd', isSearched)
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
                    this.resetErrorsStocks();
                    if(query) {
                        this.organizer = query.organizer;
                        this.dates = query.date_start ? new Date(query.date_start) : '';
                        this.date_start = this.dates !== '' ? this.dates.getFullYear() + "-" + ('0' + (this.dates.getMonth() + 1)).slice(-2) + "-" +('0'+this.dates.getDate()).slice(-2) : '';
                    }
                    axios.get(`/promotions/search?page=${page}&count=${count}&organizer=${this.organizer}&date_start=${this.date_start}&status=${this.isactivestocks}`)
                        .then(response => {
                            if(response.status === 200) {
                                if(response.data.data!==null) {
                                    if (response.data.data.list != null) {
                                        this.stocks = response.data.data.list;
                                        this.paginations = response.data.data.pagination;
                                    }
                                    else {
                                        this.stocks = [];
                                        this.paginations = {};
                                    }
                                }
                                else {
                                    this.stocks = [];
                                    this.paginations = {};
                                }
                            }
                        })
                        .catch(error => {
                            if (error.response.data.data !== null) {
                                if(error.response.data.data) {
                                    error.response.data.data.organizer ? this.errors_stocks.organizer = error.response.data.data.organizer[0] : '';
                                    error.response.data.data.date_start ? this.errors_stocks.date_start = error.response.data.data.date_start[0] : '';
                                }
                            }
                            else {
                                this.stocks = [];
                                this.paginations = {};
                            }
                        })
                }
                else {
                    axios.get(`promotions?count=${count}&page=${page}&status=${this.isactivestocks}`)
                        .then(response => {
                            if (response.data.data) {
                                this.stocks = response.data.data.list;
                                this.paginations = response.data.data.pagination
                            }
                            else {
                                this.stocks = [];
                                this.paginations = {}
                            }
                        })
                        .catch(e => {
                            console.log(e)
                        })
                }
            },

            isActiveOrDeactive(isactivestocks) {
                this.isSearched = 0;
                if(this.$route.query.page !== '1') {
                    this.$router.push({ query: { page: 1 }});
                }
                if (isactivestocks === 1) {
                    this.isactivestocks = 1;
                    this.getStocks(this.isactivestocks);
                }
                else if (isactivestocks === 0) {
                    this.isactivestocks = 0;
                    this.getStocks(this.isactivestocks);
                }
                else {
                    this.isactivestocks = 'all';
                    this.getStocks(this.isactivestocks);
                }
            },

            getClassByStatus(status) {
                switch (parseInt(status)) {
                    case 1:
                        return 'confirmed';
                        break;
                    case 2:
                        return 'waiting';
                        break;
                    case 0:
                        return 'rejected';
                        break;
                }
            },

            selectAllPromotions(){
                for(let i in this.stocks){
                    this.stocks[i].checked = this.selectAll
                }
            },

            selectPromotions() {
                let selectedId = [];
                for (let i in this.stocks) {
                    if (this.stocks[i].checked) {
                        selectedId.push(this.stocks[i].id)
                    }
                }
                this.selectedId = JSON.stringify(selectedId);
            },

            delete__custom(url, ids) {
                if(ids === 'all') {
                    var id = [];
                    if (this.selectedId) {
                        id = JSON.parse(this.selectedId);
                    }
                }
                else {
                    var id = ids;
                }
                if (id.length > 0) {
                    Swal.fire({
                        title: "Bu əməliyyatı icra etməyə əminsiniz?",
                        text: "Əgər silsəniz, geri qaytara bilməyəcəksiniz!",
                        icon: "warning",
                        buttons: true,
                        showCancelButton: true,
                        dangerMode: true,
                    })
                        .then((result) => {
                            if (result.value) {
                                axiosPost(url,id).then(data => this.getStocks());
                                this.selectedId = '';
                            }
                        });
                }
                else {
                    return Swal.fire({
                        icon: 'error',
                        title: 'Bildiriş',
                        confirmButtonText:
                            '<i class="fa fa-thumbs-up"></i> Oldu!',
                        text: 'Heç bir aksiya seçməmisiniz!',
                    })
                }
            },
        }
    }
</script>

<style scoped>
    .check-button .checkmark {
        border: 1px solid #ccc !important;
        border-radius: 4px !important;
    }
</style>
