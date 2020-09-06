<template>
    <div class="doctors blog">
        <breadcrumb name="HƏKİMLƏR" icon="fa-user-md" :routes="[{'name': 'Həkimlər'}]"></breadcrumb>
        <div class="row">
            <div class="col-md-12">
                <div class="appointment statics mb-20">
                    <div class="static-blocks doctor-static">
                        <div class="block cursor" @click="isActiveOrDeactive(1)"
                             :class="isactivedoctor === 1 ? 'active' : '' ">
                            AKTİV HƏKİMLƏR
                        </div>
                        <div class="block cursor" @click="isActiveOrDeactive(0)"
                             :class="isactivedoctor === 0 ? 'active' : '' ">
                            DEAKTİV HƏKİMLƏR
                        </div>
                        <div class="block cursor mbx-0" @click="isActiveOrDeactive('')"
                             :class="isactivedoctor === 'all' ? 'active' : '' ">
                            BÜTÜN HƏKİMLƏR
                        </div>
                    </div>
                </div>
            </div>

            <Search :isnormalsearch="isnormalsearch" :errors_doctor="errors_doctor" v-on:SearchRequested="getDoctors"/>

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
                                    <th>
                                        <label class="check-button">
                                            <input type="checkbox" v-model="selectAll" @change="selectAllDoctors(); selectDoctors();">
                                            <span class="checkmark"></span>
                                        </label>
                                    </th>
                                    <th>Foto</th>
                                    <th>Həkimin adı,<br> soyadı</th>
                                    <!--<th>Qeydiyyat nömrəsi</th>-->
                                    <th>E-mail</th>
                                    <th>Status</th>
                                    <!--<th>Telefon</th>
                                    <th>Klinikası</th>
                                    <th>Ödəniş</th>
                                    <th>Paket</th>
                                    <th>Xidmət</th>
                                    <th>Xidmət tarixi</th>-->
                                    <th style="min-width:280px;">
                                        <div class="doctor-buttons">
                                            <router-link tag="button" class="btn-add" to="/doctors/add">
                                                <span><i class="fa fa-plus" aria-hidden="true"></i></span>YENİ
                                            </router-link>
                                            <span v-if="user_role == 'superadmin'">
                                            <button type="button" class="btn-trash" v-tooltip.top="hard_remove_message"
                                                    @click="delete__custom('/doctors/all-base-delete', 'all')">
                                                <span><i class="fa fa-minus-square" aria-hidden="true"></i></span>
                                            </button>
                                        </span>
                                            <span v-else>
                                        </span>
                                            <button type="button" class="btn-trash" v-tooltip.auto="remove_message"
                                                    @click="delete__custom('/doctors/all-delete', 'all')">
                                                <span><i class="fa fa-trash" aria-hidden="true"></i></span>
                                            </button>
                                        </div>
                                    </th>
                                </tr>
                                </thead>
                                <tbody v-if="doctors.length>0">
                                <tr v-for="doctor in doctors">
                                    <td>
                                        <label class="check-button">
                                            <input type="checkbox" :checked="selectAll" v-model="doctor.checked" @change="selectDoctors()">
                                            <span class="checkmark"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <img class="img-square" v-if="doctor.thumb" :src="doctor.thumb"
                                             alt="hekimler">
                                        <img class="img-square img__bordered" v-else
                                             src="https://e-tibb.az/admin/cp/img/bg-object.png" alt="hekimler">
                                    </td>
                                    <td>
                                        <router-link class="doctor__name" :to="{path: 'doctor/'+doctor.id}"
                                                     v-tooltip.top="doctor_view_message">
                                            {{doctor.name}}
                                        </router-link>
                                    </td>
                                    <!-- <td>{{doctor.user_id }}</td>-->
                                    <td>{{doctor.email}}</td>
                                    <td><p :class="getClassByStatus(doctor.status)">{{doctor.status_name}}</p></td>
                                    <td>
                                    <span v-if="user_role == 'superadmin'">
                                        <button type="button" class="btn btn-remove-doctor"
                                                @click="delete__custom('/doctors/base-delete-one/', doctor.id)">
                                            <span><i class="fa fa-minus-square"
                                                     aria-hidden="true"></i>Həmişəlik sil</span>
                                        </button>
                                    </span>
                                        <span v-else=""></span>

                                        <button type="button" class="btn btn-remove-doctor"
                                                @click="delete__custom('/doctors/delete-one/',  doctor.id)">
                                            <span><i class="fa fa-trash" aria-hidden="true"></i>Sil</span>
                                        </button>
                                        <router-link :to="{path: 'doctor/'+doctor.id}" class="btn btn-view">
                                            <span><i class="fa fa-eye" aria-hidden="true"></i></span>Bax
                                        </router-link>
                                    </td>
                                    <!--<td>{{user.last_login}}</td>-->
                                </tr>
                                </tbody>
                                <tbody v-else class="thead-bottom">
                                <tr>
                                    <td colspan="6">Məlumat yoxdur</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <pagination :paginations="paginations" :callback="getDoctors"></pagination>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {axios} from '../config/axios';
    import Swal from 'sweetalert2';
    import Search from '../Search';
    import {axiosGet, axiosPost} from '../config/helper';
    export default {
        name: "Doctors",
        data: function () {
            return {
                doctors: [],
                doctorsbystatus: [],
                paginations: {},
                status: '',
                status_name: '',
                searchInput: '',
                isactivedoctor: 1,
                selectAll:false,
                selectedId: '',
                remove_message: 'Toplu sil',
                hard_remove_message: 'Həmişəlik sil (Toplu)',
                isnormalsearch: 'doctor',
                doctor_view_message: 'Daha ətraflı',
                activePage: this.$route.params.page,
                user: '',
                user_role: '',
                errors_doctor: {
                    name: false,
                    email: false,
                    number: false,
                    code: false,
                    specialist: false
                },
                isSearched: 0,
                name: '',
                email: '',
                number: '',
                code: '',
                specialist: '',

            }
        },
        watch: {
            user_role: function(newVal, oldVal) {
                this.user_role = newVal;
            }
        },
        mounted: function () {
            this.getDoctors();
            axiosGet('/api/site/user').then(data => this.user_role = data.role.item_name);
        },
        components: {
          Search
        },
        methods: {
            delete__custom(url, ids) {
                if(ids === 'all') {
                    var id = [];
                    if (this.selectedId) {
                        id = JSON.parse(this.selectedId);
                    }
                }
                else {
                    var id = ids;
                }

                if (id.length > 0) {
                    Swal.fire({
                        title: "Bu əməliyyatı icra etməyə əminsiniz?",
                        text: "Əgər silsəniz, geri qaytara bilməyəcəksiniz!",
                        icon: "warning",
                        buttons: true,
                        showCancelButton: true,
                        dangerMode: true,
                    })
                        .then((result) => {
                            if (result.value) {
                                axiosPost(url,id).then(data => this.getDoctors());
                                this.selectedId = '';
                            }
                        });
                }
                else {
                    return Swal.fire({
                        icon: 'error',
                        title: 'Bildiriş',
                        confirmButtonText:
                            '<i class="fa fa-thumbs-up"></i> Oldu!',
                        text: 'Heç bir həkim seçməmisiniz!',
                    })
                }
            },

            getDoctors(page, query, isSearched) {
                if (isSearched) {
                    this.isSearched = isSearched;
                }

                if (this.$route.query.page) {
                    page = this.$route.query.page;
                }
                else {
                    page = 1;
                }
                this.page = page;
                this.$count = 50;

                if (this.isSearched === 1) {
                    this.resetErrorsDoctor();
                    if (query) {
                        this.name = query.name;
                        this.email = query.email;
                        this.number = query.number;
                        this.code = query.code;
                        this.specialist = query.specialist.name ? query.specialist.name : '';
                    }
                    axios.get(`/doctors/search?name=${this.name}&email=${this.email}&number=${this.number}&code=${this.code}&specialist=${this.specialist}&status=${this.isactivedoctor}`)
                        .then(response => {
                            if (response.status === 200) {
                                if (response.data.data !== null) {
                                    if (response.data.data.list != null) {
                                        this.doctors = response.data.data.list;
                                        this.paginations = response.data.data.pagination
                                    }
                                    else {
                                        this.doctors = [];
                                        this.paginations = {};
                                    }
                                }
                                else {
                                    this.doctors = [];
                                    this.paginations = {};
                                }
                            }
                        })
                        .catch(error => {
                            if (error.response.data.data !== null) {
                                if (error.response.data.data) {
                                    error.response.data.data.name ? this.errors_doctor.name = error.response.data.data.name[0] : '';
                                    error.response.data.data.email ? this.errors_doctor.email = error.response.data.data.email[0] : '';
                                    error.response.data.data.number ? this.errors_doctor.number = error.response.data.data.number[0] : '';
                                    error.response.data.data.code ? this.errors_doctor.code = error.response.data.data.code[0] : '';
                                    error.response.data.data.specialist ? this.errors_doctor.specialist = error.response.data.data.specialist[0] : '';
                                }
                            }
                            else {
                                this.doctors = [];
                                this.paginations = {};
                            }
                        })
                }
                else {
                    axios.get(`doctors/index?count=${this.$count}&page=${page}&type=${this.isactivedoctor}`)
                        .then(response => {
                            if (response.data.data !== null) {
                                this.doctors = response.data.data.list;
                                this.paginations = response.data.data.pagination
                            }
                        })
                }
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

            resetErrorsDoctor: function () {
                this.errors_doctor = {
                    name: false,
                    email: false,
                    number: false,
                    code: false,
                    specialist: false
                }
            },

            isActiveOrDeactive(isactivedoctor) {
                this.isSearched = 0;
                if (this.$route.query.page !== '1') {
                    this.$router.push({query: {page: 1}});
                }
                if (isactivedoctor === 1) {
                    this.isactivedoctor = 1;
                    this.getDoctors(this.isactivedoctor);
                }
                else if (isactivedoctor === 0) {
                    this.isactivedoctor = 0;
                    this.getDoctors(this.isactivedoctor);
                }
                else {
                    this.isactivedoctor = 'all';
                    this.getDoctors(this.isactivedoctor);
                }
            },

            selectAllDoctors() {
                for (let i in this.doctors) {
                    this.doctors[i].checked = this.selectAll
                }
            },

            selectDoctors() {
                let selectedId = [];
                for (let i in this.doctors) {
                    if (this.doctors[i].checked) {
                        selectedId.push(this.doctors[i].id)
                    }
                }
                this.selectedId = JSON.stringify(selectedId);
            }
        }
    }
</script>
