<template>
    <div class="blog">
        <breadcrumb name="BLOQlar" icon="fa-newspaper-o" :routes="[{'name':'Bloqlar'}]"></breadcrumb>
        <div class="row">
            <div class="col-md-12">
                <div class="appointment statics mb-20">
                    <div class="static-blocks doctor-static">
                        <div class="block cursor" @click="isActiveOrDeactive(1)" :class="isactiveblogs === 1 ? 'active' : '' ">
                            AKTİV BLOQLAR
                        </div>
                        <div class="block cursor" @click="isActiveOrDeactive(0)" :class="isactiveblogs === 0 ? 'active' : '' ">
                            DEAKTİV BLOQLAR
                        </div>
                        <div class="block cursor mbx-0" @click="isActiveOrDeactive('')" :class="isactiveblogs === 'all' ? 'active' : '' ">
                            BÜTÜN BLOQLAR
                        </div>
                    </div>
                </div>
            </div>

            <Search :isnormalsearch="isnormalsearch" :errors_news="errors_news" v-on:SearchRequestedNews = "getBlogs" />

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
                                    <router-link tag="button" class="btn-add" to="/blogs/add">
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
                            <tbody v-if="blogsList.length>0">
                            <tr v-for="blogs in blogsList">
                                <td>
                                    <label class="check-button">
                                        <input type="checkbox" :checked="selectAll" v-model="blogs.checked" @change="selectNews()">
                                        <span class="checkmark"></span>
                                    </label>
                                </td>
                                <td>
                                    <img :src="blogs.thumb" class="img-square" alt="list-item1">
                                </td>
                                <td>{{blogs.headline}}</td>
                                <td>{{blogs.datetime}}</td>
                                <td>{{blogs.news_read}}</td>
                                <td>
                                    <p :class="getClassByStatus(blogs.status)">{{blogs.status_name}}</p>
                                </td>
                                <td>
                                    <span v-if="user_role == 'superadmin'">
                                        <button type="button" class="btn btn-remove-doctor" @click="delete__custom('/news/base-delete-one', blogs.id)">
                                            <span><i class="fa fa-minus-square" aria-hidden="true"></i>Həmişəlik sil</span>
                                        </button>
                                    </span>
                                    <span v-else=""></span>

                                    <button type="button" class="btn btn-remove-doctor" @click="delete__custom('/news/delete-one', blogs.id)">
                                        <span><i class="fa fa-trash" aria-hidden="true"></i>Sil</span>
                                    </button>

                                    <router-link tag="button" class="btn btn-view" :to="{path: 'blogs/'+blogs.id, }">
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
                    <pagination :paginations="paginations" :callback="getBlogs"></pagination>
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
        name: "News",
        data: function () {
            return {
                blogsList: [],
                category_id: '',
                headline: '',
                content: '',
                datetime: '',
                files: '',
                thumb: '',
                id: '',
                status: '',
                status_name: '',
                paginations:{},
                searchInput:'',
                selectAll:false,
                isnormalsearch: 'news',
                isactiveblogs: 1,
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
                selectedId: '',
                user_role: '',
                remove_message: 'Toplu sil',
                hard_remove_message: 'Həmişəlik sil (Toplu)'
            }
        },
        components: {
          Search
        },
        mounted: function () {
            this.getBlogs();
            axiosGet('/api/site/user').then(data => this.user_role = data.role.item_name);
        },
        watch: {
            user_role: function(newVal, oldVal) {
                this.user_role = newVal;
            }
        },
        methods: {
            resetErrorsNews: function(){
                this.errors_news = {
                    id: false,
                    headline: false,
                    datetime: false
                }
            },
            getBlogs(page, query, isSearched) {
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

                if(this.isSearched === 1) {
                    this.resetErrorsNews();
                    if(query) {
                        this.id_n = query.id;
                        this.headline_n = query.headline;
                        this.dates = query.datetime ? new Date(query.datetime) : '';
                        this.datetime_n = this.dates !== '' ? this.dates.getFullYear() + "-" + ('0' + (this.dates.getMonth() + 1)).slice(-2) + "-" +('0'+this.dates.getDate()).slice(-2) : '';
                    }
                    axios.get(`/news/search?page=${page}&category_id=34&headline=${this.headline_n}&id=${this.id_n}&datetime=${this.datetime_n}&status=${this.isactiveblogs}`)
                        .then(response => {
                            if(response.status === 200) {
                                if(response.data.data!==null) {
                                    if (response.data.data.list != null) {
                                        this.blogsList = response.data.data.list;
                                        this.paginations = response.data.data.pagination;

                                    }
                                    else {
                                        this.blogsList = [];
                                        this.paginations = {};
                                    }
                                }
                                else {
                                    this.blogsList = [];
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
                                this.blogsList = [];
                                this.paginations = {};
                            }
                        })
                }
                else {
                    axios.get('/news?id=34'+'&count='+this.$count+'&page='+page+'&type='+this.isactiveblogs).then(
                        response => {
                            if(response.data.data!==null) {
                                if (response.data.data.list != null) {
                                    this.blogsList = response.data.data.list;
                                    this.paginations = response.data.data.pagination
                                }
                                else {
                                    this.blogsList = [];
                                    this.paginations = {};
                                }
                            }
                            else {
                                this.blogsList = [];
                                this.paginations = {};
                            }
                        })
                }
            },
            isActiveOrDeactive(isactiveblogs) {
                this.isSearched = 0;
                if(this.$route.query.page !== '1') {
                    this.$router.push({ query: { page: 1 }});
                }
                if (isactiveblogs === 1) {
                    this.isactiveblogs = 1;
                    this.getBlogs(this.isactiveblogs);
                }
                else if (isactiveblogs === 0) {
                    this.isactiveblogs = 0;
                    this.getBlogs(this.isactiveblogs);
                }
                else {
                    this.isactiveblogs = 'all';
                    this.getBlogs(this.isactiveblogs);
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
                for(let i in this.blogsList){
                    this.blogsList[i].checked = this.selectAll
                }
            },
            selectNews() {
                let selectedId = [];
                for (let i in this.blogsList) {
                    if (this.blogsList[i].checked) {
                        selectedId.push(this.blogsList[i].id)
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
                                axiosPost(url,id).then(data => this.getBlogs());
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
                        text: 'Heç bir bloq seçməmisiniz!',
                    })
                }
            },
        }
    }
</script>

<style scoped>

</style>
