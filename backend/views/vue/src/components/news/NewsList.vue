<template>
    <div class="blog">
<!--        <breadcrumb name="XƏBƏRLƏR" icon="fa-newspaper-o" :routes="[{'name':'Xəbərlər'}]"></breadcrumb>-->
        <breadcrumb :name=breadcrumbs.name :icon=breadcrumbs.icon :routes=breadcrumbs.routes></breadcrumb>
        <div class="row">
            <div class="col-md-12">
                <div class="appointment statics mb-20">
                    <div v-if="isNewsPage === '37'" class="static-blocks doctor-static">
                        <div class="block cursor" @click="isActiveOrDeactive(1)" :class="isactivenews === 1 ? 'active' : '' ">
                            AKTİV XƏBƏRLƏR
                        </div>
                        <div class="block cursor" @click="isActiveOrDeactive(0)" :class="isactivenews === 0 ? 'active' : '' ">
                            DEAKTİV XƏBƏRLƏR
                        </div>
                        <div class="block cursor mbx-0" @click="isActiveOrDeactive('')" :class="isactivenews === 'all' ? 'active' : '' ">
                            BÜTÜN XƏBƏRLƏR
                        </div>
                    </div>
                    <div v-else="" class="static-blocks doctor-static">
                        <div class="block cursor" @click="isActiveOrDeactive(1)" :class="isactivenews === 1 ? 'active' : '' ">
                            AKTİV BLOQLAR
                        </div>
                        <div class="block cursor" @click="isActiveOrDeactive(0)" :class="isactivenews === 0 ? 'active' : '' ">
                            DEAKTİV BLOQLAR
                        </div>
                        <div class="block cursor mbx-0" @click="isActiveOrDeactive('')" :class="isactivenews === 'all' ? 'active' : '' ">
                            BÜTÜN BLOQLAR
                        </div>
                    </div>
                </div>
            </div>

            <Search :isnormalsearch="isnormalsearch" :errors_news="errors_news" v-on:SearchRequestedNews = "getNews" />

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
                                        <input type="checkbox" v-model="selectAll" @change="selectAllNews(); selectNews()">
                                        <span class="checkmark"></span>
                                    </label>
                                </th>
                                <th>Foto</th>
                                <th>Başlıq</th>
                                <th>Tarix</th>
                                <th>Oxunma sayı</th>
                                <th>Status</th>
                                <th style="min-width:280px;">
                                    <router-link tag="button" class="btn-add" :to="{path: module+'/add'}">
                                        <span><i class="fa fa-plus" aria-hidden="true"></i></span>YENİ
                                    </router-link>
                                    <span v-if="user_role == 'superadmin'">
                                        <button type="button" class="btn-trash" v-tooltip.top="hard_remove_message" @click="delete__custom('/news/all-base-delete','all')">
                                            <span><i class="fa fa-minus-square" aria-hidden="true"></i></span>
                                        </button>
                                    </span>
                                    <span v-else>
                                    </span>
                                    <button type="button" class="btn-trash" v-tooltip.auto="remove_message" @click="delete__custom('/news/all-delete', 'all')">
                                        <span><i class="fa fa-trash" aria-hidden="true"></i></span>
                                    </button>
                                </th>
                            </tr>
                            </thead>
                            <tbody v-if="newsList.length>0">
                            <tr v-for="news in newsList">
                                <td>
                                    <label class="check-button">
                                        <input type="checkbox" :checked="selectAll" v-model="news.checked" @change="selectNews()">
                                        <span class="checkmark"></span>
                                    </label>
                                </td>
                                <td>
                                    <img class="img-square" v-if="news.thumb" :src="news.thumb"
                                         alt="xeberler">
                                    <img class="img-square img__bordered" v-else
                                         src="https://e-tibb.az/admin/cp/img/bg-object.png" alt="xeberler">
                                </td>
                                <td>
                                    <router-link class="doctor__name" :to="{path: module+'/'+news.id}"
                                                 v-tooltip.top="news_view_message">
                                        {{news.headline}}
                                    </router-link>
                                </td>
                                <td>{{formatDatetime(news.datetime)}}</td>
                                <td>{{news.news_read}}</td>
                                <td>
                                    <p :class="getClassByStatus(news.status)">{{news.status_name}}</p>
                                </td>
                                <td>
                                      <span v-if="user_role == 'superadmin'">
                                        <button type="button" class="btn btn-remove-doctor" @click="delete__custom('/news/base-delete-one', news.id)">
                                            <span><i class="fa fa-minus-square" aria-hidden="true"></i>Həmişəlik sil</span>
                                        </button>
                                    </span>
                                    <span v-else=""></span>

                                    <button type="button" class="btn btn-remove-doctor" @click="delete__custom('/news/delete-one', news.id)">
                                        <span><i class="fa fa-trash" aria-hidden="true"></i>Sil</span>
                                    </button>

                                    <router-link tag="button" class="btn btn-view" :to="{path: module+'/'+news.id}">
                                        Düzəliş et
                                    </router-link>
                                </td>
                            </tr>
                            </tbody>
                            <tbody v-else class="thead-bottom" >
                            <tr>
                                <td colspan="7">Məlumat yoxdur</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <pagination :paginations="paginations" :callback="getNews"></pagination>
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
        name: "NewsList",
        data: function () {
            return {
                newsList: [],
                paginations: {},
                category_id: '',
                headline: '',
                content: '',
                datetime: '',
                files: '',
                thumb: '',
                status: '',
                status_name: '',
                selectAll:false,
                selectedId: '',
                searchInput:'',
                user_role: '',
                remove_message: 'Toplu sil',
                hard_remove_message: 'Həmişəlik sil (Toplu)',
                isnormalsearch: 'news',
                isactivenews: 1,
                errors_news:{
                    id: false,
                    headline: false,
                    datetime: false
                },
                isSearched: 0,
                id_n: '',
                headline_n:'',
                dates: '',
                datetime_n:'',
                isNewsPage: '',
                breadcrumbs: {
                    name: '',
                    icon: '',
                    routes: []
                },
                module: '',
                news_view_message: 'Ətraflı',
                monthNames: ["Yanvar", "Fevral", "Mart", "Aprel", "May", "İyun",
                    "İyul", "Avqust", "Sentyabr", "Oktyabr", "Noyabr", "Dekabr"
                ]
            }
        },
        computed: {
            id(){
                return this.$route.query.id
            }
        },
        mounted: function () {
            this.getNews();
            this.setBreadcrumbs();
            axiosGet('/api/site/user').then(data => this.user_role = data.role.item_name);
            this.isNewsPage = this.$route.query.id;
        },
        watch: {
            user_role: function(newVal, oldVal) {
                this.user_role = newVal;
            },
            id(){
                this.getNews();
                this.setBreadcrumbs();
            },
            $route (to, from){
                this.isNewsPage = this.$route.query.id;
            }
        },
        components: {
          Search
        },
        methods: {
            formatDatetime(date) {
                let d = new Date(date);
                return d.getDate() + ' ' + this.monthNames[d.getMonth()] + ' ' + d.getFullYear() + ' ' + d.getHours() + ':' + (d.getMinutes() < 10 ? '0'+ d.getMinutes() : d.getMinutes());
            },
            setBreadcrumbs: function () {
                switch(parseInt(this.id)){
                    case 34:
                        this.module = 'blogs';
                        this.breadcrumbs = {
                            name: 'BLOQLAR',
                            routes: [{name: 'Bloqlar'}],
                            icon: 'fa-newspaper-o'
                        };
                        break;
                    case 37:
                        this.module = 'news';
                        this.breadcrumbs = {
                            name: 'XƏBƏRLƏR',
                            routes: [{name: 'Xəbərlər'}],
                            icon: 'fa-newspaper-o'
                        };
                        break;
                    default:
                        break;
                }
            },
            resetErrorsNews: function(){
                this.errors_news = {
                    id: false,
                    headline: false,
                    datetime: false
                }
            },
            getNews(page, query, isSearched) {
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
                let category_id = this.$route.query.id;
                let count = 50;

                if(this.isSearched === 1) {
                    this.resetErrorsNews();
                    if(query) {
                        this.id_n = query.id;
                        this.headline_n = query.headline;
                        this.dates = query.datetime ? new Date(query.datetime) : '';
                        this.datetime_n = this.dates !== '' ? this.dates.getFullYear() + "-" + ('0' + (this.dates.getMonth() + 1)).slice(-2) + "-" +('0'+this.dates.getDate()).slice(-2) : '';
                    }
                    axios.get(`/news/search?page=${page}&count=${count}&category_id=${category_id}&headline=${this.headline_n}&id=${this.id_n}&datetime=${this.datetime_n}&status=${this.isactivenews}`)
                        .then(response => {
                            if(response.status === 200) {
                                if(response.data.data!==null) {
                                    if (response.data.data.list != null) {
                                        this.newsList = response.data.data.list;
                                        this.paginations = response.data.data.pagination;

                                    }
                                    else {
                                        this.newsList = [];
                                        this.paginations = {};
                                    }
                                }
                                else {
                                    this.newsList = [];
                                    this.paginations = {};
                                }
                            }
                        })
                        .catch(error => {
                            if (error.response.data.data !== null) {
                                if(error.response.data.data) {
                                    error.response.data.data.id ? this.errors_news.id = error.response.data.data.id[0] : '';
                                    error.response.data.data.headline ? this.errors_news.headline = error.response.data.data.headline[0] : '';
                                    error.response.data.data.datetime ? this.errors_news.datetime = error.response.data.data.datetime[0] : '';
                                }
                            }
                            else {
                                this.newsList = [];
                                this.paginations = {};
                            }
                        })
                }
                else {
                    axios.get(`news?id=${category_id}&count=${count}&page=${page}&type=${this.isactivenews}&search=${this.searchInput}`)
                        .then(response => {
                            if((response.data.data !== null) ) {
                                this.newsList = response.data.data.list;
                                this.paginations = response.data.data.pagination;
                            }
                            else {
                                this.newsList = [];
                                this.paginations = {}
                            }
                        })
                }
            },
            isActiveOrDeactive(isactivenews) {
                this.isSearched = 0;
                if(this.$route.query.page !== '1') {
                    this.$router.push({ query: { page: 1, id: this.$route.query.id }});
                }
                if (isactivenews === 1) {
                    this.isactivenews = 1;
                    this.getNews(this.isactivenews);
                }
                else if (isactivenews === 0) {
                    this.isactivenews = 0;
                    this.getNews(this.isactivenews);
                }
                else {
                    this.isactivenews = 'all';
                    this.getNews(this.isactivenews);
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
            selectAllNews(){
                for(let i in this.newsList){
                    this.newsList[i].checked = this.selectAll
                }
            },
            selectNews() {
                let selectedId = [];
                for (let i in this.newsList) {
                    if (this.newsList[i].checked) {
                        selectedId.push(this.newsList[i].id)
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
                                console.log('idididid', id);
                                axiosPost(url,id).then(data => this.getNews());
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
                        text: 'Heç bir xəbər seçməmisiniz!',
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
