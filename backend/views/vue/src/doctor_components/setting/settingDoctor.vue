<template>
  <div class="setting-doctor new-addition blog">
    <breadcrumb name="Tənzİmləmələr" icon="fa-cog" :routes="[{name:'Tənzimləmələr'}]"></breadcrumb>
    <div class="row">
      <div class="col-md-7">
        <div class="addition-info">
          <div class="block">
            <div class="row">
              <div class="col-lg-4 col-md-5 col-sm-5">
                <div class="validate-block" v-tooltip="message">
                  <div :class="previewImageClass" class="add-img-info">
                    <div :class="deletePreviewImage">
                      <div id="preview">
                        <img v-bind:src="imagePreview" v-show="showPreview"/>
                      </div>
                      <button class="delete-uploadimg" @click="deleteImage()"><span><i class="fa fa-trash-o"
                                                                                       aria-hidden="true"></i></span>
                      </button>
                      <div class="file-upload-wrapper" data-text="" v-if="deletePreviewImage">
                        <label class="custom-file-upload">
                          <span><i class="fa fa-camera" aria-hidden="true"></i></span>
                        </label>
                        <input type="file" id="file" ref="file" accept="image/*" v-on:change="handleFileUpload()"/>
                      </div>
                    </div>
                  </div>
                  <span class="validation" v-if="errors.files">{{errors.files}}</span>
                </div>
              </div>
              <div class="col-lg-8 col-md-7 col-sm-7">
                <div class="general-info">
                  <p class="info">İnfo</p>
                  <div class="validate-block">
                    <div class="input-group input-up">
                      <label>
                        <input type="text" placeholder=" " class="form-control inputText" v-model="Post_data.name">
                        <span>Ad, Soyad*</span>
                      </label>
                    </div>
                    <span class="validation" v-if="errors.name">{{errors.name}}</span>
                  </div>
                  <div class="input-group input-up">
                    <div class="textup">
                      <datepicker placeholder="Doğum günü" v-model="Post_data.birthday" :language="az"></datepicker>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="addition-data">
              <div class="row">
                <div class="col-md-6 col-sm-6">
                  <div class="general-info">
                    <div class="validate-block">
                      <div class="input-group input-up">
                        <label>
                          <input type="text" placeholder=" " class="form-control inputText"
                                 v-model="Post_data.experience1">
                          <span>Fəaliyyətə başladığı il* (1990)</span>
                        </label>
                      </div>
                      <span class="validation" v-if="errors.experience1">{{errors.experience1}}</span>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 col-sm-6">
                  <div class="general-info">
                    <div class="validate-block">
                      <div class="input-group">
                        <multiselect v-model="Post_data.doctorSpecialist" label="name" :options="doctorSpecialists"
                                     track-by="id" placeholder="İxtisas*" :multiple="true" :taggable="true"
                                     selectedLabel="" deselectGroupLabel="" deselectLabel="" select-label=""
                                     :reset-after="false" :allow-empty="false" :internal-search="false"
                                     :close-on-select="true" :clear-on-select="false" :hide-selected="false"
                                     @tag="addTag"></multiselect>
                      </div>
                      <span class="validation" v-if="errors.specialists">{{errors.specialists}}</span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 col-sm-6">
                  <div class="general-info">
                    <div class="input-group">
                      <multiselect v-model="Post_data.degree"
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
                                   :options="doctorDegrees"
                                   track-by="id">

                      </multiselect>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 col-sm-6">
                  <div class="general-info">
                    <div class="validate-block">
                      <div class="input-group input-up">
                        <label>
                          <input type="text" placeholder=" " class="form-control inputText" v-model="Post_data.email"
                                 :disabled="!!Post_data.email">
                          <span>E-mail*</span>
                        </label>
                      </div>
                      <span class="validation" v-if="errors.email">{{errors.email}}</span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 col-sm-6">
                  <div class="general-info">
                    <div class="input-group input-up">
                      <label>
                        <input type="number" placeholder=" " class="form-control inputText"
                               v-model="Post_data.phone_numbers[0].number">
                        <span>İş telefonu</span>
                      </label>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 col-sm-6">
                  <div class="general-info">
                    <div class="input-group input-up">
                      <label>
                        <input type="number" placeholder=" " class="form-control inputText"
                               v-model="Post_data.phone_numbers[1].number">
                        <span>Mobil telefonu (0551231122)</span>
                      </label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 col-sm-6">
                  <div class="general-info">
                    <div class="input-group input-up">
                      <multiselect v-model="Post_data.gender"
                                   label="name"
                                   :internal-search="false"
                                   :close-on-select="true"
                                   :clear-on-select="false"
                                   :hide-selected="false"
                                   :options="Genders"
                                   selectedLabel=""
                                   deselectGroupLabel=""
                                   deselectLabel=""
                                   select-label=""
                                   :reset-after="false"
                                   :allow-empty="false"
                                   track-by="id">
                      </multiselect>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="addition-workplace">
          <div class="block mt-20">
            <p class="info">İş yerlərİ</p>
            <div v-for="(item, index) in Post_data.workplaces_list">
              <div class="row">
                <div class="col-md-6 col-sm-6">
                  <div class="general-info">
                    <div class="input-group input-up">
                      <label>
                        <input type="text" placeholder=" " v-model="Post_data.workplaces_list[index].name"
                               class="form-control inputText">
                        <span>İş yeri adı</span>
                      </label>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 col-sm-6">
                  <div class="general-info flex-end">
                    <div class="input-group input-up">
                      <label>
                        <input type="text" placeholder=" " v-model="Post_data.workplaces_list[index].address"
                               class="form-control inputText">
                        <span>İş yeri ünvanı</span>
                      </label>
                    </div>
                    <button type="button" class="btn btn-addition" @click="addWork()" v-if="index === 0"><span>+</span>
                    </button>
                    <button type="button" class="btn btn-cancel" @click="removeWork(index)" v-if="index > 0">
                      <span>-</span></button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!--<div class="addition-security">
            <div class="block">
                <p class="info">Təhlükəsİzlİk</p>
                <div class="row">
                    <div class="col-md-6">
                        <div class="general-info">
                            <div class="input-group input-up">
                                <label>
                                    <input type="password" placeholder=" " class="form-control inputText">
                                    <span>Köhnə şifrə*</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="general-info">
                            <div class="input-group input-up">
                                <label>
                                    <input type="password" placeholder=" " class="form-control inputText">
                                    <span>Yeni şifrə*</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>-->
      </div>
      <div class="col-md-5">
        <div class="bio-diplom">
          <div class="block mtx-20">
            <div class="biography">
              <p class="info">Bİoqrafİya*</p>
              <div class="validate-block tinymce-validate-block">
                <div class="input-group">
<!--                  <textarea name="" id="" cols="30" rows="5" class="form-control" v-if="Doctor_data.doctor"-->
<!--                            v-model="Post_data.about"></textarea>-->
                  <tinymce-editor v-model="Post_data.about" :init="tinymce_config"></tinymce-editor>
                </div>
                <span class="validation" v-if="errors.about">{{errors.about}}</span>
              </div>
            </div>
            <div class="certificate">
              <p class="info">DİpLOM*</p>
              <div class="validate-block">
                <div class="certificate-list">
                  <div class="certificate-images">
                    <div v-for="(file, key) in Doctor_data.diploms" class="diploma-images">
                      <img class="preview" :src="file.file_photo_thumb" alt="certificate">
                      <button class="delete-uploadimg" @click="deleteImage_dp(key)"><span><i class="fa fa-trash-o"
                                                                                             aria-hidden="true"></i></span>
                      </button>
                    </div>
                    <div v-for="(file, key) in Post_data.dp_files" class="diploma-images">
                      <img class="preview" v-bind:ref="'image'+parseInt(key)" alt="certificate">
                      <button class="delete-uploadimg" @click="deleteImage_dpTmp(file)"><span><i class="fa fa-trash-o"
                                                                                                 aria-hidden="true"></i></span>
                      </button>
                    </div>
                  </div>
                  <div class="file-upload-wrapper" data-text="">
                    <label class="custom-file-upload">
                      <span><i class="fa fa-plus" aria-hidden="true"></i></span>
                    </label>
                    <input type="file" id="dp_files" ref="dp_files" accept="image/*" multiple
                           v-on:change="handleFilesUpload_dp()" name="dp_files" class="file-upload-field">
                  </div>
                </div>
                <span class="validation" v-if="errors.dp_files">{{errors.dp_files}}</span>
              </div>
            </div>
            <div class="certificate diploma">
              <p class="info">sertİFİKATLAR</p>
              <div class="certificate-list">
                <div class="certificate-images">
                  <div v-for="(file, key) in Doctor_data.certificate" class="diploma-images">
                    <img class="preview" :src="file.file_photo_thumb" alt="certificate">
                    <button class="delete-uploadimg" @click="deleteImage_ct(key)"><span><i class="fa fa-trash-o"
                                                                                           aria-hidden="true"></i></span>
                    </button>
                  </div>
                  <div v-for="(file, key) in Post_data.ct_files" class="diploma-images">
                    <img class="preview" v-bind:ref="'img'+parseInt( key )" alt="certificate">
                    <button class="delete-uploadimg" @click="deleteImage_ctTmp(key)"><span><i class="fa fa-trash-o"
                                                                                              aria-hidden="true"></i></span>
                    </button>
                  </div>
                </div>
                <div class="file-upload-wrapper" data-text="">
                  <label class="custom-file-upload">
                    <span><i class="fa fa-plus" aria-hidden="true"></i></span>
                  </label>
                  <input type="file" id="ct_files" ref="ct_files" accept="img/*" multiple
                         v-on:change="handleFilesUpload_ct()" name="file-upload-field" class="file-upload-field">
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="social-block edit-social">
          <div class="block mt-20">
            <p class="info">Sosİal şəbəkələr</p>
            <div class="general-info">
              <ul class="list-unstyled">
                <li v-if="Post_data.sosial_links.length > 0" v-for="(social,index) in sosial_links">
                  <div class="input-group input-up">
                    <label>
                      <input type="text" placeholder=" " v-model="Post_data.sosial_links[index].link"
                             class="form-control inputText">
                      <span :class=social.name>{{social.name}}</span>
                    </label>
                  </div>
                </li>
                <li>
                  <div class="input-group input-up">
                    <label>
                      <input type="number" placeholder=" " v-model="Post_data.phone_numbers[2].number"
                             class="form-control inputText">
                      <span class="Whatsapp">Whatsapp</span>
                    </label>
                  </div>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-12">
        <div class="addition-alternatives">
          <div class="block">
            <div class="check-list">
              <label class="check-button">
                <span>Evə çağırış</span>
                <input type="checkbox" v-if="Doctor_data.doctor" v-model="Post_data.home_doctor">
                <span class="checkmark"></span>
              </label>
              <label class="check-button">
                <span>Uşaq həkimi</span>
                <input type="checkbox" v-if="Doctor_data.doctor" v-model="Post_data.child_doctor">
                <span class="checkmark"></span>
              </label>
            </div>

            <button type="button" class="btn mt-0" @click="submitFile()">DÜZƏLİŞ ET</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import {axios} from "../config/doctor_axios";
  import {az} from 'vuejs-datepicker/dist/locale'
  import Multiselect from 'vue-multiselect';
  import Datepicker from 'vuejs-datepicker';
  import Swal from 'sweetalert2';

  export default {
    name: "settingDoctor",
    components: {
      Multiselect,
      Datepicker,
      'tinymce-editor': Editor
    },
    data: function () {
      return {
        az: az,
        id: this.$route.params.id,
        Doctor_data: {
          phones: []
        },
        Post_data: {
          name: '',
          birthday: '',
          phone_numbers: [
            {type: 0, number: ''},
            {type: 1, number: ''},
            {type: 2, number: ''}
          ],
          email: '',
          experience1: '',
          workplaces_list: [
            {name: '', address: ''}
          ],
          sosial_links: [],
          degree: '',
          doctorSpecialist: [],
          home_doctor: '',
          child_doctor: '',
          about: '',
          files: '',
          dp_files: [],
          ct_files: [],
          deletedImages: 0,
          deletedDiplomas: [],
          deletedCertificates: [],
          gender: ''
        },
        showPreview: true,
        imagePreview: '',
        url: null,
        previewImageClass: '',
        deletePreviewImage: '',
        doctorDegrees: [],
        sosial_links: [
          {name: "Facebook"},
          {name: "İnstagram"},
          {name: "Youtube"},
          {name: "Twitter"},
          {name: "Linkedin"}
        ],
        doctorSpecialists: [],
        deleteDpimages: '',
        errors: {
          name: false,
          experience1: false,
          dp_files: false,
          email: false,
          files: false,
          specialists: false,
          about: false
        },
        message: 'Şəkil yükləyin',
        Genders: [],
        tinymce_config: {
          plugins: 'image,wordcount,media,link,code',
          toolbar:'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons code | removeformat | superscript subscript',
          images_upload_url: '/tinymce',
          images_upload_base_path: 'https://e-tibb.az/upload/others/',
          remove_script_host : false,
          format: {
            removeformat: [
              {selector: 'b,strong,em,i,font,u,strike', remove : 'all', split : true, expand : false, block_expand: true, deep : true},
              {selector: 'span', attributes : ['style', 'class'], remove : 'empty', split : true, expand : false, deep : true},
              {selector: '*', attributes : ['style', 'class'], split : false, expand : false, deep : true}
            ]
          },
          relative_urls : false,
          height: 340
        }
      }
    },
    mounted: function () {
      this.initialize();
    },
    methods: {
      submitFile() {
        this.resetErrors();
        let formdata = new FormData();
        let postData = this.Post_data;
        let date = new Date(this.Post_data.birthday);
        let gender_old = this.Post_data.gender;
        let degree_old = this.Post_data.degree;
        postData.birthday = date.getFullYear() + "-" + ('0' + (date.getMonth() + 1)).slice(-2) + "-" + ('0' + date.getDate()).slice(-2);
        postData.degree = this.Post_data.degree.id || 0;
        postData.home_doctor = this.Post_data.home_doctor ? 1 : 0;
        postData.child_doctor = this.Post_data.child_doctor ? 1 : 0;
        postData.gender = this.Post_data.gender.id || 0;
        postData.specialists = [];
        for (let i of this.Post_data.doctorSpecialist) {
          postData.specialists.push(i.id)
        }
        for (let i in postData) {
          switch (i) {
            case 'workplaces_list' :
              for (let j in postData[i]) {
                formdata.append(`${i}[` + j + `][name]`, postData[i][j].name);
                formdata.append(`${i}[` + j + `][address]`, postData[i][j].address);
              }
              break
            case 'sosial_links' :
              for (let j in postData[i]) {
                formdata.append(`${i}[` + j + `][type]`, j);
                formdata.append(`${i}[` + j + `][link]`, postData[i][j].link);
              }
              break
            case 'phone_numbers':
              for (let j in postData[i]) {
                formdata.append(`${i}[` + j + `][type]`, j);
                formdata.append(`${i}[` + j + `][number]`, postData[i][j].number);
              }
              break
            default :
              if (Array.isArray(postData[i])) {
                for (let j in postData[i]) {
                  formdata.append(`${i}[` + j + `]`, postData[i][j]);
                }
              } else {
                formdata.append(i, postData[i]);
              }
              break
          }
        }
        axios.post('/doctors/edit', formdata).then(
          res => {
            if (res.data.status != 200) {
              this.errors = res.data.data
            }
            if (res.data.status == 200) {
              return Swal.fire({
                type: 'success',
                text: res.data.message,
                showConfirmButton: false,
              })
              /* this.$router.push('/doctors')*/
            }
          }).catch(error => {
          this.errors = error.response.data.data;
          postData.gender = gender_old;
          postData.degree = degree_old;
          for (let i in this.errors) {
            if (this.errors !== false) this.errors[i] = this.errors[i][0]
          }
        });
      },
      resetErrors: function () {
        this.errors = {
          name: false,
          experience1: false,
          dp_files: false,
          email: false,
          files: false,
          specialists: false,
          about: false
        }
      },
      getDegree: function () {
        axios.get('/doctors/list-academic-degree', this.Doctor_data).then(
          response => {
            if (response.data.status !== 200) {
              return Swal.fire({
                type: 'error',
                text: response.data.message

              })
            }
            for (let i in response.data.data) {
              this.doctorDegrees.push({
                id: i,
                name: response.data.data[i]
              })
            }
          })
      },
      getGender: function () {
        axios.get('/doctors/list-gender', this.gender).then(
          response => {
            if (response.data.status !== 200) {
              return Swal.fire({
                type: 'error',
                text: response.data.message
              })
            }
            for (let i in response.data.data) {
              this.Genders.push({
                id: i,
                name: response.data.data[i]
              })
            }

          })
      },
      getSpecialist: function () {
        axios.get('/doctors/specialists', this.Doctor_data).then(
          response => {
            if (response.data.status !== 200) {
              return Swal.fire({
                type: 'error',
                text: response.data.message
              })
            }
            for (let i in response.data.data) {
              this.doctorSpecialists.push({
                id: response.data.data[i].id,
                name: response.data.data[i].name
              })
            }
          })
      },
      getDoctorData: function () {
        axios.get('/doctors/info').then(
          response => {
            this.imagePreview = response.data.data.doctor.photo;
            this.Doctor_data = response.data.data;
            this.Post_data.name = this.Doctor_data.doctor.name;
            this.Post_data.birthday = this.Doctor_data.doctor.birthday;
            this.Post_data.experience1 = this.Doctor_data.doctor.experience;
            for(let i of this.Doctor_data.phones) {
              // if ((parseInt(i.number_type) === 1)) {
              //   this.Post_data.phone_numbers[i.number_type]= i;
              //   if ((this.Post_data.phone_numbers[1].num)) {
              //     this.Post_data.phone_numbers[i.number_type].number = (this.Post_data.phone_numbers[1].num)
              //   }
              //   else {
              //     this.Post_data.phone_numbers[i.number_type].number = (this.Post_data.phone_numbers[1].number)
              //   }
              // }
              // else {
                if (i.number_type) {
                  this.Post_data.phone_numbers[i.number_type]= i;
                  this.Post_data.phone_numbers[i.number_type].number = (this.Post_data.phone_numbers[i.number_type].number);
                }
              // }
            }
            // for (let i in [0, 1, 2]) {
            //   // console.log('sasas', i, this.Doctor_data.phones[i])
            //   // this.Post_data.phone_numbers[i] = typeof this.Doctor_data.phones[i] != 'undefined' ? this.Doctor_data.phones[i] : {
            //   //   type: i,
            //   //   number: ''
            //   // }
            // }
            this.Post_data.about = this.Doctor_data.doctor.about;
            this.Post_data.email = this.Doctor_data.doctor.email;
            this.Post_data.gender = this.Doctor_data.doctor.gender;
            let socials = this.Doctor_data.social_links;
            for (let i in this.sosial_links) {
              if (typeof socials[i] == 'undefined') {
                let obj = {};
                obj.id = 0;
                obj.link = " ";
                obj.link_type = i.toString();
                socials[i] = obj;
              }
            }
            this.Post_data.sosial_links = socials;
            this.Post_data.workplaces_list = this.Doctor_data.workplaces;
            if (this.Post_data.workplaces_list.length == 0) this.Post_data.workplaces_list.push({
              name: '',
              address: ''
            });
            this.Post_data.child_doctor = this.Doctor_data.doctor.child_doctor;
            this.Post_data.home_doctor = this.Doctor_data.doctor.home_doctor;
            for (let i in this.doctorDegrees) {
              if (this.doctorDegrees[i].id == this.Doctor_data.doctor.degree) {
                this.Post_data.degree = this.doctorDegrees[i]
              }
            }
            ;
            for (let i in this.Genders) {
              if (this.Genders[i].id == this.Doctor_data.doctor.gender) {
                this.Post_data.gender = this.Genders[i]
              }
            }
            this.Post_data.doctorSpecialist = this.Doctor_data.specialist;
          }
        )
      },
      addWork() {
        const row = {
          name: '',
          address: ''
        };
        this.Doctor_data.workplaces.push(row)
      },
      removeWork(index) {
        this.Doctor_data.workplaces.splice(index, 1)
      },
      addTag(newTag) {
        const tag = {
          name: newTag,
          code: newTag.substring(0, 2) + Math.floor((Math.random() * 10000000))
        };
        this.options.push(tag);
        this.value.push(tag)
      },
      handleFileUpload() {
        this.Post_data.files = this.$refs.file.files[0];
        let reader = new FileReader();
        this.previewImageClass = 'changebg';
        reader.addEventListener("load", function () {
          this.showPreview = true;
          this.imagePreview = reader.result;
        }.bind(this), false);
        if (this.Post_data.files) {
          if (/\.(jpe?g|png|gif)$/i.test(this.Post_data.files.name)) {
            reader.readAsDataURL(this.Post_data.files);
          }
        }
      },
      deleteImage: function () {
        this.deletePreviewImage = 'deletebg';
        this.imagePreview = '';
        this.Post_data.deletedImages = 1;
        this.showPreview = false

      },

      deleteImage_ct: function (key) {
        this.Post_data.deletedCertificates.push(this.Doctor_data.certificate[key].id);
        this.Doctor_data.certificate.splice(key, 1);
      },

      deleteImage_ctTmp: function (key) {
        this.Post_data.ct_files.splice(key, 1);
      },

      deleteImage_dp: function (key) {
        this.Post_data.deletedDiplomas.push(this.Doctor_data.diploms[key].id);
        this.Doctor_data.diploms.splice(key, 1);
      },

      deleteImage_dpTmp: function (item) {
        this.Post_data.dp_files.splice(this.Post_data.dp_files.indexOf(item), 1);
      },

      handleFilesUpload_dp() {
        let dp_uploadedFiles = this.$refs.dp_files.files;
        for (var i = 0; i < dp_uploadedFiles.length; i++) {
          this.Post_data.dp_files.push(dp_uploadedFiles[i]);
        }
        this.getImagePreviews_dp();
      },
      getImagePreviews_dp() {
        for (let i = 0; i < this.Post_data.dp_files.length; i++) {
          if (/\.(jpe?g|png|gif)$/i.test(this.Post_data.dp_files[i].name)) {
            let reader = new FileReader();
            reader.addEventListener("load", function () {
              this.$refs['image' + parseInt(i)][0].src = reader.result;
            }.bind(this), false);
            reader.readAsDataURL(this.Post_data.dp_files[i]);
          }
        }
      },
      handleFilesUpload_ct() {
        let ct_uploadedFiles = this.$refs.ct_files.files;
        for (var j = 0; j < ct_uploadedFiles.length; j++) {
          this.Post_data.ct_files.push(ct_uploadedFiles[j]);
        }
        this.getImagePreviews_ct();
      },
      getImagePreviews_ct() {
        for (let j = 0; j < this.Post_data.ct_files.length; j++) {
          if (/\.(jpe?g|png|gif)$/i.test(this.Post_data.ct_files[j].name)) {
            let reader = new FileReader();
            reader.addEventListener("load", function () {
              this.$refs['img' + parseInt(j)][0].src = reader.result;
            }.bind(this), false);
            reader.readAsDataURL(this.Post_data.ct_files[j]);
          }
        }
      },
      async initialize() {
        axios.get('/doctors/list-academic-degree', this.Doctor_data).then(
          response => {
            if (response.data.status !== 200) {
              return Swal.fire({
                type: 'error',
                text: response.data.message()
              })
            }
            for (let i in response.data.data) {
              this.doctorDegrees.push({
                id: i,
                name: response.data.data[i]
              })
            }
            axios.get('/doctors/specialists', this.Doctor_data).then(
              response => {
                if (response.data.status !== 200) {
                  return Swal.fire({
                    type: 'error',
                    text: response.data.message()
                  })
                }
                for (let i in response.data.data) {
                  this.doctorSpecialists.push({
                    id: response.data.data[i].id,
                    name: response.data.data[i].name
                  })
                }
                axios.get('/doctors/list-gender', this.gender).then(
                  response => {
                    if (response.data.status !== 200) {
                      return Swal.fire({
                        type: 'error',
                        text: response.data.message()
                      })
                    }
                    for (let i in response.data.data) {
                      this.Genders.push({
                        id: i,
                        name: response.data.data[i]
                      })
                    }
                    axios.get('/doctors/info/', this.Doctor_data).then(
                      response => {
                        if (response.data.data.doctor.photo === null) {
                          this.deletePreviewImage = 'deletebg';
                          this.imagePreview = '';
                          this.Post_data.deletedImages = 1;
                        } else {
                          this.imagePreview = response.data.data.doctor.photo;
                        }
                        this.Doctor_data = response.data.data;
                        this.Post_data.name = this.Doctor_data.doctor.name;
                        this.Post_data.birthday = this.Doctor_data.doctor.birthday;
                        if (this.Doctor_data.doctor.experience === null) {
                            this.errors.experience1="Təcrübə xanasını doldurun."
                        } else {
                          this.Post_data.experience1 = this.Doctor_data.doctor.experience;
                        }
                        if (this.Doctor_data.diploms.length === 0) {
                          this.errors.dp_files="Diplom əlavə edin."
                        }
                        for(let i of this.Doctor_data.phones) {
                          // if ((parseInt(i.number_type) === 1)) {
                          //   this.Post_data.phone_numbers[i.number_type]= i;
                          //   this.Post_data.phone_numbers[i.number_type].number = (this.Post_data.phone_numbers[1].num)
                          // }
                          // else {
                            if (i.number_type) {
                              this.Post_data.phone_numbers[i.number_type]= i;
                              this.Post_data.phone_numbers[i.number_type].number = (this.Post_data.phone_numbers[i.number_type].number);
                            }
                          // }
                        }
                        // for (let i in [0, 1, 2]) {
                        //   // console.log('sasas', i, this.Doctor_data.phones[i])
                        //   // this.Post_data.phone_numbers[i] = typeof this.Doctor_data.phones[i] != 'undefined' ? this.Doctor_data.phones[i] : {
                        //   //   type: i,
                        //   //   number: ''
                        //   // }
                        // }
                        // for(let i of this.Doctor_data.phones) {
                        //   if ((parseInt(i.number_type) === 1)) {
                        //     this.Post_data.phone_numbers[i.number_type]= i;
                        //     this.Post_data.phone_numbers[i.number_type].number = (this.Post_data.phone_numbers[1].num)
                        //   }
                        //   else {
                        //     if (i.number_type) {
                        //       this.Post_data.phone_numbers[i.number_type]= i;
                        //       this.Post_data.phone_numbers[i.number_type].number = (this.Post_data.phone_numbers[i.number_type].number);
                        //     }
                        //   }
                        // }
                        this.Post_data.about = this.Doctor_data.doctor.about;
                        this.Post_data.email = this.Doctor_data.doctor.email;
                        let socials = this.Doctor_data.social_links;
                        for (let i in this.sosial_links) {
                          if (typeof socials[i] == 'undefined') {
                            let obj = {};
                            obj.id = 0;
                            obj.link = " ";
                            obj.link_type = i.toString();
                            socials[i] = obj;
                          }
                        }
                        this.Post_data.sosial_links = socials;
                        this.Post_data.workplaces_list = this.Doctor_data.workplaces;
                        if (this.Post_data.workplaces_list.length == 0) this.Post_data.workplaces_list.push({
                          name: '',
                          address: ''
                        });
                        this.Post_data.child_doctor = this.Doctor_data.doctor.child_doctor;
                        this.Post_data.home_doctor = this.Doctor_data.doctor.home_doctor;
                        for (let i in this.doctorDegrees) {
                          if (this.doctorDegrees[i].id == this.Doctor_data.doctor.degree) {
                            this.Post_data.degree = this.doctorDegrees[i]
                          }
                        }
                        ;
                        for (let i in this.Genders) {
                          if (this.Genders[i].id == this.Doctor_data.doctor.gender) {
                            this.Post_data.gender = this.Genders[i]
                          }
                        }
                        this.Post_data.doctorSpecialist = this.Doctor_data.specialist;
                      }
                    )

                  })
              })
          })
      },
    }
  }
</script>
