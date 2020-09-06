<template>
    <div class="doctor-component stocks-inner new-addition blog">
        <breadcrumb name="BLOQ" icon="fa-newspaper-o" :routes="[{'name':'Bloq'}]"></breadcrumb>
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
                                    <div class="col-md-12">
                                        <div class="input-group input-up">
                                            <label>
                                                <input type="text" placeholder=" " class="form-control inputText"
                                                       v-model="BlogData.keywords">
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
                                                    <datepicker v-model="BlogData.date" :language="az"></datepicker>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6">
                                        <div class="upload-hour">
                                            <div class="input-group input-up">
                                                <div class="textup">
                                                    <timeselector v-model="BlogData.time"></timeselector>
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
                                           v-model="BlogData.headline">
                                    <span>Başlıq</span>
                                </label>
                            </div>
                            <span class="validation" v-if="errors.headline">{{errors.headline}}</span>
                        </div>
                        <div class="validate-block">
                            <div class="upload-article">
                                <p>Məqalə</p>
                                <textarea name="article" id="article" cols="30" rows="14" class="form-control"
                                          v-model="BlogData.content"></textarea>
                            </div>
                            <span class="validation" v-if="errors.content">{{errors.content}}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="edit-post">
                    <div class="block">
                        <!--<div class="switch-part ">
                            <span>Gizlə</span>
                            <label class="switch">
                                <input type="checkbox" v-model="BlogData.status">
                                <span class="slider round"></span>
                            </label>
                            <span>Yayımla</span>
                        </div>-->
                        <div class="editor-buttons">
                            <!--<button type="button" class="delete-post">
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
    import {axios} from '../config/doctor_axios';
    import Datepicker from 'vuejs-datepicker';
    import {az} from 'vuejs-datepicker/dist/locale'
    import Multiselect from 'vue-multiselect';
    import Timeselector from 'vue-timeselector';
    export default {
        name: "Blog",
        components: {
            Datepicker,
            Multiselect,
            Timeselector
        },
        data: function () {
            return {
                az:az,
                id: this.$route.params.id,
                newBlog:{},
                BlogData: {
                    files: '',
                    keywords: '',
                    headline: '',
                    content: '',
                    datetime: '',
                    date: '',
                    time: '',
                    deletedImages: 0,
                },
                previewImageClass: '',
                showPreview: true,
                imagePreview: '',
                deletePreviewImage: '',
                errors:{
                    headline:false,
                    files:false,
                    content:false
                },
            }
        },
        mounted() {
            this.getNewsData();
        },
        methods: {
            submit: function(){
                this.resetErrors();
                let formdata = new FormData();
                let date = new Date(this.BlogData.date),
                    time = this.BlogData.time,
                    dateFormat = date.getFullYear() + "-" + ("0" + (date.getMonth()+1)).slice(-2) + "-" + ("0" + date.getDate()).slice(-2),
                    timeFormat = ("0" + time.getHours()).slice(-2) + ":" + ("0" + time.getMinutes()).slice(-2),
                    combineDate = dateFormat + ' ' + timeFormat;
                formdata.append('datetime', combineDate);
                formdata.append('files', this.BlogData.files);
                formdata.append('headline', this.BlogData.headline);
                formdata.append('content', this.BlogData.content);
                formdata.append('keywords', this.BlogData.keywords);
                formdata.append('status', this.BlogData.status ? 1 : 0);
                formdata.append('deletedImages',this.BlogData.deletedImages);
                formdata.append('id',this.id);
                axios.post('/news/edit', formdata).then(
                    res =>{
                        if(res.data.status == 200) {
                            this.$router.push("/blogs")
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
            resetErrors: function(){
                this.errors = {
                    headline:false,
                    files:false,
                    content:false
                }
            },
            getNewsData: function () {
                axios.get('/news/info/?id='+this.$route.params.id).then(
                    response => {
                        this.imagePreview = response.data.data.photo;
                        this.newBlog = response.data.data;
                        this.BlogData.files=this.newBlog.files;
                        this.BlogData.keywords=this.newBlog.keywords;
                        this.BlogData.headline=this.newBlog.headline;
                        this.BlogData.content=this.newBlog.content ;
                        let date = new Date(this.newBlog.datetime);
                        this.BlogData.date=date;
                        this.BlogData.time=date;
                    }
                )
            },
            handleFileUpload() {
                this.BlogData.files = this.$refs.file.files[0];
                let reader = new FileReader();
                this.previewImageClass = 'changebg';
                reader.addEventListener("load", function () {
                    this.showPreview = true;
                    this.imagePreview = reader.result;
                }.bind(this), false);
                if (this.BlogData.files) {
                    if (/\.(jpe?g|png|gif)$/i.test(this.BlogData.files.name)) {
                        reader.readAsDataURL(this.BlogData.files);
                    }
                }
            },
            deleteImage: function () {
                this.deletePreviewImage = 'deletebg';
                this.imagePreview = '';
                this.BlogData.deletedImages = 1;
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
