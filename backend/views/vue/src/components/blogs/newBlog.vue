<template>
    <div class="stocks-inner new-addition blog">
        <breadcrumb name="Yenİ Bloq" icon="fa-certificate" :routes="[{'name':'Yeni Bloq'}]"></breadcrumb>
        <div class="row">
            <div class="col-md-4">
                <div class="blog-file-upload">
                    <div class="block">
                        <div class="upload-body addition-info">
                            <div class="validate-block" v-tooltip="message">
                                <div :class="previewImageClass" class="add-img-info">
                                    <div id="preview">
                                        <img v-bind:src="imagePreview" v-show="showPreview"/>
                                    </div>
                                    <div class="file-upload-wrapper" data-text="">
                                        <label class="custom-file-upload">
                                            <span><i class="fa fa-camera" aria-hidden="true"></i></span>
                                        </label>
                                        <input type="file" id="file" ref="file" accept="image/*"
                                               v-on:change="handleFileUpload()"/>
                                    </div>
                                </div>
                                <span class="validation" v-if="errors.files">{{errors.files}}</span>
                            </div>
                            <div class="cost-sale">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="input-group input-up">
                                            <label>
                                                <input type="text" placeholder=" " class="form-control inputText"
                                                       v-model="newNews.keywords">
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
                                                    <datepicker placeholder="Tarix" v-model="newNews.date" :language="az"></datepicker>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6">
                                        <div class="upload-hour">
                                            <div class="input-group input-up">
                                                <div class="textup">
                                                    <input type="text" v-model="newNews.time"
                                                           v-mask="timeMask_custom"
                                                           placeholder=" " class="form-control inputText">
                                                </div>
                                            </div>
                                        </div>
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
                                           v-model="newNews.headline">
                                    <span>Başlıq</span>
                                </label>
                            </div>
                            <span class="validation" v-if="errors.headline">{{errors.headline}}</span>
                        </div>
                        <div class="validate-block">
                            <div class="upload-article">
                                <p>Məqalə</p>
                                <tinymce-editor v-model="newNews.content" :init="tinymce_config"></tinymce-editor>
                                <span class="validation" v-if="errors.content">{{errors.content}}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="edit-post">
                    <div class="block">
                        <div class="switch-part ">
                            <span>Gizlə</span>
                            <label class="switch">
                                <input type="checkbox" v-model="newNews.status">
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
    import Datepicker from 'vuejs-datepicker';
    import Multiselect from 'vue-multiselect';
    import Timeselector from 'vue-timeselector';
    import {az} from 'vuejs-datepicker/dist/locale'
    export default {
        name: "newNews",
        components: {
            Datepicker,
            Multiselect,
            Timeselector,
            'tinymce-editor': Editor,
        },
        data: function () {
            return {
                az:az,
                newNews: {
                    files: '',
                    category_id: {id: 0, name: 'Kateqoriya'},
                    keywords: '',
                    content: '',
                    datetime: '',
                    date: '',
                    time: '',
                    headline:'',
                },
                message: 'Şəkil ölçüsü 850x570px',
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
                    headline:false,
                    files:false,
                    content:false
                },
                previewImageClass: '',
                showPreview: false,
                imagePreview: '',
                timeMask_custom: this.timeMask,
            }
        },
        mounted() {
            let date = new Date();
            this.newNews.date = date;
            this.newNews.time = date;
        },
        methods: {
            timeMask: function (value) {
                const hours = [
                    /[0-2]/,
                    value.charAt(0) === '2' ? /[0-3]/ : /[0-9]/,
                ];
                const minutes = [/[0-5]/, /[0-9]/];
                return value.length > 2
                    ? [...hours, ':', ...minutes]
                    : hours;
            },
            submitFile() {
                this.resetErrors();
                let formdata = new FormData();
                let date = new Date(this.newNews.date),
                    time = this.newNews.time,
                    dateFormat = date.getFullYear() + "-" + ("0" + (date.getMonth()+1)).slice(-2) + "-" + ("0" + date.getDate()).slice(-2),
                    combineDate = dateFormat + ' ' + time;
                formdata.append('datetime', combineDate);
                formdata.append('files', this.newNews.files);
                formdata.append('headline', this.newNews.headline);
                formdata.append('category_id', 34);
                formdata.append('content', this.newNews.content);
                formdata.append('keywords', this.newNews.keywords);
                formdata.append('status', this.newNews.status ? 1 : 0);
                axios.post('/news/create', formdata).then(
                        res =>{
                            if(res.data.status == 200) {
                                this.$router.push("/newsList?id=34")
                            }
                            if(res.data.status != 200){
                                this.errors=res.data.data
                            }
                        }).catch(error => {
                            this.errors=error.response.data.data;
                            for(let i in this.errors) {
                                if(this.errors !== false) this.errors[i]=this.errors[i][0]
                            }
                });
            },
            resetErrors: function(){
                this.errors = {
                    headline:false,
                    files:false,
                    content:false
                }
            },
            handleFileUpload() {
                this.newNews.files = this.$refs.file.files[0];
                let reader = new FileReader();
                this.previewImageClass = 'changebg';
                reader.addEventListener("load", function () {
                    this.showPreview = true;
                    this.imagePreview = reader.result;
                }.bind(this), false);
                if (this.newNews.files) {
                    if (/\.(jpe?g|png|gif)$/i.test(this.newNews.files.name)) {
                        reader.readAsDataURL(this.newNews.files);
                    }
                }
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
