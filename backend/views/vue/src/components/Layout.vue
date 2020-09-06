<template>
  <section class="main">
    <div class="container-fluid">
      <div class="row relative">
        <div class="menu-block" v-bind:class="[isActive ? 'toggle' : '']">
          <Sidebar/>
        </div>
        <div class="main__Content" v-bind:class="[isActive ? 'changeWidth' : '']">
          <div class="col-lg-12 col-md-12">
            <loading :is-visible="this.$root._data.isLoading"></loading>
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
  import '../../../../web/cp/css/custom.css';
  import '../../../../web/cp/js/custom';
  export default {
    name: 'Layout',
    data: function(){
        return {
            isActive:false,
            isLoading: true
        }
    },
    components: {
        Sidebar
    },
    mounted() {
      this.$root.$on('clicked', data => {
        this.isActive = data
      });
      this.isLoading = this.$root._data.isLoading;
    }
  }
</script>
