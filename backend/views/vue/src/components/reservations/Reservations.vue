<template>
    <div class="reservation blog">
        <breadcrumb name="REZERVASİYA" icon="fa-calendar-check-o" :routes="[{'name':'Rezervasiya'}]"></breadcrumb>
        <div class="row">
            <Search :isnormalsearch="isnormalsearch" v-on:SearchRequested = "search" />
            <div class="col-md-12">
                <div class="information-blog">
                    <div class="block">
                        <table class="table table-hover table-striped data-null">
                            <thead v-if="reservations.length > 0">
                                <tr>
                                    <th>Həkimin Adı, Soyadı,<br>Qeydiyyat nömrəsi</th>
                                    <th>Rezerv edənin Adı, Soyadı,<br>Qeydiyyat nömrəsi</th>
                                    <th>Rezerv edənin  emaili</th>
                                    <th>Rezerv edənin  telefonu</th>
                                    <th class="double-th">Tarix</th>
                                </tr>
                            </thead>
                            <thead v-else class="thead-bottom" >
                                <tr>
                                    <th>Məlumat yoxdur</th>
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
    import {axios} from "../config/axios";
    import Search from '../Search';
    export default {
        name: "Reservation",
        data:function () {
            return {
                reservations:[],
                paginations: {},
                isnormalsearch: 0
            }
        },
        mounted() {
            this.getReservation()
        },
        components: {
          Search
        },
        methods: {
            getReservation(page=1) {
                axios.get('/appointment'+'?count='+this.$count + '&page='+page).then(
                    response=> {
                        if (response.data.data !== null) {
                            this.reservations = response.data.data.list;
                            this.paginations = response.data.data.pagination
                        }
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
