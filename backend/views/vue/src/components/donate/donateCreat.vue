<template>
    <div class="stocks-inner new-addition blog">
        <breadcrumb name="Yenİ Qan elanı" icon="fa-plus-square-o" :routes="[{'name':'Yenİ Qan elanı'}]"></breadcrumb>
        <div class="row">
            <div class="col-md-4 col-sm-4">
                <div class="blog-file-upload">
                    <div class="block">
                        <div class="upload-body addition-info">
<!--                            <div class="validate-block">-->
<!--                                <div :class="previewImageClass" class="add-img-info">-->
<!--                                    <div id="preview">-->
<!--                                        <img v-bind:src="imagePreview" v-show="showPreview"/>-->
<!--                                    </div>-->
<!--                                    <div class="file-upload-wrapper" data-text="">-->
<!--                                        <label class="custom-file-upload">-->
<!--                                            <span><i class="fa fa-camera" aria-hidden="true"></i></span>-->
<!--                                        </label>-->
<!--                                        <input type="file" id="file" ref="file" accept="image/*"-->
<!--                                               v-on:change="handleFileUpload()"/>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                                <span class="validation" v-if="errors.image">{{errors.image}}</span>-->
<!--                            </div>-->
                            <div class="date-hour-add">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="validate-block">
                                            <div class="upload-date">
                                                <div class="input-group input-up">
                                                    <label>
                                                        <input type="text" placeholder=" " class="form-control inputText"
                                                               v-model="newDonate.user_info">
                                                        <span>Ad, Soyad</span>
                                                    </label>
                                                </div>
                                            </div>
                                            <span class="validation" v-if="errors.user_info">{{errors.user_info}}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="general-info">
                                            <div class="validate-block">
                                                <div class="input-group input-up">
                                                    <label>
                                                        <input type="text" placeholder=" " class="form-control inputText" v-model="newDonate.email">
                                                        <span>E-mail*</span>
                                                    </label>
                                                </div>
                                                <span class="validation" v-if="errors.email">{{errors.email}}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                              <div class="row">
                                <div class="col-md-12">
                                  <div class="general-info">
                                    <div class="input-group input-up">
                                      <label>
                                        <input type="number" placeholder=" " class="form-control inputText"
                                               v-model="newDonate.phone" minlength="12" maxlength="12">
                                        <span>Telefon (0551231122)</span>
                                      </label>
                                    </div>
                                    <span class="validation" v-if="errors.phone">{{errors.phone}}</span>
                                  </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="cost-sale">
                                        <div class="input-group donate_multiselect">
                                            <label v-if="changeMultiSelect === 1" class="new_label">Qan qrupu</label>
                                            <multiselect v-model="newDonate.blood_type"
                                                         selectedLabel=""
                                                         deselectGroupLabel="" deselectLabel="" select-label=""
                                                         :reset-after="false" :allow-empty="false"
                                                         :internal-search="false" @input="onChangeMulti"
                                                         :close-on-select="true" :clear-on-select="false"
                                                         :hide-selected="false" :options="newDonate.donateTip"
                                            >
                                            </multiselect>
                                        </div>
                                        <span class="validation" v-if="errors.blood_type">{{errors.blood_type}}</span>
                                    </div>
                                </div>
                              </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8 col-sm-8">
                <div class="upload-file-content">
                    <div class="block">
                        <div class="validate-block">
                            <div class="input-group input-up">
                                <label>
                                    <input type="text" placeholder=" " class="form-control inputText"
                                           v-model="newDonate.title">
                                    <span>Başlıq</span>
                                </label>
                            </div>
                            <span class="validation" v-if="errors.title">{{errors.title}}</span>
                        </div>
                        <div class="validate-block">
                            <div class="upload-article">
                                <p>Məqalə</p>
                                <tinymce-editor v-model="newDonate.text" :init="tinymce_config"></tinymce-editor>
                                <span class="validation" v-if="errors.text">{{errors.text}}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="edit-post">
                    <div class="block">
                        <div class="switch-part ">
                            <span>Gizlə</span>
                            <label class="switch">
                                <input type="checkbox" v-model="newDonate.status">
                                <span class="slider round"></span>
                            </label>
                            <span>Yayımla</span>
                        </div>
                        <div class="editor-buttons">
                            <!--<button type="button" class="delete-post">
                                SİL
                            </button>-->
                            <button @click="submitFile()" type="button" class="share-post">
                                PAYLAŞ
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
        name: "donateCreat",
        components: {
            Multiselect,
            'tinymce-editor': Editor
        },
        data: function () {
            return {
                newDonate: {
                    // files: '',
                    title: '',
                    text: '',
                    user_info: '',
                    email: '',
                    phone: '',
                    blood_type:'Qan qrupunu seçin',
                    status:'',
                    is_blood:"1",
                    donateTip:[]
                },
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
                height: 400,
              },
                errors:{
                    // image:false,
                    title:false,
                    user_info:false,
                    text:false,
                    blood_type:false,
                    email: false,
                    phone: false
                },
                changeMultiSelect: 0
                // previewImageClass: '',
                // showPreview: false,
                // imagePreview: '',
            }
        },
        mounted() {
            this.getDonateTip();
        },
        methods: {
            onChangeMulti() {
                this.changeMultiSelect = 1;
            },
            submitFile() {
                // console.log('submit me');
                this.resetErrors();
                let formdata = new FormData();
                formdata.append('user_info',this.newDonate.user_info);
                // formdata.append('image',this.newDonate.files);
                formdata.append('email',this.newDonate.email);
                formdata.append('phone',this.newDonate.phone);
                formdata.append('title',this.newDonate.title);
                formdata.append('blood_type',this.newDonate.blood_type);
                formdata.append('text',this.newDonate.text);
                formdata.append('status',this.newDonate.status ? 1 : 0);
                formdata.append('is_blood',this.newDonate.is_blood );
                axios.post('/donate/create', formdata).then(
                    res => {
                        if(res.data.status == 200) {
                            this.$router.push("/donate")
                        }
                        if(res.data.status!=200) {
                            this.errors=res.data.data
                        }
                    }).catch(error=>
                {
                    this.errors = error.response.data.data;
                    for(let i in this.errors) {
                        if(this.errors!==false) this.errors[i]=this.errors[i][0]
                    }
                });
            },
            resetErrors:function(){
                this.errors={
                    // image:false,
                    title:false,
                    user_info:false,
                    email: false,
                    phone: false,
                    text:false,
                    blood_type:false
                }
            },
            // handleFileUpload() {
            //     this.newDonate.files = this.$refs.file.files[0];
            //     let reader = new FileReader();
            //     this.previewImageClass = 'changebg';
            //     reader.addEventListener("load", function () {
            //         this.showPreview = true;
            //         this.imagePreview = reader.result;
            //     }.bind(this), false);
            //     if (this.newDonate.files) {
            //         if (/\.(jpe?g|png|gif)$/i.test(this.newDonate.files.name)) {
            //             reader.readAsDataURL(this.newDonate.files);
            //         }
            //     }
            // },

            getDonateTip: function (){
                axios.get('/donate/bloods').then(
                    response=> {
                        this.newDonate.donateTip = response.data.data
                    }
                )
            },
        }
    }
</script>
<style scoped>
    .upload-article #article {
        border: 1px solid #a3adb4;
        padding-left: 5px;
    }
</style>
