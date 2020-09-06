<template>
    <div class="stocks-inner new-addition blog">
        <breadcrumb name="Yenİ AKSİYA" icon="fa-certificate" :routes="[{'name':'Yeni Aksiya'}]"></breadcrumb>
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
                            <div class="date-hour-add">
                                <div class="row">
                                    <div class="col-md-6 col-sm-6">
                                        <div class="validate-block">
                                            <div class="upload-date">
                                                <div class="input-group input-up">
                                                    <div class="textup">
                                                        <datepicker placeholder="Başlama tarixi"
                                                                    v-model="newStock.date_start" :language="az"></datepicker>
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
                                                                    v-model="newStock.date_end" :language="az" :clear-button="clear_button" @cleared="datepickerClearedFunction"></datepicker>
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
                                        <div class="validation-block">
                                            <div class="input-group input-up">
                                                <label>
                                                    <input type="number" placeholder=" " class="form-control inputText"
                                                           v-model="newStock.price">
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
                                                       v-model="newStock.discount">
                                                <span>Endirim %</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="input-group input-up">
                                            <label>
                                                <input type="text" placeholder=" " class="form-control inputText"
                                                       v-model="newStock.organizer">
                                                <span>Orqanizator</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <multiselect @input="onChange(newStock.type)"
                                                         v-model="newStock.type"
                                                         label="name"
                                                         placeholder="Seçin"
                                                         selectedLabel="x"
                                                         selectLabel=""
                                                         deselectLabel=""
                                                         :searchable="true"
                                                         :options="newStock.organizer ? [] : newStock.stockTip"
                                                         track-by="id">
                                            </multiselect>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <multiselect v-model="newStock.connect_id"
                                                         label="name"
                                                         :custom-label="customLabel"
                                                         placeholder="Seçin"
                                                         selectedLabel="x"
                                                         selectLabel=""
                                                         deselectLabel=""
                                                         :searchable="true"
                                                         :options="newStock.organizer ? [] : newStock.options"
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
                                           v-model="newStock.headline">
                                    <span>Başlıq</span>
                                </label>
                            </div>
                            <span class="validation" v-if="errors.headline">{{errors.headline}}</span>
                        </div>
                        <div class="validate-block">
                            <div class="upload-article">
                                <p>Məqalə</p>
                                <tinymce-editor v-model="newStock.content" :init="tinymce_config"></tinymce-editor>
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
                                <input type="checkbox" v-model="newStock.status">
                                <span class="slider round"></span>
                            </label>
                            <span>Yayımla</span>
                        </div>
                        <div class="editor-buttons">
                            <!--<button type="button" class="delete-post">
                                SİL
                            </button>-->
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
    import Datepicker from 'vuejs-datepicker';
    import Multiselect from 'vue-multiselect';
    import {az} from 'vuejs-datepicker/dist/locale';
    import Swal from 'sweetalert2';

    export default {
        name: "newStock",
        components: {
            Datepicker,
            Multiselect,
            'tinymce-editor': Editor,
        },
        data: function () {
            return {
                az:az,
                newStock: {
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
                    optionsData: {
                        doctors: [],
                        corporative: []
                    },
                    stockTip: [],
                    options: []
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
                    files:false,
                    headline:false,
                    date_start:false,
                    date_end:false,
                    price:false,
                    content:false
                },
                previewImageClass: '',
                showPreview: false,
                imagePreview: '',
                clear_button: true,
                message: 'Şəkil ölçüsü 265x265px'
            }
        },
        mounted() {
            this.getTip();
            this.getDoctorList();
            this.getCorporativeList();
        },
        methods: {
            datepickerClearedFunction() {
              this.newStock.date_end = '';
            },
            submitFile() {
                this.resetErrors();
                let formdata = new FormData();
                if (this.newStock.date_start !== '') {
                    let dates = new Date(this.newStock.date_start);
                    formdata.append('date_start',dates.getFullYear() + "-" + ('0' + (dates.getMonth() + 1)).slice(-2) + "-" +('0'+dates.getDate()).slice(-2));
                }
                else {
                    formdata.append('date_start','');
                }

                if (this.newStock.date_end !== '') {
                    let datee = new Date(this.newStock.date_end);
                    formdata.append('date_end',datee.getFullYear() + "-" + ('0' + (datee.getMonth() + 1)).slice(-2) + "-" + ('0'+datee.getDate()).slice(-2));
                }
                else {
                    formdata.append('date_end','');
                }

                formdata.append('price',this.newStock.price);
                formdata.append('photo',this.newStock.files);
                formdata.append('discount',this.newStock.discount);
                formdata.append('content',this.newStock.content);
                formdata.append('organizer',this.newStock.organizer);
                formdata.append('type',this.newStock.type.id || '');
                formdata.append('headline',this.newStock.headline);
                formdata.append('connect_id',this.newStock.connect_id.id || '');
                formdata.append('status',this.newStock.status ? 1 : 0);
                formdata.append('id',this.id);
                axios.post('/promotions/create', formdata).then(
                    res => {
                        if(res.data.status == 200) {
                            this.$router.push("/stocks")
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
                    files:false,
                    headline:false,
                    date_start:false,
                    date_end:false,
                    price:false,
                    content:false
                }
            },
            handleFileUpload() {
                this.newStock.files = this.$refs.file.files[0];
                let reader = new FileReader();
                this.previewImageClass = 'changebg';
                reader.addEventListener("load", function () {
                    this.showPreview = true;
                    this.imagePreview = reader.result;
                }.bind(this), false);
                if (this.newStock.files) {
                    if (/\.(jpe?g|png|gif)$/i.test(this.newStock.files.name)) {
                        reader.readAsDataURL(this.newStock.files);
                    }
                }
            },
            getTip: function () {
                axios.get('promotions/types', this.type).then(
                    response => {
                        if (response.data.status !== 200) {
                            return Swal.fire({
                                type: 'error',
                                text: response.data.message()
                            })
                        }
                        for (let i in response.data.data) {
                            this.newStock.stockTip.push({
                                id: i,
                                name: response.data.data[i]
                            });

                        }
                    })
            },
            getDoctorList: function () {
                axios.get('doctors?type=all&count=1000000000', this.newStock.connect_id).then(
                    response => {
                        if (response.data.status !== 200) {
                            return Swal.fire({
                                type: 'error',
                                text: response.data.message()
                            })
                        }
                        this.newStock.optionsData.doctors = response.data.data.list;
                    })
            },
            getCorporativeList: function () {
                axios.get('enterprises?id=1&count=1000000000', this.newStock.connect_id).then(
                    response => {
                        if (response.data.status !== 200) {
                            return Swal.fire({
                                type: 'error',
                                text: response.data.message()
                            })
                        }
                        this.newStock.optionsData.corporative = response.data.data.list
                    })
            },
            onChange: function (type) {
              this.newStock.type = type;
              if (this.newStock.type !== null) {
                if (this.newStock.type.id === '1') {
                  this.newStock.options = this.newStock.optionsData.doctors;
                }
                else if (this.newStock.type.id === '2') {
                  this.newStock.options = this.newStock.optionsData.corporative;
                }
                else {
                  this.newStock.options = [];
                }
              }
              else {
                this.newStock.options = [];
              }
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
