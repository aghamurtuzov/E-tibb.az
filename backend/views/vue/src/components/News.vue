<template>
    <div class="blog">
        <breadcrumb name="XƏBƏRLƏR" icon="fa-newspaper-o" :routes="[{'name':'XƏBƏRLƏR'}]"></breadcrumb>
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
            <div class="col-md-12">
                <div class="information-blog">
                    <div class="block">
                        <ul class="list-inline information-head">
                            <li>
                                <p>Foto</p>
                            </li>
                            <li>
                                <p>Adı, Soyadı</p>
                            </li>
                            <li>
                                <p>Qeydiyyat №</p>
                            </li>
                            <li>
                                <p>Başlıq</p>
                            </li>
                            <li>
                                <p>Tarix</p>
                            </li>
                            <li>
                                <p>Status</p>
                            </li>
                            <li>
                                <button type="button" class="btn-add">
                                    <span><i class="fa fa-plus" aria-hidden="true"></i></span>YENİ
                                </button>
                            </li>
                        </ul>
                        <ul class="list-inline information-body bg-gray" v-for="news in newsList">
                            <li>
                                <img :src="news.thumb" alt="list-item1">
                            </li>
                            <li>
                                <p>{{}}</p>
                            </li>
                            <li>
                                <p>{{news.id}}</p>
                            </li>
                            <li>
                                <p>Yorğunluq nə zaman xəstəlik əlamətidir?</p>
                            </li>
                            <li>
                                <p class="date">19.05.2019</p>
                            </li>
                            <li>
                                <p class="rejected">İmtina olunub</p>
                            </li>
                            <li>
                                <a href="" class="btn btn-view">
                                    <span><i class="fa fa-eye" aria-hidden="true"></i></span>Bax
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {axios} from 'config/axios';
    export default {
        name: "News",
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
                id: ''
            }
        },
        mounted: function () {
            this.getNews();
        },
        methods: {
            getNews(page = 1) {
                axios.get(`news/index?count=${this.$count}&page=${page}`)
                    .then(response => {
                        this.newsList = response.data.data.list;
                        this.paginations = response.data.data.pagination;
                    })
                    .catch(e => {
                        console.log(e)
                    })
            },
        }
    }
</script>

<style scoped>

</style>
