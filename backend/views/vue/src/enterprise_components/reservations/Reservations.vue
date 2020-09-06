<template>
    <div class="reservation blog">
        <breadcrumb name="REZERVASİYA" icon="fa-ticket" :routes="[{'name':'Rezervasiya'}]"></breadcrumb>
        <div class="row">
            <div class="col-md-12">
                <div class="search">
                    <div class="block">
                        <select class="form-control selectpicker" name="money" title="Axtarış növünü seçin">
                            <option value="">Qeydiyyat nömrəsinə görə</option>
                            <option value="">Klinikaya görə</option>
                            <option value="">Həkimə görə</option>
                            <option value="">Pasient adına görə</option>
                            <option value="">Saat aralığına görə</option>
                            <option value="">Tarixə görə</option>
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
                        <table class="table table-hover table-striped">
                            <thead>
                            <tr>
                                <th>Həkimin Adı, Soyadı,<br>Qeydiyyat nömrəsi</th>
                                <th>Rezerv edənin Adı, Soyadı,<br>Qeydiyyat nömrəsi</th>
                                <th>Rezerv edənin  emaili</th>
                                <th>Rezerv edənin  telefonu</th>
                                <th>Tarix</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="reservation in reservations">
                                <td>{{reservation.doctor_name}}<br>
                                    {{reservation.doctor_id}}
                                </td>
                                <td>{{reservation.fullname}} <br>
                                    {{reservation.id}}
                                </td>
                                <td>{{reservation.email}}</td>
                                <td>{{reservation.telefon}}</td>
                                <td>{{reservation.date}}</td>
                            </tr>
                            </tbody>
                        </table>
                        <pagination v-if="paginations" :paginations="paginations" :callback="getReservation"></pagination>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {axios} from "../config/enterprise_axios";

    export default {
        name: "Reservation",
        data:function () {
            return {
                reservations:[],
                paginations: {},
            }
        },
        mounted() {
            this.getReservation()
        },
        methods: {
            getReservation(page=1) {
                axios.get('/appointment'+'?count='+this.$count + '&page='+page).then(
                    response=> {
                        this.reservations = response.data.data.list;
                        this.paginations = response.data.data.pagination
                    }
                )
            }
        }
    }
</script>

<style scoped>

</style>