<template>
    <div class="reservation blog">
        <breadcrumb name="REZERVASİYA" icon="fa-calendar-check-o" :routes="[{'name':'Rezervasiya'}]"></breadcrumb>
        <div class="row reservations_top_nav">
          <div class="col-md-4">
            <div class="block create_table_block">
              <router-link v-tooltip="tooltip_message" tag="button" class="btn-add" to="/reservations/add">
                <span><i class="fa fa-plus" aria-hidden="true"></i></span>CƏDVƏL YARAT
              </router-link>
            </div>
          </div>
          <div class="col-md-8">
            <Search :isnormalsearch="isnormalsearch" v-on:SearchRequested = "search" />
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="information-blog">
              <div class="block">
                <table class="table table-hover table-striped data-null">
                  <thead>
                  <tr>
                    <th>Həkimin Adı, Soyadı,<br>Qeydiyyat nömrəsi</th>
                    <th>Rezerv edənin Adı, Soyadı,<br>Qeydiyyat nömrəsi</th>
                    <th>Rezerv edənin  emaili</th>
                    <th>Rezerv edənin  telefonu</th>
                    <th class="double-th">
                      Tarix
                    </th>
                    <th>Tarix</th>
                    <th></th>
                  </tr>
                  </thead>
                  <tbody  v-if="reservations.length>0">
                  <tr v-for="reservation in reservations">
                    <td>{{reservation.doctor_name}}<br>
                      {{reservation.doctor_id}}
                    </td>
                    <td>{{reservation.user_name}} <br>
                      {{reservation.user_id}}
                    </td>
                    <td>{{reservation.user_email}}</td>
                    <td>{{reservation.user_phone_number}}</td>
                    <td>{{reservation.date}}</td>
                    <td>{{reservation.time}}
                    </td>
                    <td>
                      <button class="active-user btn pull-right" @click="activeUser(reservation)" v-if="reservation.status == 0"> Aktiv et</button>
                      <button class="block-user btn pull-right" @click="blockUser(reservation)" v-else>Blok et</button>
                    </td>
                  </tr>
                  </tbody>
                  <tbody v-else class="thead-bottom" >
                  <tr>
                    <td colspan="7">Məlumat yoxdur</td>
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

    import {axios} from "../config/doctor_axios";
    import Search from '../Search';
    import '../../../../../web/cp/js/custom';

    export default {
        name: "Reservation",
        data:function () {
            return {
                reservations:[],
                paginations: {},
                isnormalsearch: 1,
                tooltip_message: 'İş saatlarını əlavə edin'
            }
        },
        components: {
          Search
        },
        mounted() {
            this.getReservation();
        },
        methods: {
            getReservation(page=1) {
                axios.get('/appointment'+'?count='+this.$count + '&page='+page).then(
                    response=> {
                       if(response.data.data !=null) {
                           this.reservations = response.data.data.list;
                           this.paginations = response.data.data.pagination
                       }
                    }
                )
            },
            blockUser(reservation) {
                axios.post('/appointment/block',{id: reservation.id}).then(
                    response=> {
                        reservation.status= (reservation.status == '1') ? 1 : 0;
                        this.getReservation()
                    }
                )
            },

            activeUser(reservation) {
                axios.post('/appointment/active',{id: reservation.id}).then(
                    response=> {
                        reservation.status= (reservation.status == '0') ? 0 : 1;
                        this.getReservation()
                    }
                )
            },
            search(query) {
              // console.log(query);
            }
        }
    }
</script>

<style scoped>
  .create_table_block {
    padding: 15px 20px;
    display: flex;
  }

  .btn-add {
    width: 100%;
  }

  .btn-add span {
    padding-right: 10px;
  }

  .reservations_top_nav .col-md-12 {
    padding-right: 0;
    padding-left: 0;
  }

  .create_table_block {
    margin-bottom: 10px;
  }
</style>
