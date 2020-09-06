<template>
  <div class="stocks-inner new-addition blog">
    <breadcrumb name="İdarəçİ redaktə et" icon="fa-certificate" :routes="[{'name':'İdarəçi redaktə et'}]"></breadcrumb>
    <div class="row">
      <div class="col-md-6">
        <div class="blog-file-upload">
          <div class="block">
            <div class="row">
              <div class="col-md-6">
                <div class="general-info">
                  <div class="input-group input-up">
                    <label>
                      <input type="text" placeholder=" " class="form-control inputText"
                             v-model="administratorData.name">
                      <span>Ad</span>
                    </label>
                  </div>
                  <span class="validation" v-if="errors.name">{{errors.name}}</span>
                </div>
              </div>
              <div class="col-md-6">
                <div class="general-info">
                  <div class="input-group input-up">
                    <label>
                      <input type="text" placeholder=" " class="form-control inputText"
                             v-model="administratorData.username">
                      <span>İstifadəçi adı</span>
                    </label>
                  </div>
                  <span class="validation" v-if="errors.username">{{errors.username}}</span>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="general-info">
                  <div class="input-group input-up">
                    <label>
                      <input type="text" placeholder=" " class="form-control inputText"
                             v-mask="'##########'" v-model="administratorData.phone">
                      <span>Telefon nömrəsi (Məs: 0502223344)</span>
                    </label>
                  </div>
                  <span class="validation" v-if="errors.phone">{{errors.phone}}</span>
                </div>
              </div>
              <div class="col-md-6">
                <div class="general-info">
                  <div class="input-group input-up">
                    <label>
                      <input type="text" placeholder=" " class="form-control inputText"
                             v-model="administratorData.email">
                      <span>Email</span>
                    </label>
                  </div>
                  <span class="validation" v-if="errors.email">{{errors.email}}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="blog-file-upload">
          <div class="block">
            <div class="row">
              <div class="col-md-12">
                <div class="general-info">
                  <div class="input-group input-up input-up-position">
                    <label>
                      <span>İcazə</span>
                    </label>
                    <multiselect v-model="administratorData.permissions"
                                 label="name"
                                 selectedLabel=""
                                 deselectGroupLabel=""
                                 deselectLabel=""
                                 select-label=""
                                 :reset-after="false"
                                 :allow-empty="false"
                                 :internal-search="false"
                                 :close-on-select="true"
                                 :clear-on-select="false"
                                 :hide-selected="false"
                                 :options="permissionList"
                                 track-by="id" >
                    </multiselect>
                  </div>
                  <span class="validation" v-if="errors.permissions">{{errors.permissions}}</span>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="general-info">
                  <div class="input-group input-up">
                    <label>
                      <input type="password" placeholder=" " class="form-control inputText"
                             v-model="administratorData.password">
                      <span>Şifrə</span>
                    </label>
                  </div>
                  <span class="validation" v-if="errors.password">{{errors.password}}</span>
                </div>
              </div>
              <div class="col-md-6">
                <div class="general-info">
                  <div class="input-group input-up">
                    <label>
                      <input type="password" placeholder=" " class="form-control inputText"
                             v-model="administratorData.password_repeat">
                      <span>Şifrə təkrarı</span>
                    </label>
                  </div>
                  <span class="validation" v-if="errors.password_repeat">{{errors.password_repeat}}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="edit-post">
          <div class="block">
            <div class="switch-part">
              <span>Gizlə</span>
              <label class="switch">
                <input type="checkbox" v-model="administratorData.status">
                <span class="slider round"></span>
              </label>
              <span>Yayımla</span>
            </div>
            <div class="editor-buttons">
              <button @click="submitFile()" type="button" class="share-post">
                ƏLAVƏ ET
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
    import {axios} from '../config/axios';
    import Multiselect from 'vue-multiselect';
    export default {
        name: "administratorEdit",
        components: {
            Multiselect
        },
        data: function () {
            return {
                id: this.$route.params.id,
                newAdministrator:[],
                administratorData: {
                    name: '',
                    username: '',
                    phone: '',
                    email: '',
                    permissions: {id:'2',name: 'superadmin'},
                    password: '',
                    password_repeat: '',
                    status: true
                },
                permissionList: [],
                errors: {
                    name: false,
                    username: false,
                    phone: false,
                    email: false,
                    permissions: false,
                    password: false,
                    password_repeat: false,
                },
            }
        },
        mounted() {
            this.getAdministratorData();
            this.getAdministratorTypes();
        },
        methods: {
            submitFile: function(){
                this.resetErrors();
                let formdata = new FormData();
                formdata.append('name', this.administratorData.name);
                formdata.append('username', this.administratorData.username);
                formdata.append('phone', this.administratorData.phone);
                formdata.append('email', this.administratorData.email);
                formdata.append('permissions', this.administratorData.permissions.name);
                formdata.append('password', this.administratorData.password ? this.administratorData.password : '');
                formdata.append('password_repeat', this.administratorData.password_repeat ? this.administratorData.password_repeat : '');
                formdata.append('status', this.administratorData.status ? 1 : 0);
                formdata.append('id', this.id);
                axios.post('/admin/edit', formdata).then(
                    res => {
                        if (res.data.status == 200) {
                            this.$router.push("/administrators")
                        }
                        if (res.data.status != 200) {
                            this.errors = res.data.data
                        }
                    }).catch(error => {
                    this.errors = error.response.data.data;
                    for (let i in this.errors) {
                        if (this.errors !== false) this.errors[i] = this.errors[i][0]
                    }
                });
            },
            resetErrors: function(){
                this.errors = {
                    name: false,
                    username: false,
                    phone: false,
                    email: false,
                    permissions: false,
                    password: false,
                    password_repeat: false
                }
            },
            getAdministratorTypes() {
                axios.get('/admin/select').then(
                    response => {
                        if (response.data.status !== 200) {
                            return Swal.fire({
                                type: 'error',
                                text: response.data.message()
                            })
                        }
                        for (let i in response.data.data.authItems) {
                            this.permissionList.push({
                                id: i,
                                name: response.data.data.authItems[i].name
                            });
                        }
                    })
            },
            getAdministratorData: function () {
                let id = this.id;
                axios.post('/admin/info', {id}).then(
                    response => {
                        this.administratorData.name = response.data.data.name;
                        this.administratorData.username = response.data.data.username;
                        this.administratorData.phone = response.data.data.phone;
                        this.administratorData.email = response.data.data.email;
                        this.administratorData.permissions.name = response.data.data.permission;
                        this.administratorData.status = (response.data.data.status == 1 ? true : false);
                    }
                )

            }
        }
    }
</script>

<style scoped>
  .input-up-position {
    padding-top: 30px !important;
  }

  .input-up-position label {
    padding-bottom: 10px;
  }
</style>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

