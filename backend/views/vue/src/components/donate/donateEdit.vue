<template>
    <div class="stocks-inner new-addition blog">
        <breadcrumb name="Qan elanı" icon="fa-plus-square-o" :routes="[{'name':'Qan elanı'}]"></breadcrumb>
        <div class="row">
            <div class="col-md-4 col-sm-4">
                <div class="blog-file-upload">
                    <div class="block">
                        <div class="upload-body addition-info">
                            <div class="cost-sale">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="validte-block">
                                            <div class="input-group input-up">
                                                <label>
                                                    <input type="text" placeholder=" " class="form-control inputText"
                                                           v-model="PostData.user_info">
                                                    <span>Ad</span>
                                                </label>
                                            </div>
                                            <span class="validation" v-if="errors.user_info">{{errors.user_info}}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                      <div class="general-info">
                                        <div class="validate-block">
                                          <div class="input-group input-up">
                                            <label>
                                              <input type="text" placeholder=" " class="form-control inputText" v-model="PostData.email">
                                              <span>E-mail*</span>
                                            </label>
                                          </div>
                                          <span class="validation" v-if="errors.email">{{errors.email}}</span>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-md-12">
                                      <div class="general-info">
                                        <div class="input-group input-up">
                                          <label>
                                            <input type="number" placeholder=" " class="form-control inputText"
                                                   v-model="PostData.phone" minlength="12" maxlength="12">
                                            <span>Telefon (0551231122)</span>
                                          </label>
                                        </div>
                                        <span class="validation" v-if="errors.phone">{{errors.phone}}</span>
                                      </div>
                                    </div>
                                    <div class="col-md-12">
                                      <div class="validate-block">
                                        <div class="input-group">
                                          <label class="new_label">Qan qrupu</label>
                                          <multiselect v-model="PostData.blood_type"
                                                       selectedLabel=""
                                                       deselectGroupLabel="" deselectLabel="" select-label=""
                                                       :reset-after="false" :allow-empty="false"
                                                       :internal-search="false"
                                                       :close-on-select="true" :clear-on-select="false"
                                                       :hide-selected="false" :options="donateTip">
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
                                           v-model="PostData.title">
                                    <span>Başlıq</span>
                                </label>
                            </div>
                            <span class="validation" v-if="errors.title">{{errors.title}}</span>
                        </div>
                        <div class="validate-block">
                            <div class="upload-article">
                                <p>Məqalə</p>
                                <tinymce-editor v-model="PostData.text" :init="tinymce_config"></tinymce-editor>
                            </div>
                            <span class="validation" v-if="errors.text">{{errors.text}}</span>
                        </div>
                    </div>
                </div>
                <div class="edit-post">
                    <div class="block">
                        <div class="switch-part ">
                            <span>Gizlə</span>
                            <label class="switch">
                                <input type="checkbox" v-model="PostData.status">
                                <span class="slider round"></span>
                            </label>
                            <span>Yayımla</span>
                        </div>
                        <div class="editor-buttons">
                            <!-- <button type="button" class="delete-post">
                                 SİL
                             </button>-->
                            <button @click="submit()" type="button" class="share-post">
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
        name: "newsEdit",
        components: {
            Multiselect,
            'tinymce-editor': Editor,

        },
        data: function () {
            return {
                id: this.$route.params.id,
                donateData:{},
                PostData: {
                    // files: '',
                    title: '',
                    text: '',
                    user_info: '',
                    phone: '',
                    email: '',
                    blood_type:'Qan qrupunu seçin',
                    status:'',
                    is_blood:"1",
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
                    phone: false,
                    email: false,
                    text:false,
                    blood_type:false
                },
                // previewImageClass: '',
                // showPreview: true,
                // imagePreview: '',
                // deletePreviewImage: '',
                donateTip:[]
            }
        },
        mounted() {
            this.getDonateData();
            this.getDonateTip();
        },
        methods: {
            submit: function () {
                this.resetErrors();
                let formdata = new FormData();
                // formdata.append('image', this.PostData.files);
                formdata.append('title', this.PostData.title);
                formdata.append('text', this.PostData.text);
                formdata.append('user_info', this.PostData.user_info);
                formdata.append('email', this.PostData.email);
                formdata.append('phone', this.PostData.phone);
                formdata.append('blood_type', this.PostData.blood_type);
                formdata.append('status', this.PostData.status ? 1 : 0);
                formdata.append('id',this.id);
                axios.post('/donate/edit', formdata).then(
                    res =>{
                        if(res.data.status == 200) {
                            this.$router.push("/donate")
                        }
                        if(res.data.status != 200){
                            this.errors = res.data.data
                        }
                    }).catch(error => {
                    this.errors=error.response.data.data;
                    for(let i in this.errors) {
                        if(this.errors !== false) this.errors[i]=this.errors[i][0]
                    }
                });
            },
            getDonateData: function () {
                axios.get('/donate/info/' + this.id + "?type=1").then(
                    response => {
                        // this.imagePreview = response.data.data.image;
                        this.donateData = response.data.data;
                        // this.PostData.files=this.donateData.image;
                        this.PostData.title=this.donateData.title;
                        this.PostData.user_info=this.donateData.user_info;
                        this.PostData.email=this.donateData.email;
                        this.PostData.phone=this.donateData.phone;
                        this.PostData.status=this.donateData.status == '1';
                        this.PostData.text=this.donateData.text ;
                        this.PostData.blood_type = this.donateData.blood_type
                    }
                )
            },
            getDonateTip: function (){
                axios.get('/donate/bloods').then(
                    response=> {
                        this.donateTip = response.data.data
                    }
                )
            },
            resetErrors: function () {
                this.errors = {
                    // image:false,
                    title:false,
                    user_info:false,
                    text:false,
                    blood_type:false,
                    email: false,
                    phone: false
                }
            },
            // handleFileUpload() {
            //     this.PostData.files = this.$refs.file.files[0];
            //     let reader = new FileReader();
            //     this.previewImageClass = 'changebg';
            //     reader.addEventListener("load", function () {
            //         this.showPreview = true;
            //         this.imagePreview = reader.result;
            //     }.bind(this), false);
            //     if (this.PostData.files) {
            //         if (/\.(jpe?g|png|gif)$/i.test(this.PostData.files.name)) {
            //             reader.readAsDataURL(this.PostData.files);
            //         }
            //     }
            // },
            // deleteImage: function () {
            //     this.deletePreviewImage = 'deletebg';
            //     this.imagePreview = '';
            //     this.PostData.deletedImages = 1;
            //     this.showPreview=false
            // }
        }
    }


</script>

<style scoped>
    .upload-article #article {
        border: 1px solid #a3adb4;
        padding-left: 5px;
    }
</style>
