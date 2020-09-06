<template>
    <div class="blog">
        <breadcrumb name="BLOQ" icon="fa-newspaper-o" :routes="[{'name':'BLOQ'}]"></breadcrumb>
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
                                    <router-link tag="button" class="btn-add" to="/blogs/add">
                                        <span><i class="fa fa-plus" aria-hidden="true"></i></span>YENİ
                                    </router-link>
                                </th>
                            </tr>
                            </thead>
                            <tbody v-if="blogsList.length>0">
                              <tr v-for="blogs in blogsList">
                                  <td>
                                      <img :src="blogs.thumb" class="img-square" alt="list-item1">
                                  </td>
                                  <td>{{blogs.headline}}</td>
                                  <td>{{blogs.datetime}}</td>
                                  <td>
                                      <p :class="getClassByStatus(blogs.status)">{{blogs.status_name}}</p>
                                  </td>
                                  <td>
                                      <router-link tag="button" class="btn btn-view" :to="{path: 'blogs/'+blogs.id, }">
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
                    <pagination :paginations="paginations" :callback="getBlogs"></pagination>
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
                isnormalsearch: 0
            }
        },
        mounted: function () {
            this.getBlogs();
        },
        components: {
          Search
        },
        methods: {
            getBlogs(page = 1) {
                axios.get('/news?id=34'+'&count='+this.$count+'&page='+page).then(
                    response => {
                        if(response.data.data !=null) {
                            this.blogsList = response.data.data.list;
                            this.paginations = response.data.data.pagination
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
