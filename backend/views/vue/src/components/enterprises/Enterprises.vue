<template>
    <div class="pharmacy blog">
        <breadcrumb :name=breadcrumbs.name :icon=breadcrumbs.icon :routes=breadcrumbs.routes></breadcrumb>
        <div class="row">
            <div class="col-md-12">
                <div class="appointment statics mb-20">
                    <div class="static-blocks doctor-static">
                        <div class="block cursor" @click="isActiveOrDeactive(1)" :class="isactiveclinics === 1 ? 'active' : '' ">
                            AKTİV OBYEKTLƏR
                        </div>
                        <div class="block cursor" @click="isActiveOrDeactive(0)" :class="isactiveclinics === 0 ? 'active' : '' ">
                            DEAKTİV OBYEKTLƏR
                        </div>
                        <div class="block cursor mbx-0" @click="isActiveOrDeactive('')" :class="isactiveclinics === 'all' ? 'active' : '' ">
                            BÜTÜN OBYEKTLƏR
                        </div>
                    </div>
                </div>
            </div>

            <Search :isnormalsearch="isnormalsearch" :errors_enterprises="errors_enterprises" v-on:SearchRequestedEnterprises = "getEnterprises" />

            <div class="col-md-12">
                <div class="information-blog">
                  <div class="notification-bar">
                    Cədvəl tam görünmürsə sağa doğru sürüşdürün və ya cihazınızın "ekran fırlanması" özəlliyini aktivləşdirin
                  </div>
                  <div class="block mtx-0">
                    <div class="table__Container">
                        <table class="table table-hover table-striped ">
                            <thead>
                            <tr>
                                <th>
                                    <label class="check-button">
                                        <input type="checkbox" v-model="selectAll" @change="selectAllEnterprises(); selectEnterprises();">
                                        <span class="checkmark"></span>
                                    </label>
                                </th>
                                <th>Foto</th>
                                <th>Obyektin adı</th>
                                <th>Əlaqələndirici <br> şəxsin adı, soyadı </th>
                                <!--<th>Qeydiyyat nömrəsi</th>-->
                                <th>Əlaqələndirici <br> şəxs telefonu </th>
                                <th>Əlaqələndirici <br> şəxs E-mail </th>
                                <th>Status</th>
                               <!--<th>Ödəniş</th>
                                <th>Paket</th>
                                <th>Xidmət</th>
                                <th>Xidmət tarixi</th>-->

                                <th style="min-width:280px;" v-if="isClinicPage==='6'">
                                    <router-link tag="button" class="btn-add" to="/enterprises/add?id=6">
                                        <span><i class="fa fa-plus" aria-hidden="true"></i></span>YENİ
                                    </router-link>
                                    <span v-if="user_role == 'superadmin'">
                                            <button type="button" class="btn-trash" v-tooltip.top="hard_remove_message" @click="delete__custom('/enterprises/all-base-delete','all')">
                                                <span><i class="fa fa-minus-square" aria-hidden="true"></i></span>
                                            </button>
                                        </span>
                                    <span v-else>
                                        </span>
                                    <button type="button" class="btn-trash" v-tooltip.auto="remove_message" @click="delete__custom('/enterprises/all-delete', 'all')">
                                        <span><i class="fa fa-trash" aria-hidden="true"></i></span>
                                    </button>
                                </th>
                                <th style="min-width:280px;" v-else>
                                  <router-link tag="button" class="btn-add" to="/enterprises/add?id=1">
                                    <span><i class="fa fa-plus" aria-hidden="true"></i></span>YENİ
                                  </router-link>
                                    <span v-if="user_role == 'superadmin'">
                                            <button type="button" class="btn-trash" v-tooltip.top="hard_remove_message" @click="delete__custom('/enterprises/all-base-delete','all')">
                                                <span><i class="fa fa-minus-square" aria-hidden="true"></i></span>
                                            </button>
                                        </span>
                                    <span v-else>
                                        </span>
                                    <button type="button" class="btn-trash" v-tooltip.auto="remove_message" @click="delete__custom('/enterprises/all-delete', 'all')">
                                        <span><i class="fa fa-trash" aria-hidden="true"></i></span>
                                    </button>
                                </th>
                            </tr>
                            </thead>
                            <tbody v-if="enterprises.length>0">
                                <tr v-for="enterprise in enterprises">
                                    <td>
                                        <label class="check-button">
                                            <input type="checkbox" :checked="selectAll" v-model="enterprise.checked" @change="selectEnterprises()">
                                            <span class="checkmark"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <img v-if="enterprise.photo" :src="enterprise.thumb" alt="klinika">
                                        <img v-else src="/admin/cp/img/bg-object.png" alt="klinika">
                                    </td>
                                    <td>{{enterprise.name}}</td>
                                    <td>{{enterprise.user_name}}</td>
                                    <!--<td>{{enterprise.user_id}}</td>-->
                                    <td>{{enterprise.user_phone_number}}</td>
                                    <td>{{enterprise.user_email}}</td>
                                    <td><p :class="getClassByStatus(enterprise.status)">{{enterprise.status_name}}</p></td>
                                    <td>
                                        <span v-if="user_role == 'superadmin'">
                                            <button type="button" class="btn btn-remove-doctor" @click="delete__custom('/enterprises/base-delete-one', enterprises.id)">
                                                <span><i class="fa fa-minus-square" aria-hidden="true"></i>Həmişəlik sil</span>
                                            </button>
                                        </span>
                                        <span v-else=""></span>

                                        <button type="button" class="btn btn-remove-doctor" @click="delete__custom('/enterprises/delete-one', enterprises.id)">
                                            <span><i class="fa fa-trash" aria-hidden="true"></i>Sil</span>
                                        </button>
                                        <router-link :to="{path: '/enterprises/'+module+'/'+enterprise.id}" class="btn btn-view">
                                            <span><i class="fa fa-eye" aria-hidden="true"></i></span>Bax
                                        </router-link>
                                    </td>
                                    <!--<td>{{user.last_login}}</td>-->
                                </tr>
                            </tbody>
                            <tbody v-else class="thead-bottom" >
                            <tr>
                                <td colspan="8">Məlumat yoxdur</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <pagination v-if="paginations" :paginations="paginations" :callback="getEnterprises"></pagination>
                  </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {axios} from "../config/axios";
    import Search from '../Search';
    import {axiosGet, axiosPost} from '../config/helper';
    import Swal from "sweetalert2";
    export default {
        name: "Enterprises",
        data:function () {
            return {
                enterprises:[],
                paginations: {},
                breadcrumbs: {
                    name: '',
                    icon: '',
                    routes: []
                },
                module: '',
                status: '',
                status_name: '',
                searchInput:'',
                isnormalsearch: 'enterprises',
                isClinicPage: '',
                isactiveclinics: 1,
                errors_enterprises:{
                    name: false,
                    code: false,
                    phone: false
                },
                isSearched: 0,
                name: '',
                code: '',
                phone:'',
                selectAll:false,
                selectedId: '',
                user_role: '',
                remove_message: 'Toplu sil',
                hard_remove_message: 'Həmişəlik sil (Toplu)'
            }
        },
        computed: {
            id(){
                return this.$route.query.id
            }
        },
        watch: {
            id(){
                this.getEnterprises();
                this.setBreadcrumbs();
            },
            $route (to, from){
              this.isClinicPage = this.$route.query.id;
            }
        },
        mounted() {
            this.isactiveclinics = 1;
            this.getEnterprises();
            this.setBreadcrumbs();
            this.isClinicPage = this.$route.query.id;
            if(this.isClinicPage === 6) {
                this.isnormalsearch = 'pharmacies';
            }
            else {
                this.isnormalsearch = 'clinics';
            }
            axiosGet('/api/site/user').then(data => this.user_role = data.role.item_name);
        },
        components: {
          Search
        },
        methods: {
            resetErrorsEnterprises: function(){
                this.errors_enterprises = {
                    name: false,
                    code: false,
                    phone: false
                }
            },
            getEnterprises(page, query, isSearched) {
                let id = this.$route.query.id;
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
                    this.resetErrorsEnterprises();
                    if(query) {
                        this.name = query.name;
                        this.code = query.code;
                        this.phone = query.phone;
                    }
                    axios.get(`/enterprises/search?page=${page}&count=${count}&name=${this.name}&code=${this.code}&phone=${this.phone}&category_id=${this.id}&status=${this.isactiveclinics}`)
                        .then(response => {
                            if(response.status === 200) {
                                if(response.data.data!==null) {
                                    if (response.data.data.list != null) {
                                        this.paginations = response.data.data.pagination;
                                        this.paginations.perPage = 5;
                                        this.enterprises = response.data.data.list;
                                    }
                                    else {
                                        this.enterprises = [];
                                        this.paginations = {};
                                    }
                                }
                                else {
                                    this.enterprises = [];
                                    this.paginations = {};
                                }
                            }
                        })
                        .catch(error => {
                            if (error.response.data.data !== null) {
                                if(error.response.data.data) {
                                    error.response.data.data.name ? this.errors_enterprises.name = error.response.data.data.name[0] : '';
                                    error.response.data.data.code ? this.errors_enterprises.code = error.response.data.data.code[0] : '';
                                    error.response.data.data.phone ? this.errors_enterprises.phone = error.response.data.data.phone[0] : '';
                                }
                            }
                            else {
                                this.enterprises = [];
                                this.paginations = {};
                            }
                        })
                }
                else {
                    axios.get(`enterprises?id=${id}&count=${count}&page=${page}&type=${this.isactiveclinics}&search=${this.searchInput}`).then(
                        response=> {
                            if((response.data.data !== null) ) {
                                this.enterprises = response.data.data.list;
                                this.paginations = response.data.data.pagination
                            }
                            else {
                                this.enterprises = [];
                                this.paginations = {}
                            }
                        }
                    )
                }
            },
            isActiveOrDeactive(isactiveclinics) {
                this.isSearched = 0;
                if(this.$route.query.page !== '1') {
                    this.$router.push({ query: { page: 1, id: this.$route.query.id }});
                }
                if (isactiveclinics === 1) {
                    this.isactiveclinics = 1;
                    this.getEnterprises(this.isactiveclinics);
                }
                else if (isactiveclinics === 0) {
                    this.isactiveclinics = 0;
                    this.getEnterprises(this.isactiveclinics);
                }
                else {
                    this.isactiveclinics = 'all';
                    this.getEnterprises(this.isactiveclinics);
                }
            },
            setBreadcrumbs: function () {
                switch(parseInt(this.id)){
                    case 1:
                        this.module = 'clinics';
                        this.breadcrumbs = {
                            name: 'KLİNİKALAR',
                            routes: [{name: 'Klinikalar'}],
                            icon: 'fa-hospital-o'
                        };
                        break;
                    case 6:
                        this.module = 'pharmacy';
                        this.breadcrumbs = {
                                name: 'APTEKLƏR',
                                routes: [{name: 'Apteklər'}],
                                icon: 'fa-plus'
                            };
                        break;
                    case 21:
                        this.module = 'medical';
                        this.breadcrumbs = {
                            name: 'TİBBİ MAĞAZA',
                            routes: [{name: 'Tibbi mağaza'}],
                            icon: 'fa-shopping-cart'
                        };
                        break;
                    default:
                        break;
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

            selectAllEnterprises(){
                for(let i in this.enterprises){
                    this.enterprises[i].checked = this.selectAll
                }
            },

            selectEnterprises() {
                let selectedId = [];
                for (let i in this.enterprises) {
                    if (this.enterprises[i].checked) {
                        selectedId.push(this.enterprises[i].id)
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
                                axiosPost(url,id).then(data => this.getEnterprises());
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
                        text: 'Heç bir obyekt seçməmisiniz!',
                    })
                }
            },
        }
    }
</script>

<style scoped>
  .d-none {
    display: none;
  }

  .check-button .checkmark {
      border: 1px solid #ccc !important;
      border-radius: 4px !important;
  }

</style>
