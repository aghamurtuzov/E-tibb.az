<template>
    <div class="blog">
        <breadcrumb name="XƏBƏRLƏR" icon="fa-newspaper-o" :routes="[{'name':'XƏBƏRLƏR'}]"></breadcrumb>
        <div class="row">
            <div class="col-md-12">
              <Search :isnormalsearch="isnormalsearch" v-on:SearchRequested = "search" />
            </div>
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
                                <th>Foto</th>
                                <th>Başlıq</th>
                                <th>Tarix</th>
                                <th>Status</th>
                                <th>
                                    <router-link tag="button" class="btn-add" to="/news/add">
                                        <span><i class="fa fa-plus" aria-hidden="true"></i></span>YENİ
                                    </router-link>
                                </th>
                            </tr>
                            </thead>
                            <tbody v-if="newsList.length>0">
                                <tr v-for="news in newsList">
                                    <td>
                                        <img :src="news.thumb" class="img-square" alt="list-item1">
                                    </td>
                                    <td>{{news.headline}}</td>
                                    <td>{{news.datetime}}</td>
                                    <td>
                                        <p :class="getClassByStatus(news.status)">{{news.status_name}}</p>
                                    </td>
                                    <td>
                                        <router-link tag="button" class="btn btn-view" :to="{path: 'news/'+news.id, }">
                                            Düzəliş et
                                        </router-link>
                                    </td>
                                </tr>
                            </tbody>
                            <tbody v-else class="thead-bottom" >
                            <tr>
                              <td colspan="5">Məlumat yoxdur</td>
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
    import {axios} from '../config/enterprise_axios';
    import Search from '../Search';
    export default {
        name: "News",
        data: function () {
            return {
                newsList: [],
                paginations: {},
                status: '',
                isnormalsearch: 0
            }
        },
        components: {
          Search
        },
        mounted: function () {
            this.getNews();
        },
        methods: {
            getNews(page = 1) {
                axios.get('/news?id=37'+'&count='+this.$count+'&page='+page)
                    .then(response => {
                        if(response.data.data !==null) {
                            this.newsList = response.data.data.list;
                            this.paginations = response.data.data.pagination;
                        }
                    })
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
            search(query) {
            console.log(query);
          }
        }
    }
</script>

<style scoped>

</style>
