<template>
    <div class="reviews letters blog">
        <breadcrumb name="Rəylər" icon="fa-thumbs-up" :routes="[{name:'Rəylər'}]"></breadcrumb>
        <div class="row">
            <div class="col-md-12">
                <div class="appointment statics mb-20">
                    <div class="static-blocks doctor-static">
                        <div class="block cursor" @click="isActiveOrDeactive(1)" :class="isactivereviews === 1 ? 'active' : '' ">
                            AKTİV RƏYLƏR
                        </div>
                        <div class="block cursor" @click="isActiveOrDeactive(0)" :class="isactivereviews === 0 ? 'active' : '' ">
                            DEAKTİV RƏYLƏR
                        </div>
                        <div class="block cursor mbx-0" @click="isActiveOrDeactive('')" :class="isactivereviews === 'all' ? 'active' : '' ">
                            BÜTÜN RƏYLƏR
                        </div>
                    </div>
                </div>
            </div>
            <Search :isnormalsearch="isnormalsearch" :errors_reviews = "errors_reviews" v-on:SearchRequestedReviews = "getReviews" />
            <div class="col-md-12 reservation-position mtx-0">
                <div class="row relative">
                    <div class="col-md-7">
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
                                                <input type="checkbox" v-model="selectAll" @change="selectAllReviews(); selectReviews();">
                                                <span class="checkmark"></span>
                                            </label>
                                        </th>
                                        <th>İstifadəçi</th>
                                        <th>Rəy verilən</th>
                                        <th style="min-width: 105px;">Reytinq</th>
                                        <th>Tarix</th>
                                        <th style="min-width:215px; text-align:center !important;">
                                            <button style="float: none;" type="button" class="btn-trash" v-tooltip.auto="remove_message" @click="delete__custom('/comments/all-delete', 'all')">
                                                <span><i class="fa fa-trash" aria-hidden="true"></i></span>
                                            </button>
                                            <span v-if="user_role == 'superadmin'">
                                                <button style="float: none;" type="button" class="btn-trash" v-tooltip.top="hard_remove_message" @click="delete__custom('/comments/all-base-delete','all')">
                                                    <span><i class="fa fa-minus-square" aria-hidden="true"></i></span>
                                                </button>
                                            </span>
                                            <span v-else>
                                            </span>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody class="text-content" v-if="reviews.length>0">
                                    <tr v-for="review in reviews" @click="selected_item = review" :class="selected_item == review ? 'active' : ''">
                                        <td>
                                            <label class="check-button">
                                                <input type="checkbox" :checked="selectAll" v-model="review.checked" @change="selectReviews()">
                                                <span class="checkmark"></span>
                                            </label>
                                        </td>
                                        <td>{{review.name}}</td>
                                        <td>
                                            <router-link class="doctor__name" target="_blank" :to="{path: 'doctor/'+review.connect_id}">
                                                {{review.comment_by}}
                                            </router-link>
                                        </td>
                                        <td style="min-width: 105px;">
                                            <p class="rating">
                                                <span v-for="item in [0,1,2,3,4]"><i class="fa" :class="getClass(item, review.rating)" aria-hidden="true"></i></span>
                                            </p>
                                        </td>
                                        <td>
                                            {{review.datetime}}
                                        </td>
                                        <td>
                                            <span v-if="user_role == 'superadmin'">
                                                <button type="button" class="btn btn-remove-doctor" @click="delete__custom('/comments/base-delete-one', review.id)">
                                                    <span><i class="fa fa-minus-square" aria-hidden="true"></i>Həmişəlik sil</span>
                                                </button>
                                            </span>
                                            <span v-else=""></span>

                                            <button type="button" class="btn btn-remove-doctor" @click="delete__custom('/comments/delete-one', review.id)">
                                                <span><i class="fa fa-trash" aria-hidden="true"></i>Sil</span>
                                            </button>
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
                            <pagination :paginations="paginations" :callback="getReviews"></pagination>
                          </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="article-form pts-20">
                            <div class="block">
                                <div class="article-lists">
                                    <div class="text-content">
                                        <ul class="list-inline information-body ">
                                            <li class="bg-whitesmoke">
                                                <p class="user-information">
                                                    {{selected_item.name}}
                                                    <span>{{ selected_item.datetime.split(" ")[0]}}</span>
                                                </p>
                                                <p>{{selected_item.comment}} </p>
                                                <div class="status-block">
                                                    <button class="active-status btn" @click="active_comment()" v-if="selected_item.status==0">Aktiv et</button>
                                                    <button class="block-status btn" @click="block_comment()" v-else>Blok et</button>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {axios} from "../config/axios";
    import Search from '../Search';
    import {axiosGet, axiosPost} from "../config/helper";
    import Swal from "sweetalert2";
    export default {
        name: "Review",
        data: function () {
            return {
                id: this.$route.params.id,
                reviews:[],
                paginations:{},
                selected_item: {
                    name: '',
                    comment: '',
                    datetime: '',
                    status:''
                },
                isnormalsearch: 'reviews',
                isactivereviews: 0,
                errors_reviews: {
                    doctor_name:false,
                    datetime:false
                },
                isSearched: 0,
                doctor_name: '',
                dates: '',
                datetime:'',
                selectAll:false,
                selectedId: '',
                user_role: '',
                remove_message: 'Toplu sil',
                hard_remove_message: 'Həmişəlik sil (Toplu)'
            }
        },
        mounted() {
            this.getReviews();
            axiosGet('/api/site/user').then(data => this.user_role = data.role.item_name);
        },

        components: {
          Search
        },
        methods:{
            getClass(i, star){
                if(star > i && star >= i+1){
                    return 'fa-star'
                }else if(star > i && star < i+1){
                    return 'fa-star-half-o'
                }else{
                    return 'fa-star-o'
                }
            },

            resetErrorsReviews: function(){
                this.errors_questions = {
                    doctor_name:false,
                    datetime:false
                }
            },

            getReviews(page, query, isSearched) {
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
                    this.resetErrorsReviews();
                    if(query) {
                        this.doctor_name = query.doctor_name;
                        console.log(query.datetime,new Date(query.datetime) )
                        this.dates = query.datetime ? new Date(query.datetime) : '';
                        this.datetime = this.dates !== '' ? this.dates.getFullYear() + "-" + ('0' + (this.dates.getMonth() + 1)).slice(-2) + "-" +('0'+this.dates.getDate()).slice(-2) : '';
                    }
                    axios.get(`/comments/search?count=${count}&doctor_name=${this.doctor_name}&datetime=${this.datetime}&status=${this.isactivereviews}`)
                        .then(response => {
                            if(response.status === 200) {
                                if(response.data.data!==null) {
                                    if (response.data.data.list != null) {
                                        this.reviews = response.data.data.list;
                                        this.paginations = response.data.data.pagination
                                    }
                                    else {
                                        this.reviews = [];
                                        this.paginations = {};
                                    }
                                }
                                else {
                                    this.reviews = [];
                                    this.paginations = {};
                                }
                            }
                        })
                        .catch(error => {
                            if (error.response.data.data !== null) {
                                if(error.response.data.data) {
                                    error.response.data.data.doctor_name ? this.errors_reviews.doctor_name = error.response.data.data.doctor_name[0] : '';
                                    error.response.data.data.datetime ? this.errors_reviews.datetime = error.response.data.data.datetime[0] : '';
                                }
                            }
                            else {
                                this.reviews = [];
                                this.paginations = {};
                            }
                        })
                }
                else {
                    axios.get(`comments?count=${count}&page=${page}&type=${this.isactivereviews}`).then(
                        response=> {
                            this.reviews=response.data.data.list;
                            this.selected_item = this.reviews[0];
                            this.paginations = response.data.data.pagination
                        }
                    )
                }
            },

            isActiveOrDeactive(isactivereviews) {
                this.isSearched = 0;
                if(this.$route.query.page !== '1') {
                    this.$router.push({ query: { page: 1 }});
                }
                if (isactivereviews === 1) {
                    this.isactivereviews = 1;
                    this.getReviews(this.isactivereviews);
                }
                else if (isactivereviews === 0) {
                    this.isactivereviews = 0;
                    this.getReviews(this.isactivereviews);
                }
                else {
                    this.isactivereviews = 'all';
                    this.getReviews(this.isactivereviews);
                }
            },

            active_comment:function () {
                axios.post('/comments/active',{id: this.selected_item.id}).then(
                    response=>{
                        this.selected_item.status=(this.selected_item.status =='0') ? 1 : 0
                    }
                )
            },

            block_comment:function () {
                axios.post('/comments/block',{id: this.selected_item.id}).then(
                    response=>{
                        this.selected_item.status=(this.selected_item.status == '1') ? 0 : 1
                    }
                )
            },

            resetErrorsReview: function(){
                this.errors_reviews = {
                    doctor_name:false,
                    datetime:false
                }
            },

            selectAllReviews(){
                for(let i in this.reviews){
                    this.reviews[i].checked = this.selectAll
                }
            },

            selectReviews() {
                let selectedId = [];
                for (let i in this.reviews) {
                    if (this.reviews[i].checked) {
                        selectedId.push(this.reviews[i].id)
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
                                axiosPost(url,id).then(data => this.getReviews());
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
                        text: 'Heç bir rəy seçməmisiniz!',
                    })
                }
            },
        }
    }
</script>

<style scoped>

</style>
