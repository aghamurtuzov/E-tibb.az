<template>
    <div class="doctors blog">
        <breadcrumb name="HƏKİMLƏR" icon="fa-user-md" :routes="[{'name': 'Həkimlər'}]"></breadcrumb>
        <div class="row">
            <div class="col-md-12">
              <Search :isnormalsearch="isnormalsearch" v-on:SearchRequested = "search" />
            </div>
            <div class="col-md-12" v-if="doctors.length>0">
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
                                <th>Həkimin adı,<br> soyadı</th>
                                <th>Qeydiyyat nömrəsi</th>
                                <th>E-mail</th>
                                <th>Status</th>
                                <!--<th>Telefon</th>
                                <th>Klinikası</th>
                                <th>Ödəniş</th>
                                <th>Paket</th>
                                <th>Xidmət</th>
                                <th>Xidmət tarixi</th>-->
                                <th>
                                    <router-link tag="button" class="btn-add" to="/doctors/add">
                                        <span><i class="fa fa-plus" aria-hidden="true"></i></span>YENİ
                                    </router-link>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="doctor in doctors">
                                <td>
                                    <img :src="doctor.thumb" alt="list-item1">
                                </td>
                                <td>{{doctor.name}}</td>
                                <td>{{doctor.id}}</td>
                                <td>{{doctor.email}}</td>
                                <td><p :class="getClassByStatus(doctor.status)">{{doctor.status_name}}</p></td>
                                <td>
                                    <router-link :to="{path: 'doctor/'+doctor.id}" class="btn btn-view">
                                        <span><i class="fa fa-eye" aria-hidden="true"></i></span>Bax
                                    </router-link>
                                </td>
                                <!--<td>{{user.last_login}}</td>-->
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <pagination :paginations="paginations" :callback="getDoctors"></pagination>
                  </div>
                </div>
            </div>
            <div class="col-md-12 changeData" v-else>
              <div class="block">
                <h5>Məlumat yoxdur</h5>
              </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {axios} from '../config/enterprise_axios';
    import Search from '../Search';
    export default {
        name: "Doctors",
        data: function(){
            return {
                doctors: [],
                paginations: {},
                status: '',
                status_name: '',
                isnormalsearch: 0
            }
        },
        mounted: function () {
            this.getDoctors()
        },
        components: {
          Search
        },
        methods: {
            getDoctors(page=1){
                axios.get(`doctors/index?count=${this.$count}&page=${page}`)
                    .then(response => {
                        if(response.data.data !== null) {
                            this.doctors = response.data.data.list;
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
