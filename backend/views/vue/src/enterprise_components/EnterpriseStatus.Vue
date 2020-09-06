<template>
  <div class="row blog">
    <div class="col-lg-12 col-md-12">
      <div class="notification-bar" v-if="enterpriseStatus === '0'">
        Əməkdaşlarımız tərəfindən təsdiqləndikdən sonra profiliniz saytda göstəriləcəkdir. Məlumatlarınızı tam doldurmağı unutmayın
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    name: "EnterpriseStatus",
    mounted() {
      this.getEnterpriseData();
    },
    methods: {
      getEnterpriseData:function () {
        axios.get('/enterprises/info').then(
          response=> {
            console.log(response.data.data.enterprise.status, 'test');
            // this.enterpriseStatus=response.data.data.enterprise.status;
          }
        )
      }
    },
    data() {
      return {
        enterpriseStatus: 0
      }
    }
  }
</script>

<style scoped>

</style>
