<template>
  <section class="main">
    <div class="container-fluid">
      <div class="row relative">
        <div class="col-lg-2 col-md-2 menu-block" v-bind:class="[isActive ? 'toggle' : '']">
          <Sidebar/>
        </div>
        <div class="col-lg-10 col-md-10" v-bind:class="[isActive ? 'changeWidth' : '']">
          <div class="row blog">
            <div class="col-lg-12 col-md-12">
              <div class="notification-bar" v-if="doctorStatus === '0'">
                sdsdsdƏməkdaşlarımız tərəfindən təsdiqləndikdən sonra profiliniz saytda göstəriləcəkdir. Məlumatlarınızı tam doldurmağı unutmayın
              </div>
            </div>
          </div>

          <router-view></router-view>
        </div>
      </div>
    </div>
  </section>
</template>

<script>

  import Sidebar from './Sidebar.vue';
  // import EnterpriseStatus from './EnterpriseStatus.vue';
  import {axios} from "./config/doctor_axios";
  export default {
    name: 'Layout',
    mounted() {
      this.$root.$on('clicked', data => {
        this.isActive = data
      });
      this.getDoctorData();
    },
    methods: {
      getDoctorData:function () {
        axios.get('/doctors/info').then(
          response=> {
            this.doctorStatus=response.data.data.doctor.status;
          }
        )
      }
    },
    components: {
      Sidebar
    },
    data() {
      return {
        isActive:false,
        doctorStatus: 0
      }
    }
  }
</script>
