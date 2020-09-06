<template>
    <div class="stocks-inner new-addition blog">
        <breadcrumb name="AKSİYA" icon="fa-certificate" :routes="[{'name':'Aksiya'}]"></breadcrumb>
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
                                        <button class="delete-uploadimg" @click="deleteImage()"><span><i class="fa fa-trash-o" aria-hidden="true"></i></span></button>
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
                            <div class="date-hour-add">
                                <div class="row">
                                    <div class="col-md-6 col-sm-6">
                                        <div class="validate-block">
                                            <div class="upload-date">
                                                <div class="input-group input-up">
                                                    <div class="textup">
                                                        <datepicker placeholder="Başlama tarixi"
                                                                    v-model="StockData.date_start" :language="az"></datepicker>
                                                    </div>
                                                </div>
                                            </div>
                                            <span class="validation" v-if="errors.date_start">{{errors.date_start}}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6">
                                        <div class="validate-block">
                                            <div class="upload-hour">
                                                <div class="input-group input-up">
                                                    <div class="textup">
                                                        <datepicker placeholder="Bitiş tarixi"
                                                                    v-model="StockData.date_end" :language="az"></datepicker>
                                                    </div>
                                                </div>
                                            </div>
                                            <span class="validation" v-if="errors.date_end">{{errors.date_end}}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="cost-sale">
                                <div class="row">
                                    <div class="col-md-6 col-sm-6">
                                        <div class="validate-block">
                                            <div class="input-group input-up">
                                                <label>
                                                    <input type="number" placeholder=" " class="form-control inputText"
                                                           v-model="StockData.price">
                                                    <span>Qiymət</span>
                                                </label>
                                            </div>
                                            <span class="validation" v-if="errors.price">{{errors.price}}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6">
                                        <div class="input-group input-up">
                                            <label>
                                                <input type="number" placeholder=" " class="form-control inputText"
                                                       v-model="StockData.discount">
                                                <span>Endirim %</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="input-group input-up">
                                            <label>
                                                <input type="text" placeholder=" " class="form-control inputText"
                                                       v-model="StockData.organizer">
                                                <span>Orqanizator</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <multiselect v-model="StockData.type"
                                                         label="name"
                                                         selectedLabel="x"
                                                         deselectGroupLabel=""
                                                         deselectLabel=""
                                                         select-label=""
                                                         :options="StockData.organizer ? [] : stockTip"
                                                         track-by="id">
                                            </multiselect>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <multiselect v-model="StockData.connect_id"
                                                         label="name"
                                                         :custom-label="customLabel"
                                                         placeholder="Seçin"
                                                         selectedLabel="x"
                                                         deselectGroupLabel=""
                                                         deselectLabel=""
                                                         select-label=""
                                                         :searchable="true"
                                                         :options="(StockData.organizer ? [] : (((StockData.type.id !== null) && (StockData.type.id == 1)) ? optionsData.doctors : optionsData.corporative))"
                                                         :options-limit="50"
                                                         track-by="id">
                                            </multiselect>
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
                                           v-model="StockData.headline">
                                    <span>Başlıq</span>
                                </label>
                            </div>
                            <span class="validation" v-if="errors.headline">{{errors.headline}}</span>
                        </div>
                        <div class="validate-block">
                            <div class="upload-article">
                                <p>Məqalə</p>
                                <tinymce-editor v-model="StockData.content" :init="tinymce_config"></tinymce-editor>

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
                                <input type="checkbox" v-model="StockData.status">
                                <span class="slider round"></span>
                            </label>
                            <span>Yayımla</span>
                        </div>
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
    import {axios} from '../config/axios';
    import Datepicker from 'vuejs-datepicker';
    import Multiselect from 'vue-multiselect';
    import {az} from 'vuejs-datepicker/dist/locale';
    import Swal from 'sweetalert2';
    export default {
        name: "Stock",
        components: {
            Multiselect,
            Datepicker,
            'tinymce-editor': Editor,
        },
        data: function () {
            return {
                az:az,
                id: this.$route.params.id,
                newStock:{},
                StockData: {
                    files: '',
                    date_start: '',
                    date_end: '',
                    price: '',
                    discount: '',
                    content: '',
                    organizer: '',
                    check: '',
                    headline: '',
                    type: {id: 0, name: 'Tip'},
                    connect_id: {id: 0, name: 'Əlaqədar'},
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
                    height: 400,
                },
                optionsData: {
                    doctors: [],
                    corporative: []
                },
                previewImageClass: '',
                showPreview: true,
                imagePreview: '',
                deletePreviewImage: '',
                stockTip: [],
                errors:{
                    files:false,
                    headline:false,
                    date_start:false,
                    date_end:false,
                    price:false,
                    content:false
                },
                message: 'Şəkil ölçüsü 265x265px'
            }
        },
        mounted() {
            this.init();
        },
        methods: {
            submit: function(){
                this.resetErrors();
                let formdata = new FormData();
                let dates = new Date(this.StockData.date_start);
                let datee = new Date(this.StockData.date_end);
                formdata.append('date_start',dates.getFullYear() + "-" + ('0' + (dates.getMonth() + 1)).slice(-2) + "-" +('0'+dates.getDate()).slice(-2));
                formdata.append('date_end',datee.getFullYear() + "-" + ('0' + (datee.getMonth() + 1)).slice(-2) + "-" + ('0'+datee.getDate()).slice(-2));
                formdata.append('price',this.StockData.price);
                formdata.append('discount',this.StockData.discount);
                formdata.append('content',this.StockData.content);
                formdata.append('organizer',this.StockData.organizer);
                formdata.append('type',this.StockData.type.id || '');
                formdata.append('headline',this.StockData.headline);
                formdata.append('connect_id',this.StockData.connect_id.id || '' );
                formdata.append('status',this.StockData.status ? 1 : 0);
                formdata.append('deletedImages',this.StockData.deletedImages);
                formdata.append('photo',this.StockData.files);
                formdata.append('id',this.id);
                axios.post('/promotions/edit', formdata).then(
                    res => {
                        if(res.data.status==200) {
                            this.$router.push('/stocks')
                        }
                        if(res.data.status!=200){
                            this.errors=res.data.data
                        }
                    }).catch(error=>{
                        this.errors=error.response.data.data;
                        for(let i in this.errors) {
                            if(this.errors !== false) this.errors[i]=this.errors[i][0]
                        }
                });
            },
            resetErrors:function(){
                this.errors= {
                    files:false,
                    headline:false,
                    date_start:false,
                    date_end:false,
                    price:false,
                    content:false
                }
            },
            getStockData: function () {
                axios.get('/promotions/info/' + this.$route.params.id).then(
                    response => {
                        this.imagePreview = response.data.data.photo;
                        this.newStock = response.data.data;
                        this.StockData.date_start=this.newStock.date_start;
                        this.StockData.date_end=this.newStock.date_end;
                        this.StockData.price=this.newStock.price;
                        this.StockData.files=this.newStock.files;
                        this.StockData.discount=this.newStock.discount;
                        this.StockData.content=this.newStock.content;
                        this.StockData.organizer=this.newStock.organizer;
                        this.StockData.type.id =this.newStock.type === null ? 0 : this.newStock.type.id;
                        this.StockData.headline=this.newStock.headline;
                        this.StockData.connect_id.id=this.newStock.connect_id === null ? 0 : this.newStock.connect_id.id;
                        this.StockData.status=this.newStock.status == '1' ;

                        for(let i of this.stockTip){
                            if(this.newStock.type == i.id){
                                this.StockData.type = {
                                    id: this.newStock.type.toString(),
                                    name: i.name
                                };
                            }
                        }
                        let obj = this.StockData.type.id == 1 ? this.optionsData.doctors : this.optionsData.corporative;
                        for(let i of obj){
                            if(parseInt(this.newStock.connect_id) == parseInt(i.id)){
                                this.StockData.connect_id = {
                                    id: i.id,
                                    name: i.name
                                }
                            }
                        }

                    }
                )
            },
            getDoctorList: function () {
                axios.get('doctors?count=1000000000&type=all').then(
                    response => {
                        if (response.data.status !== 200) {
                            return Swal.fire({
                                type: 'error',
                                text: response.data.message
                            })
                        }
                        this.optionsData.doctors = response.data.data.list;
                    })
            },
            getCorporativeList: function () {
                axios.get('enterprises?id=1&count=1000000000').then(
                    response => {
                        if (response.data.status !== 200) {
                            return Swal.fire({
                                type: 'error',
                                text: response.data.message
                            })
                        }
                        this.optionsData.corporative = response.data.data.list;
                    })
            },
            handleFileUpload() {
                this.StockData.files = this.$refs.file.files[0];
                let reader = new FileReader();
                this.previewImageClass = 'changebg';
                reader.addEventListener("load", function () {
                    this.showPreview = true;
                    this.imagePreview = reader.result;
                }.bind(this), false);
                if (this.StockData.files) {
                    if (/\.(jpe?g|png|gif)$/i.test(this.StockData.files.name)) {
                        reader.readAsDataURL(this.StockData.files);
                    }
                }
            },
            getTip: function () {
                axios.get('promotions/types').then(
                    response => {
                        if (response.data.status !== 200) {
                            return Swal.fire({
                                type: 'error',
                                text: response.data.message()
                            })
                        }
                        for (let i in response.data.data) {
                            this.stockTip.push({
                                id: i,
                                name: response.data.data[i]
                            })
                        }
                    })
            },
            deleteImage: function () {
                this.deletePreviewImage = 'deletebg';
                this.imagePreview = '';
                this.StockData.deletedImages = 1;
                this.showPreview=false
            },
            init: async function(){
                axios.get('doctors?count=1000000000&type=all').then(
                    response => {
                        if (response.data.status !== 200) {
                            return Swal.fire({
                                type: 'error',
                                text: response.data.message
                            })
                        }
                        this.optionsData.doctors = response.data.data.list;
                        axios.get('enterprises?id=1').then(
                            response => {
                                if (response.data.status !== 200) {
                                    return Swal.fire({
                                        type: 'error',
                                        text: response.data.message
                                    })
                                }
                                this.optionsData.corporative = response.data.data.list;
                                axios.get('promotions/types').then(
                                    response => {
                                        if (response.data.status !== 200) {
                                            return Swal.fire({
                                                type: 'error',
                                                text: response.data.message()
                                            })
                                        }
                                        for (let i in response.data.data) {
                                            this.stockTip.push({
                                                id: i,
                                                name: response.data.data[i]
                                            })
                                        }

                                        axios.get('/promotions/info/' + this.$route.params.id).then(
                                            response => {
                                                this.imagePreview = response.data.data.photo;
                                                this.newStock = response.data.data;
                                                this.StockData.date_start=this.newStock.date_start;
                                                this.StockData.date_end=this.newStock.date_end;
                                                this.StockData.price=this.newStock.price;
                                                this.StockData.files=this.newStock.files;
                                                this.StockData.discount=this.newStock.discount;
                                                this.StockData.content=this.newStock.content;
                                                this.StockData.organizer=this.newStock.organizer;
                                                this.StockData.type.id =this.newStock.type === null ? 0 : this.newStock.type.id;
                                                this.StockData.headline=this.newStock.headline;
                                                this.StockData.connect_id.id=this.newStock.connect_id === null ? 0 : this.newStock.connect_id.id;
                                                this.StockData.status=this.newStock.status == '1' ;

                                                for(let i of this.stockTip){
                                                    if(this.newStock.type == i.id){
                                                        this.StockData.type = {
                                                            id: this.newStock.type.toString(),
                                                            name: i.name
                                                        };
                                                    }
                                                }
                                                let obj = this.StockData.type.id == 1 ? this.optionsData.doctors : this.optionsData.corporative;
                                                for(let i of obj){
                                                    if(parseInt(this.newStock.connect_id) == parseInt(i.id)){
                                                        this.StockData.connect_id = {
                                                            id: i.id,
                                                            name: i.name
                                                        }
                                                    }
                                                }

                                            }
                                        )
                                    })
                            })
                    })
            },
            customLabel({name, email}) {
              if (email) {
                return `${name} – ${email}`;
              }
              else {
                return `${name}`;
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
