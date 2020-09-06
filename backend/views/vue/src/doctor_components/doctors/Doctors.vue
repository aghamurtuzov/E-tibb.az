<template>
    <div class="doctors blog">
        <breadcrumb name="HƏKİMLƏR" icon="fa-user-md" :routes="[{'name': 'həkimlər'}]"></breadcrumb>
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
                        <table class="table table-hover table-striped ">
                            <thead>
                            <tr>
                                <th>Foto</th>
                                <th>Həkimin adı,<br> soyadı</th>
                                <th>Qeydiyyat nömrəsi</th>
                                <th>E-mail</th>
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
                                <td>
                                    <router-link :to="{path: 'doctor/'+doctor.id}" class="btn btn-view">
                                    <span><i class="fa fa-eye" aria-hidden="true"></i></span>Bax
                                </router-link>
                                </td>
                                <!--<td>{{user.last_login}}</td>-->
                            </tr>
                            </tbody>
                        </table>
                        <pagination :paginations="paginations" :callback="getDoctors"></pagination>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {axios} from '../config/axios';

    export default {
        name: "Doctors",
        data: function(){
            return {
                doctors: [],
                paginations: {},
            }
        },
        mounted: function () {
            this.getDoctors()
        },
        methods: {
            getDoctors(page=1){
                axios.get(`doctors/index?count=${this.$count}&page=${page}`)
                    .then(response => {
                        this.doctors = response.data.data.list;
                        this.paginations = response.data.data.pagination
                    })
                    .catch(e => {
                        console.log(e)
                    })
            }
        }
    }
</script>

<style scoped>

</style>
