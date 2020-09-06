<template>
    <div class="pharmacy blog">
        <breadcrumb :name=breadcrumbs.name :icon=breadcrumbs.icon :routes=breadcrumbs.routes></breadcrumb>
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
                                <th>Obyektin adı</th>
                                <!--<th>Qeydiyyat nömrəsi</th>-->
                                <th>Əlaqələndirici <br> şəxsin adı, soyadı </th>
                                <th>Əlaqələndirici <br> şəxs telefonu </th>
                                <th>Əlaqələndirici <br> şəxs E-mail </th>
                               <!--<th>Ödəniş</th>
                                <th>Paket</th>
                                <th>Xidmət</th>
                                <th>Xidmət tarixi</th>-->
                                <th>
                                    <router-link tag="button" class="btn-add" to="/enterprises/add">
                                        <span><i class="fa fa-plus" aria-hidden="true"></i></span>YENİ
                                    </router-link>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="enterprise in enterprises">
                                <!--<td>
                                    <label class="check-button">
                                        <input type="checkbox">
                                        <span class="checkmark"></span>
                                    </label>
                                </td>-->
                                <td>
                                    <img :src="enterprise.thumb" alt="list-item1">
                                </td>
                                <td>{{enterprise.name}}</td>
                                <td>{{enterprise.user_name}}</td>
                                <td>{{enterprise.user_phone_number}}</td>
                                <td>{{enterprise.user_email}}</td>
                                <td>
                                    <router-link :to="{path: '/enterprises/'+module+'/'+enterprise.id}" class="btn btn-view">
                                        <span><i class="fa fa-eye" aria-hidden="true"></i></span>Bax
                                    </router-link>
                                </td>
                                <!--<td>{{user.last_login}}</td>-->
                            </tr>
                            </tbody>
                        </table>
                        <pagination v-if="paginations" :paginations="paginations" :callback="getEnterprises"></pagination>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {axios} from "../config/axios";
    export default {
        name: "Enterprises",
        data:function () {
            return {
                enterprises:[],
                paginations: {},
                breadcrumbs: {
                    name: '',
                    icon: '',
                    routes: []
                },
                module: '',
            }
        },
        computed: {
            id(){
                return this.$route.query.id
            }
        },
        watch: {
            id(){
                this.getEnterprises();
                this.setBreadcrumbs()
            }
        },
        mounted() {
            this.getEnterprises();
            this.setBreadcrumbs()
        },
        methods: {
            getEnterprises:function (page=1) {
                axios.get('/enterprises?id='+this.id+'&count='+this.$count+'&page='+page,this.enterprises).then(
                    response=> {
                        this.enterprises = response.data.data !== null ? response.data.data.list : [];
                        this.paginations = response.data.data !== null ? response.data.data.pagination : false
                    }
                )
            },

            setBreadcrumbs: function () {
                switch(parseInt(this.id)){
                    case 1:
                        this.module = 'clinics';
                        this.breadcrumbs = {
                            name: 'KLİNİKALAR',
                            routes: [{name: 'Klinikalar'}],
                            icon: 'fa-hospital-o'
                        };
                        break;
                    case 6:
                        this.module = 'pharmacy';
                        this.breadcrumbs = {
                                name: 'APTEKLƏR',
                                routes: [{name: 'Apteklər'}],
                                icon: 'fa-plus'
                            };
                        break;
                    case 21:
                        this.module = 'medical';
                        this.breadcrumbs = {
                            name: 'TİBBİ MAĞAZA',
                            routes: [{name: 'Tibbi mağaza'}],
                            icon: 'fa-shopping-cart'
                        };
                        break;
                    default:
                        break;
                }
            },
        }
    }
</script>

<style scoped>

</style>
