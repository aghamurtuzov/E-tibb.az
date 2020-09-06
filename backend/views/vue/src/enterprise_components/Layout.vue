<template>
  <section class="main">
    <div class="container-fluid">
      <div class="row relative">
        <div class="menu-block" v-bind:class="[isActive ? 'toggle' : '']">
          <Sidebar/>
        </div>

        <div class="main__Content" v-bind:class="[isActive ? 'changeWidth' : '']">
          <div class="col-lg-12 col-md-12">
            <div class="notification-bar deactive__Notification" v-if="enterpriseStatus === '0'">
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
  import {axios} from './config/enterprise_axios';
  import '../../../../web/cp/css/custom.css';
  export default {
    name: 'Layout',
    mounted() {
      this.$root.$on('clicked', data => {
        this.isActive = data
      });
      this.getEnterpriseData();
    },
    methods: {
      getEnterpriseData:function () {
        axios.get('/enterprises/info').then(
          response=> {
            this.enterpriseStatus=response.data.data.enterprise.status;
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
        enterpriseStatus:0
      }
    }
  }
</script>
