<template>
    <div class="pharmacy blog">
        <breadcrumb name="KLİNİKALAR" icon="fa-hospital-o" :routes="[{'name':'Klinikalar'}]"></breadcrumb>
        <!--<div class="head-line">
            <p><span><i class="fa fa-hospital-o" aria-hidden="true"></i></span>KLİNİKALAR</p>
            <ol class="breadcrumb">
                <li><a href="index.html">Admin</a></li>
                <li class="active">Klinikalar</li>
            </ol>
        </div>-->
        <div class="row">

            <Search v-on:SearchRequested = "search" />

            <div class="col-md-12">
                <div class="information-blog">
                    <div class="block">
                        <ul class="list-inline information-head">
                            <!--<li>
                                <label class="check-button">
                                    <input type="checkbox" >
                                    <span class="checkmark"></span>
                                </label>
                            </li>-->
                            <li>
                                <p>Foto</p>
                            </li>
                            <li>
                                <p>Obyektin adı</p>
                            </li>
                            <li>
                                <p>Qeydiyyat<p>
                                <p>nömrəsi</p>
                            </li>
                            <li>
                                <p>Əlaqələndirici<p>
                                <p>şəxsin adı, soyadı</p>
                            </li>
                            <li>
                                <p>Əlaqələndirici </p>
                                <p>şəxs telefonu</p>
                            </li>
                            <li>
                                <p>Əlaqələndirici </p>
                                <p>şəxs E-mail</p>
                            </li>
                            <li>
                                <p>Ödəniş</p>
                            </li>
                            <li>
                                <p>Paket</p>
                            </li>
                            <li>
                                <p>Xidmət</p>
                            </li>
                            <li>
                                <p>Xidmət tarixi</p>
                            </li>
                            <li>
                                <router-link tag="button" class="btn-add" to="/clinics/add">
                                    <span><i class="fa fa-plus" aria-hidden="true"></i></span>YENİ
                                </router-link>
                            </li>
                        </ul>
                        <ul v-for="clinic in clinics" class="list-inline information-body">
                            <!--<li>
                                <label class="check-button">
                                    <input type="checkbox" >
                                    <span class="checkmark"></span>
                                </label>
                            </li>-->
                            <li>
                                <img src="/new/img/clinic-list.png" alt="list-item1">
                            </li>
                            <li>
                                <p>{{clinic.name}}</p>
                            </li>
                            <li>
                                <p>00006</p>
                            </li>
                            <li>
                                <p>Valeh Əliyev</p>
                            </li>
                            <li>
                                <p>+994 55 123 45 67</p>
                            </li>
                            <li>
                                <p>info@klinika.com</p>
                            </li>
                            <li>
                                <p>Nəğd</p>
                            </li>
                            <li>
                                <p>1 Aylıq</p>
                            </li>
                            <li>
                                <p>Premium</p>
                            </li>
                            <li>
                                <p>15.05.2019 - 15.06.2019</p>
                            </li>
                            <li>
                                <a href="" class="btn btn-view">
                                    <span><i class="fa fa-eye" aria-hidden="true"></i></span>Bax
                                </a>
                            </li>
                        </ul>
                        <pagination :paginations="paginations" :callback="getClinics"></pagination>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {axios} from "../config/axios";
    import Search from '../Search';
    export default {
        name: "Clinics",
        data:function () {
            return {
                id: this.$route.params.id,
                clinics:[],
                paginations: {},
            }
        },
        mounted() {
            this.getClinics();
          console.log('clinic1');
        },
        components: {
          Search
        },
        methods: {
            getClinics:function () {
                axios.get('clinics/enterprises?'+this.id+'?count='+this.$count+'&page='+page,this.clinics).then(
                    response=> {
                        this.clinics = response.data.data.list;
                        this.paginations = response.data.data.pagination
                    }
                )
            },

            search(query) {
            }
        }
    }
</script>

<style scoped>

</style>
