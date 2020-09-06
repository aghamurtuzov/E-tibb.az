<template>
  <div class="blog">
    <breadcrumb name="İdarəçİlər" icon="fa-users" :routes="[{'name':'İdarəçilər'}]"></breadcrumb>
    <div class="row">
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
                  <th>Ad</th>
                  <th>İstifadəçi adı</th>
                  <th>Email</th>
                  <th>Telefon</th>
                  <th>İcazə</th>
                  <th>Status</th>
                  <th>
                    <router-link tag="button" class="btn-add" to="/administrator/add">
                      <span><i class="fa fa-plus" aria-hidden="true"></i></span>YENİ
                    </router-link>
                  </th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="administrator in administratorList">
                  <td>{{administrator.name}}</td>
                  <td>{{administrator.username}}</td>
                  <td>{{administrator.email}}</td>
                  <td>{{administrator.phone}}</td>
                  <td>{{administrator.permission}}</td>
                  <td>
                    <p :class="getClassByStatus(administrator.status)">{{getStatusNameByStatus(administrator.status)}}</p>
                  </td>
                  <td>
                    <router-link tag="button" class="btn btn-view" :to="{path: 'administrator/'+administrator.id, }">
                      Düzəliş et
                    </router-link>
                  </td>
                </tr>
                </tbody>
              </table>
            </div>
            <pagination :paginations="paginations" :callback="getAdministrators"></pagination>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import {axios} from '../config/axios';
  import Search from '../Search';
  export default {
    name: "administratorList",
    data: function () {
      return {
        administratorList: [],
        name: '',
        username: '',
        phone: '',
        email: '',
        permission: '',
        status: '',
        status_name: '',
        id: '',
        paginations:{},
        searchInput:''
      }
    },
    mounted: function () {
      this.getAdministrators();
    },
    methods: {
      getAdministrators() {
         axios.get('/admin').then(
           response => {
//               console.log('ilism', response.data.data);
             this.administratorList = response.data.data;
//             this.paginations = response.data.data.pagination
           })
      },

      getStatusNameByStatus(status) {
        switch (parseInt(status)) {
            case 1:
                return 'aktiv';
                break;
            case 0:
                return 'deaktiv';
                break;
        }
      },
      getClassByStatus(status) {
        switch (parseInt(status)) {
          case 1:
            return 'confirmed';
            break;
          case 0:
            return 'rejected';
            break;
        }
      }
    }
  }
</script>

<style scoped>

</style>
