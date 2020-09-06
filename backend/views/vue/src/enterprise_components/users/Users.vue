<template>
    <div class="users blog">
        <breadcrumb name="İSTİFADƏÇİLƏR" icon="fa-user-o" :routes="[{'name': 'İstifadəçilər'}]"></breadcrumb>
        <div class="row">
            <div class="col-md-4">
                <div class="search">
                    <div class="block">
                        <div class="search-blog promo">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Promo kodu daxil edin" aria-describedby="basic-addon1">
                            </div>
                            <button type="submit" class="btn-effect">YOXLA</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
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
                                <th>İstifadəçi adı,soyadı</th>
                                <th>Qeydiyyat nömrəsi</th>
                                <th>Növü</th>
                                <th>E-mail</th>
                                <th>Mobil Telefon</th>
                                <th>Son daxil olma</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(user) in users">
                                    <td>{{user.name}}</td>
                                    <td>{{user.id}}</td>
                                    <td>{{user.type_name}}</td>
                                    <td>{{user.email}}</td>
                                    <td>{{user.phone_number}}</td>
                                    <td>{{user.last_login}}</td>
                                    <td>
                                        <button class="active-user btn" @click="activeUser(user)" v-if="user.status == 0"> Aktiv et</button>
                                        <button class="block-user btn" @click="blockUser(user)" v-else>Blok et</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <pagination :paginations="paginations" :callback="getUsers"></pagination>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {axios} from "../config/axios";

    export default {
        name: "Users",
        data:function () {
            return {
                users:[],
                paginations:{},
            }
        },
        mounted() {
            this.getUsers()
        },
        methods: {
            getUsers(page=1) {
                axios.get('/users'+'?count='+this.$count +'&page=' +page).then(
                    response=> {
                        this.users=response.data.data.list;
                        this.paginations = response.data.data.pagination
                    }
                )
            },

            blockUser(user) {
                axios.post('/users/block',{id: user.id}).then(
                    response=> {
                        user.status= (user.status == '1') ? 0 : 1
                    }
                )
            },

            activeUser(user) {
                axios.post('/users/active',{id: user.id}).then(
                    response=> {
                        user.status= (user.status == '0') ? 1 : 0
                    }
                )
            }
        }
    }
</script>

<style scoped>

</style>