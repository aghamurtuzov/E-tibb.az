<template>
    <div class="donate-block stocks blog">
        <breadcrumb name="Qan ver" icon="fa-plus-square-o" :routes="[{'name':'Qanver'}]"></breadcrumb>
        <div class="row">
            <div class="col-md-12">
                <div class="appointment statics mb-20">
                    <div class="static-blocks doctor-static">
                        <div class="block cursor" @click="isActiveOrDeactive(1)" :class="isactivedonate === 1 ? 'active' : '' ">
                            AKTİV ELANLAR
                        </div>
                        <div class="block cursor" @click="isActiveOrDeactive(0)" :class="isactivedonate === 0 ? 'active' : '' ">
                            DEAKTİV ELANLAR
                        </div>
                        <div class="block cursor mbx-0" @click="isActiveOrDeactive('')" :class="isactivedonate === 'all' ? 'active' : '' ">
                            BÜTÜN ELANLAR
                        </div>
                    </div>
                </div>
            </div>
            <Search :isnormalsearch="isnormalsearch" :errors_donate = "errors_donate" v-on:SearchRequestedDonate = "getDonateList" />
            <div class="col-md-12">
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
                                        <input type="checkbox" v-model="selectAll" @change="selectAllDonates(); selectDonates()">
                                        <span class="checkmark"></span>
                                    </label>
                                </th>
                                <th>Elan verən</th>
                                <th>Başlıq</th>
                                <th>Tarix</th>
                                <th>Status</th>
                                <th style="min-width:280px;">
                                    <router-link tag="button" class="btn-add" to="/donate/add">
                                        <span><i class="fa fa-plus" aria-hidden="true"></i></span>YENİ
                                    </router-link>
                                    <span v-if="user_role == 'superadmin'">
                                            <button type="button" class="btn-trash" v-tooltip.top="hard_remove_message" @click="delete__custom('/donate/all-base-delete','all')">
                                                <span><i class="fa fa-minus-square" aria-hidden="true"></i></span>
                                            </button>
                                        </span>
                                    <span v-else>
                                        </span>
                                    <button type="button" class="btn-trash" v-tooltip.auto="remove_message" @click="delete__custom('/donate/all-delete', 'all')">
                                        <span><i class="fa fa-trash" aria-hidden="true"></i></span>
                                    </button>
                                </th>
                            </tr>
                            </thead>
                            <tbody v-if=" donateList.length>0">
                            <tr v-for="donate in donateList">
                                <td>
                                    <label class="check-button">
                                        <input type="checkbox" :checked="selectAll" v-model="donate.checked" @change="selectDonates()">
                                        <span class="checkmark"></span>
                                    </label>
                                </td>
                                <td>{{donate.user_info}}</td>
                                <td>{{donate.title}}</td>
                                <td>{{donate.created_at}}</td>
                                <td><p :class="getClassByStatus(donate.status)">{{donate.status_name}}</p></td>
                                <td>
                                      <span v-if="user_role == 'superadmin'">
                                        <button type="button" class="btn btn-remove-doctor" @click="delete__custom('/donate/base-delete-one', donate.id)">
                                            <span><i class="fa fa-minus-square" aria-hidden="true"></i>Həmişəlik sil</span>
                                        </button>
                                    </span>
                                    <span v-else=""></span>

                                    <button type="button" class="btn btn-remove-doctor" @click="delete__custom('/donate/delete-one', donate.id)">
                                        <span><i class="fa fa-trash" aria-hidden="true"></i>Sil</span>
                                    </button>
                                    <router-link :to="{path: 'donate/'+donate.id, }" class="btn btn-view">
                                        <span><i class="fa fa-eye" aria-hidden="true"></i></span>Bax
                                    </router-link>
                                </td>
                            </tr>
                            </tbody>
                            <tbody v-else class="thead-bottom" >
                            <tr>
                                <td colspan="6">Məlumat yoxdur</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <pagination :paginations="paginations" :callback="getDonateList"></pagination>
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
        name: "donateList",
        data: function () {
            return {
                donateList: [],
                paginations: {},
                user_info: '',
                text: '',
                created_at: '',
                status_name: '',
                id: '',
                status: '',
                title:'',
                selectAll:false,
                isnormalsearch: 'donate',
                isactivedonate: 1,
                errors_donate:{
                    datetime:false
                },
                isSearched: 0,
                dates: '',
                datetime:'',
                selectedId: '',
                user_role: '',
                remove_message: 'Toplu sil',
                hard_remove_message: 'Həmişəlik sil (Toplu)'
            }
        },
        mounted: function () {
            this.getDonateList();
            axiosGet('/api/site/user').then(data => this.user_role = data.role.item_name);
        },
        components: {
          Search
        },
        watch: {
            user_role: function(newVal, oldVal) {
                this.user_role = newVal;
            }
        },
        methods: {
            resetErrorsDonate: function(){
                this.errors_donate = {
                    datetime:false
                }
            },

            getDonateList(page, query, isSearched) {
                if(isSearched) {
                    this.isSearched = isSearched;
                }
                else {
                }

                if(this.$route.query.page) {
                    page = this.$route.query.page;
                }
                else {
                    page = 1;
                }
                this.page = page;

                if(this.isSearched === 1) {
                    this.resetErrorsDonate();
                    if(query) {
                        this.dates = query.datetime ? new Date(query.datetime) : '';
                        this.datetime = this.dates !== '' ? this.dates.getFullYear() + "-" + ('0' + (this.dates.getMonth() + 1)).slice(-2) + "-" +('0'+this.dates.getDate()).slice(-2) : '';
                    }
                    axios.get(`/donate/search?page=${page}&date=${this.datetime}&status=${this.isactivedonate}`)
                        .then(response => {
                            if(response.status === 200) {
                                if(response.data.data!==null) {
                                    if (response.data.data.list != null) {
                                        this.donateList = response.data.data.list;
                                        this.paginations = response.data.data.pagination;
                                    }
                                    else {
                                        this.donateList = [];
                                        this.paginations = {};
                                    }
                                }
                                else {
                                    this.donateList = [];
                                    this.paginations = {};
                                }
                            }
                        })
                        .catch(error => {
                            if (error.response.data.data !== null) {
                                if(error.response.data.data) {
                                    error.response.data.data.datetime ? this.errors_questions.datetime = error.response.data.data.datetime[0] : '';
                                }
                            }
                            else {
                                this.donateList = [];
                                this.paginations = {};
                            }
                        })
                }
                else {
                    axios.get(`donate?count=${this.$count}&page=${page}&status=${this.isactivedonate}`)
                        .then(response => {
                            this.donateList = response.data.data.list || [];
                            this.paginations = response.data.data.pagination || [];
                        }).catch(err => console.log('Error: ',err));
                }
            },
            isActiveOrDeactive(isactivedonate) {
                this.isSearched = 0;
                if(this.$route.query.page !== '1') {
                    this.$router.push({ query: { page: 1 }});
                }
                if (isactivedonate === 1) {
                    this.isactivedonate = 1;
                    this.getDonateList(this.isactivedonate);
                }
                else if (isactivedonate === 0) {
                    this.isactivedonate = 0;
                    this.getDonateList(this.isactivedonate);
                }
                else {
                    this.isactivedonate = 'all';
                    this.getDonateList(this.isactivedonate);
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
            selectAllDonates(){
                for(let i in this.donateList){
                    this.donateList[i].checked = this.selectAll
                }
            },
            selectDonates() {
                let selectedId = [];
                for (let i in this.donateList) {
                    if (this.donateList[i].checked) {
                        selectedId.push(this.donateList[i].id)
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
                                axiosPost(url,id).then(data => this.getDonateList());
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
                        text: 'Heç bir elan seçməmisiniz!',
                    })
                }
            },
        }
    }
</script>

<style scoped>

</style>
