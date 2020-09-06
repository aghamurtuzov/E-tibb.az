<template>
    <div class="stocks-inner new-addition blog">
        <breadcrumb name="XƏBƏR" icon="fa-newspaper-o" :routes="[{'name':'Xəbər'}]"></breadcrumb>
        <div class="row">
            <div class="col-md-4">
                <div class="blog-file-upload">
                    <div class="block">
                        <div class="upload-body addition-info">
                            <div class="validate-block" v-tooltip="message">
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
                                    <div class="col-md-12 col-sm-6">
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
                                    </div>
                                    <div class="col-md-12 col-sm-6">
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
                                                    <input type="text" v-model="PostData.time"
                                                           v-mask="timeMask_custom"
                                                           placeholder=" " class="form-control inputText">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12">
                                        <span class="validation" v-if="errors.datetime">{{errors.datetime}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="certificate">
                                <p class="info">ŞƏKİLLƏR*</p>
                                <div class="validate-block">
                                    <div class="certificate-list">
                                        <div class="certificate-images">
                                            <div v-for="(file, key) in PostData.gallery_images" class="diploma-images">
                                                <img v-if="file.file_photo_thumb" class="preview" :src="file.file_photo_thumb" alt="certificate">
                                                <img v-else class="preview" v-bind:ref="'image'+parseInt(key)" alt="certificate">
                                                <button class="delete-uploadimg" @click="deleteImage_dp(key)" ><span><i class="fa fa-trash-o" aria-hidden="true"></i></span></button>
                                            </div>
                                        </div>
                                        <div class="file-upload-wrapper" data-text="">
                                            <label class="custom-file-upload">
                                                <span><i class="fa fa-plus" aria-hidden="true"></i></span>
                                            </label>
                                            <input type="file" id="gallery_images" ref="gallery_images" accept="image/*" multiple v-on:change="handleFilesUpload_dp()"  name="gallery_images" class="file-upload-field">
                                        </div>
                                    </div>
                                    <span class="validation" v-if="errors.gallery_images">{{errors.gallery_images}}</span>
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
                                <tinymce-editor v-model="PostData.content" :init="tinymce_config"></tinymce-editor>
                              <!--  <textarea name="article" id="article" cols="30" rows="14" class="form-control"
                                          v-model="PostData.content">
                                </textarea>-->

                            </div>
                            <span class="validation" v-if="errors.content">{{errors.content}}</span>
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
    import Datepicker from 'vuejs-datepicker';
    import Multiselect from 'vue-multiselect';
    import {az} from 'vuejs-datepicker/dist/locale';
    import Swal from 'sweetalert2';

    export default {
        name: "newsEdit",
        components: {
            Datepicker,
            Multiselect,
            'tinymce-editor': Editor,

        },
        data: function () {
            return {
                az:az,
                id: this.$route.params.id,
                newsData:{},
                PostData: {
                    files: '',
                    category_id: {id: 0, name: 'Kateqoriya'},
                    keywords: '',
                    headline: '',
                    content: '',
                    datetime: '',
                    date: '',
                    time: '',
                    deletedImages: 0,
                    gallery_images: [],
                    deletedGalleryImages: []
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
                timeMask_custom: this.timeMask,
            }
        },
        mounted() {
            this.getNewsData();

            this.getCategory();
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
            submit: function () {
                this.resetErrors();
                let formdata = new FormData();
                let postData = this.PostData;
                let date = new Date(this.PostData.date),
                    time = this.PostData.time,
                    dateFormat = date.getFullYear() + "-" + ("0" + (date.getMonth()+1)).slice(-2) + "-" + ("0" + date.getDate()).slice(-2),
                    combineDate = dateFormat + ' ' + time;
                formdata.append('datetime', combineDate);
                formdata.append('files', this.PostData.files);
                formdata.append('headline', this.PostData.headline);
                formdata.append('content', this.PostData.content);
                formdata.append('keywords', this.PostData.keywords);
                formdata.append('category_id', this.PostData.category_id.id );
                formdata.append('status', this.PostData.status ? 1 : 0);
                formdata.append('deletedImages',this.PostData.deletedImages);
                for(let i in postData)
                {
                    switch (i) {
                        case 'gallery_images':
                            if (Array.isArray(postData[i])) {
                                for(let j in postData[i]){
                                    formdata.append(`images[`+j+`]`, postData[i][j]);
                                }
                            } else {
                                formdata.append(i, postData[i]);
                            }
                        case 'deletedGalleryImages':
                            if (Array.isArray(postData[i])) {
                                for(let j in postData[i]){
                                    formdata.append(`${i}[`+j+`]`, postData[i][j]);
                                }
                            } else {
                                formdata.append(i, postData[i]);
                            }
                        break
                    }
                }


                formdata.append('id',this.id);
                axios.post('/news/edit', formdata).then(
                    res =>{
                        if(res.data.status == 200) {
                            this.$router.push("/newsList?id=37")
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
            getNewsData: function () {
                axios.get('/news/info/' + this.id).then(
                    response => {
                        this.imagePreview = response.data.data.photo;
                        this.newsData = response.data.data;
                        this.PostData.files=this.newsData.files;
                        this.PostData.gallery_images=this.newsData.gallery_images;
                        this.PostData.keywords=this.newsData.keywords;
                        this.PostData.headline=this.newsData.headline;
                        this.PostData.status=this.newsData.status == '1';
                        this.PostData.content=this.newsData.content ;
                        let date = new Date(this.newsData.datetime);
                        this.PostData.date=date;
                        this.PostData.time= ("0" + date.getHours()).slice(-2) + ":" + ("0" + date.getMinutes()).slice(-2);
                        this.PostData.category_id = {
                            id: this.newsData.category_id,
                            name: this.newsData.category
                        };
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
            getCategory() {
                axios.get('news/categories', this.newsData.connect_id).then(
                    response => {
                        if (response.data.status !== 200) {
                            return Swal.fire({
                                type: 'error',
                                text: response.data.message()
                            })
                        }
                        for (let i in response.data.data) {
                                this.newsTip.push({
                                    id: response.data.data[i].id,
                                    name: response.data.data[i].name
                                });

                        }
                    })
            },
            deleteImage: function () {
                this.deletePreviewImage = 'deletebg';
                this.imagePreview = '';
                this.PostData.deletedImages = 1;
                this.showPreview=false
            },
            deleteImage_dp:function (key) {
                let deletedGalleryImage = this.PostData.deletedGalleryImages;
                deletedGalleryImage.push(this.PostData.gallery_images[key].id);
                this.PostData.deletedGalleryImages = deletedGalleryImage;
                this.PostData.gallery_images.splice(key,1);
            },
            deleteImage_dpTmp:function (item){
                this.PostData.gallery_images.splice(this.PostData.gallery_images.indexOf(item), 1);
            },
            handleFilesUpload_dp(){
                let dp_uploadedFiles = this.$refs.gallery_images.files;
                for( var i = 0; i < dp_uploadedFiles.length; i++ ){
                    this.PostData.gallery_images.push( dp_uploadedFiles[i] );
                }
                this.getImagePreviews_dp();
            },
            getImagePreviews_dp(){
                for( let i = 0; i < this.PostData.gallery_images.length; i++ ){
                    if ( /\.(jpe?g|png|gif)$/i.test( this.PostData.gallery_images[i].name ) ) {
                        if (this.PostData.gallery_images[i].file_photo) {
                           console.log(this.PostData.gallery_images[i]);
                        }
                        else {
                            let reader = new FileReader();
                            reader.addEventListener("load", function(){
                                this.$refs['image'+parseInt( i )][0].src = reader.result;
                            }.bind(this), false);
                            reader.readAsDataURL( this.PostData.gallery_images[i] );
                        }
                    }
                }
            },
        }
    }

</script>

<style scoped>

    .upload-article #article {
        border: 1px solid #a3adb4;
        padding-left: 5px;
    }

    .certificate {
        margin-top: 30px;
    }

    .certificate .info {
        color: #3d4244;
        font-size: 16px;
        font-weight: 700;
        text-transform: uppercase;
    }

</style>
