<template>
  <section class="main">
    <div class="container-fluid">
      <div class="row relative">
        <div class="menu-block" v-bind:class="[isActive ? 'toggle' : '']">
          <Sidebar/>
        </div>
        <div class="main__Content" v-bind:class="[isActive ? 'changeWidth' : '']">
          <div class="col-lg-12 col-md-12">
            <div class="notification-bar deactive__Notification" v-if="doctorStatus === '0'">
              Əməkdaşlarımız tərəfindən təsdiqləndikdən sonra profiliniz saytda göstəriləcəkdir. Məlumatlarınızı tam doldurmağı unutmayın
            </div>
            <router-view></router-view>
          </div>
        </div>
        <div class="curtain_bg"  v-bind:class="[isActive ? 'active' : '']"></div>
      </div>
    </div>
  </section>
</template>

<script>
  import Sidebar from './Sidebar.vue';
  // import EnterpriseStatus from './EnterpriseStatus.vue';
  import {axios} from "./config/doctor_axios";
  import '../../../../web/cp/css/custom.css';
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
