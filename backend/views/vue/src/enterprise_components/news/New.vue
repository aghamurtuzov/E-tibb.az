<template>
    <div class="doctor-component stocks-inner new-addition blog">
        <breadcrumb name="XƏBƏRLƏR" icon="fa-newspaper-o" :routes="[{'name':'XƏBƏRLƏR'}]"></breadcrumb>
        <div class="row">
            <div class="col-md-4">
                <div class="blog-file-upload">
                    <div class="block">
                        <div class="upload-body addition-info">
                            <div class="validate-block">
                                <div :class="previewImageClass" :style="imagePreview ? 'background-image:none !important' : ''" class="add-img-info">
                                    <div :class="deletePreviewImage">
                                        <div id="preview">
                                            <img v-bind:src="imagePreview" v-show="showPreview">
                                        </div>
                                        <button class="delete-uploadimg" @click="deleteImage()"><span><i
                                                class="fa fa-trash-o" aria-hidden="true"></i></span></button>
                                        <div class="file-upload-wrapper" data-text="" v-if="deletePreviewImage">
                                            <label class="custom-file-upload">
                                                <span><i class="fa fa-camera" aria-hidden="true"></i></span>
                                            </label>
                                            <input type="file" id="file" ref="file" accept="image/*"
                                                   v-on:change="handleFileUpload()"/>
                                        </div>
                                    </div>
                                </div>
                                <span class="validation" v-if="errors.files">{{errors.files}}</span>
                            </div>
                            <div class="cost-sale">
                                <div class="row">
                                    <!--<div class="col-md-12">
                                        <div class="input-group">
                                            <multiselect v-model="PostData.category_id" label="name"
                                                         selectedLabel=""
                                                         deselectGroupLabel="" deselectLabel="" select-label=""
                                                         :reset-after="false" :allow-empty="false"
                                                         :internal-search="false"
                                                         :close-on-select="true" :clear-on-select="false"
                                                         :hide-selected="false" :options="newsTip"
                                                         track-by="id"></multiselect>
                                        </div>
                                    </div>-->
                                    <div class="col-md-12">
                                        <div class="input-group input-up">
                                            <label>
                                                <input type="text" placeholder=" " class="form-control inputText"
                                                       v-model="PostData.keywords">
                                                <span>Açar sözlər</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="date-hour-add">
                                <div class="row">
                                    <div class="col-md-6 col-sm-6">
                                        <div class="upload-date">
                                            <div class="input-group input-up">
                                                <div class="textup">
                                                    <datepicker v-model="PostData.date" :language="az"></datepicker>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6">
                                        <div class="upload-hour">
                                            <div class="input-group input-up">
                                                <div class="textup">
                                                    <timeselector v-model="PostData.time"></timeselector>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12">
                                        <span class="validation" v-if="errors.datetime">{{errors.datetime}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="upload-file-content">
                    <div class="block">
                        <div class="validate-block">
                            <div class="input-group input-up">
                                <label>
                                    <input type="text" placeholder=" " class="form-control inputText"
                                           v-model="PostData.headline">
                                    <span>Başlıq</span>
                                </label>
                            </div>
                            <span class="validation" v-if="errors.headline">{{errors.headline}}</span>
                        </div>
                        <div class="validate-block">
                            <div class="upload-article">
                                <p>Məqalə</p>
<!--                                <textarea name="article" id="article" cols="30" rows="15" class="form-control"-->
<!--                                          v-model="PostData.content"></textarea>-->
                                <tinymce-editor v-model="PostData.content" :init="tinymce_config"></tinymce-editor>
                            </div>
                            <span class="validation" v-if="errors.content">{{errors.content}}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">`
                <div class="edit-post">
                    <div class="block">
                        <!-- <div class="switch-part ">
                             <span>Gizlə</span>
                             <label class="switch">
                                 <input type="checkbox" v-model="PostData.status">
                                 <span class="slider round"></span>
                             </label>
                             <span>Yayımla</span>
                         </div>-->
                        <div class="editor-buttons">
                            <!-- <button type="button" class="delete-post">
                                 SİL
                             </button>-->
                            <button @click="submit()" type="button" class="share-post">
                                DÜZƏLİŞ ET
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import {axios} from '../config/enterprise_axios';
    import Datepicker from 'vuejs-datepicker';
    import {az} from 'vuejs-datepicker/dist/locale'
    import Multiselect from 'vue-multiselect';
    import Timeselector from 'vue-timeselector';

    export default {
        name: "newsData",
        components: {
            Datepicker,
            Multiselect,
            Timeselector,
            'tinymce-editor': Editor,
        },
        data: function () {
            return {
                az:az,
                id: this.$route.params.id,
                newsData:{},
                PostData: {
                    files: '',
                    category_id:'',
                    keywords: '',
                    headline: '',
                    content: '',
                    datetime: '',
                    date: '',
                    time: '',
                    deletedImages: 0,
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
                  height: 340,
                },
                errors: {
                    datetime: false,
                    files: false,
                    headline: false,
                    content: false
                },
                previewImageClass: '',
                showPreview: true,
                imagePreview: '',
                deletePreviewImage: '',
                newsTip: [],
            }
        },
        mounted() {
            this.getNewsData();
        },
        methods: {
            submit: function () {
                this.resetErrors();
                let formdata = new FormData();
                let date = new Date(this.PostData.date),
                    time = this.PostData.time,
                    dateFormat = date.getFullYear() + "-" + ("0" + (date.getMonth()+1)).slice(-2) + "-" + ("0" + date.getDate()).slice(-2),
                    timeFormat = ("0" + time.getHours()).slice(-2) + ":" + ("0" + time.getMinutes()).slice(-2),
                    combineDate = dateFormat + ' ' + timeFormat;
                formdata.append('datetime', combineDate);
                formdata.append('files', this.PostData.files);
                formdata.append('headline', this.PostData.headline);
                formdata.append('content', this.PostData.content);
                formdata.append('keywords', this.PostData.keywords);
                formdata.append('status', this.PostData.status ? 1 : 0);
                formdata.append('category_id', 37);
                formdata.append('id', this.id);
                formdata.append('deletedImages',this.PostData.deletedImages);
                axios.post('/news/edit', formdata).then(
                    res =>{
                        if(res.data.status == 200) {
                            this.$router.push("/news")
                        }
                        if(res.data.status != 200){
                            this.errors = res.data.data
                        }
                    }).catch(error => {
                        this.errors = error.response.data.data;
                        for(let i in this.errors) {
                            if(this.errors !== false) this.errors[i]=this.errors[i][0]
                        }
                });
            },
            getNewsData: function () {
                axios.get('/news/info/' + this.id).then(
                    response => {
                        this.imagePreview = response.data.data.photo;
                        this.newsData = response.data.data;
                        this.PostData.files=this.newsData.files;
                        this.PostData.keywords=this.newsData.keywords;
                        this.PostData.headline=this.newsData.headline;
                        this.PostData.status=this.newsData.status == '1';
                        this.PostData.content=this.newsData.content ;
                        let date = new Date(this.newsData.datetime);
                        this.PostData.date=date;
                        this.PostData.time=date;
                    }
                )
            },
            resetErrors: function () {
                this.errors = {
                    datetime: false,
                    files: false,
                    headline: false,
                    content: false
                }
            },
            handleFileUpload() {
                this.PostData.files = this.$refs.file.files[0];
                let reader = new FileReader();
                this.previewImageClass = 'changebg';
                reader.addEventListener("load", function () {
                    this.showPreview = true;
                    this.imagePreview = reader.result;
                }.bind(this), false);
                if (this.PostData.files) {
                    if (/\.(jpe?g|png|gif)$/i.test(this.PostData.files.name)) {
                        reader.readAsDataURL(this.PostData.files);
                    }
                }
            },
            deleteImage: function () {
                this.deletePreviewImage = 'deletebg';
                this.imagePreview = '';
                this.PostData.deletedImages = 1;
                this.showPreview=false
            }
        }
    }
</script>

<style scoped>
    .upload-article #article {
        border: 1px solid #a3adb4;
        padding-left: 5px;
    }
</style>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
