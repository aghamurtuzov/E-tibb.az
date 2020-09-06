<template>
    <div class="object-new clinic-new new-addition blog">
        <breadcrumb name="Yenİ Obyekt" icon="fa-building-o" :routes="[{'name':'Yeni obyekt'}]"></breadcrumb>
        <div class="row">
            <div class="col-md-7">
                <div class="search_enterprise">
                    <div class="block">
                        <div class="general-info">
                                <div class="input-group">
                                  <span style="display: none">test</span>
                                    <multiselect v-model="newEnterprises.category_id"
                                                 label="name" selectedLabel=""
                                                 deselectGroupLabel=""
                                                 deselectLabel=""
                                                 select-label=""
                                                 :reset-after="false"
                                                 placeholder="Obyekt növünü seçin"
                                                 :allow-empty="false"
                                                 :internal-search="false"
                                                 :close-on-select="true"
                                                 :clear-on-select="false"
                                                 :hide-selected="false"
                                                 :options="categories"
                                                 track-by="id" >
                                    </multiselect>
                                </div>
                        </div>
                    </div>
                </div>
                <div class="addition-info">
                    <div class="block">
                        <div class="row">
                            <div class="col-lg-4 col-md-5 col-sm-5">
                                <div class="validate-block" v-tooltip="message">
                                    <div :class="previewImageClass" class="add-img-info">
                                        <div id="preview">
                                            <img v-bind:src="imagePreview" v-show="showPreview"/>
                                        </div>
                                        <div class="file-upload-wrapper" data-text="">
                                            <label class="custom-file-upload">
                                                <span><i class="fa fa-camera" aria-hidden="true"></i></span>
                                            </label>
                                            <input type="file" id="file" ref="file" accept="image/*" v-on:change="handleFileUpload()"/>
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
                                                <input type="text" placeholder=" " class="form-control inputText" v-model="newEnterprises.name">
                                                <span>Obyektin adı*</span>
                                            </label>
                                        </div>
                                        <span class="validation" v-if="errors.name">{{errors.name}}</span>
                                    </div>
                                    <div class="input-group input-up">
                                        <label>
                                            <input type="text" placeholder=" " class="form-control inputText" v-model="newEnterprises.company_email[0]">
                                            <span>Obyekt E-maili</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="addition-workhours">
                    <div class="block">
                        <p class="info">İş günləri və saatları</p>
                        <div class="row">
                            <div class="col-md-4 col-sm-4">
                                <div class="validate-block">
                                    <div class="general-info">
                                        <div class="input-group input-up">
                                            <label>
                                                <input type="text" placeholder=" " class="form-control inputText" v-model="newEnterprises.weekdays">
                                                <span>B.e - Cümə (09:00-18:00)</span>
                                            </label>
                                        </div>
                                        <span class="validation" v-if="errors.weekdays">{{errors.weekdays}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="validate-block">
                                    <div class="general-info">
                                        <div class="input-group input-up">
                                            <label>
                                                <input type="text" placeholder=" " class="form-control inputText" v-model="newEnterprises.saturday">
                                                <span>Şənbə (09:00-18:00)</span>
                                            </label>
                                        </div>
                                        <span class="validation" v-if="errors.saturday">{{errors.saturday}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <div class="validate-block">
                                    <div class="general-info">
                                        <div class="input-group input-up">
                                            <label>
                                                <input type="text" placeholder=" " class="form-control inputText" v-model="newEnterprises.sunday">
                                                <span>Bazar (09:00-18:00)</span>
                                            </label>
                                        </div>
                                        <span class="validation" v-if="errors.sunday">{{errors.sunday}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="addition-user">
                    <div class="block">
                        <p class="info">Əlaqələndİrİcİ şəxsin adı</p>
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="general-info">
                                    <div class="validate-block">
                                        <div class="input-group input-up">
                                            <label>
                                                <input type="text" placeholder=" " class="form-control inputText" v-model="newEnterprises.contact_name">
                                                <span>Əlaqələndirici şəxs*</span>
                                            </label>
                                        </div>
                                        <span class="validation" v-if="errors.contact_name">{{errors.contact_name}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="general-info">
                                    <div class="validate-block">
                                        <div class="input-group input-up">
                                            <label>
                                                <input type="text" placeholder=" " class="form-control inputText" v-model="newEnterprises.email">
                                                <span>Əlaqələndirici şəxsin E-maili*</span>
                                            </label>
                                        </div>
                                        <span class="validation" v-if="errors.email">{{errors.email}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="general-info">
                                    <div class="validate-block">
                                        <div class="input-group input-up">
                                            <label>
                                                <input type="text" placeholder=" " class="form-control inputText" v-model="newEnterprises.contact_phone" maxlength="12" minlength="12">
                                                <span>Əlaqələndirici şəxsin telefonu* (994551231122)</span>
                                            </label>
                                        </div>
                                        <span class="validation" v-if="errors.contact_phone">{{errors.contact_phone}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="general-info">
                                    <div class="validate-block">
                                        <div class="input-group input-up">
                                            <div class="textup">
                                                <datepicker  placeholder="Əlaqələndirici şəxsin doğum günü*" v-model="newEnterprises.contact_birthday" :language="az"></datepicker>
                                            </div>
                                        </div>
                                        <span class="validation" v-if="errors.contact_birthday">{{errors.contact_birthday}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="addition-contact addition-enterprise-contact">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="block">
                                <p class="info">Obyekt TELEFONU</p>
                                <div v-for="(item, index) in phone_numbers">
                                    <div class="general-info addListContainer phoneList flex-end">
                                        <div class="input-group input-up">
                                            <label>
                                                <input type="number" placeholder=" " class="form-control inputText" v-model="phone_numbers[index].number" maxlength="12" minlength="12">
                                                <span>Obyekt telefonu* (994551231122)</span>
                                            </label>
                                        </div>
                                        <button type="button" class="btn btn-addition" @click="addPhone()" v-if="index === 0"><span>+</span></button>
                                        <button type="button" class="btn btn-cancel" @click="removePhone(index)" v-if="index > 0"><span>-</span></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="block">
                                <p class="info">ObyektİN Mobİl telefonu</p>
                                <div v-for="(item, index) in mobile_numbers">
                                    <div class="general-info addListContainer mobileList flex-end">
                                        <div class="input-group input-up">
                                            <label>
                                                <input type="number" placeholder=" " class="form-control inputText" v-model="mobile_numbers[index].number" maxlength="12" minlength="12">
                                                <span>Obyekt Mobil telefonu* (994551231122)</span>
                                            </label>
                                        </div>
                                        <button type="button" class="btn btn-addition" @click="addMobile()" v-if="index === 0"><span>+</span></button>
                                        <button type="button" class="btn btn-cancel" @click="removeMobile(index)" v-if="index > 0"><span>-</span></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="addition-address">
                    <div class="block">
                        <p class="info">ŞİRKƏT ÜNVANI</p>
                        <div v-for="(item, index) in newEnterprises.addresses">
                            <div class="general-info workList addListContainer flex-end">
                                <div class="input-group input-up">
                                    <label>
                                        <input type="text" placeholder=" " class="form-control inputText" v-model="newEnterprises.addresses[index].name">
                                        <span>Obyekt ünvanı*</span>
                                    </label>
                                </div>
                                <button type="button" class="btn btn-addition" @click="addWork()" v-if="index === 0"><span>+</span></button>
                                <button type="button" class="btn btn-cancel" @click="removeWork(index)" v-if="index > 0"><span>-</span></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="bio-diplom pts-20">
                    <div class="block">
                        <div class="biography">
                            <p class="info">Obyekt haqqında*</p>
                            <div class="validate-block">
                                <div class="input-group">
                                    <textarea name="" id="" cols="30" rows="5" class="form-control" v-model="newEnterprises.about"></textarea>
                                    <span class="validation" v-if="errors.about">{{errors.about}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="certificate">
                            <p class="info"> sertİFİKATLAR</p>
                            <div class="certificate-list">
                                <div class="certificate-images">
                                    <div v-for="(file, key) in newEnterprises.ct_files" class="diploma-images">
                                        <img class="preview"  v-bind:ref="'img'+parseInt( key )"  alt="certificate">
                                        <button class="delete-uploadimg" @click="deleteImage_ctTmp(file)"><span><i class="fa fa-trash-o" aria-hidden="true"></i></span></button>
                                    </div>
                                </div>
                                <div class="file-upload-wrapper" data-text="">
                                    <label class="custom-file-upload">
                                        <span><i class="fa fa-plus" aria-hidden="true"></i></span>
                                    </label>
                                    <input type="file" id="ct_files" ref="ct_files" accept="img/*" multiple v-on:change="handleFilesUpload_ct()"  name="file-upload-field" class="file-upload-field">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="social-block">
                    <div class="block">
                        <p class="info">Sosİal şəbəkələr</p>
                        <div class="general-info">
                            <ul  class="list-unstyled">
                                <li v-for="(social,index) in newEnterprises.sosial_links">
                                    <div class="input-group input-up">
                                        <label>
                                            <input type="text" placeholder=" " v-model="newEnterprises.sosial_links[index].link" class="form-control inputText" >
                                            <span :class=social.name>{{social.name}}</span>
                                        </label>
                                    </div>
                                </li>
                                <li>
                                    <div class="input-group input-up">
                                        <label>
                                            <input type="number" placeholder=" " v-model="phones[0].number" class="form-control inputText" maxlength="12" minlength="12">
                                            <span class="Whatsapp">Whatsapp (994551231122)</span>
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
                                <input type="checkbox" v-model="newEnterprises.eve_caqiris">
                                <span class="checkmark"></span>
                            </label>
                            <label class="check-button">
                                <span>Çatdırılma</span>
                                <input type="checkbox" v-model="newEnterprises.catdirilma">
                                <span class="checkmark"></span>
                            </label>
                            <label class="check-button">
                                <span>24 Saat</span>
                                <input type="checkbox" v-model="newEnterprises.saat24">
                                <span class="checkmark"></span>
                            </label>
                        </div>
                        <button type="button" @click="submitFile()" class="btn">ƏLAVƏ ET</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Multiselect from 'vue-multiselect';
    import {axios} from "../config/axios";
    import Datepicker from 'vuejs-datepicker';
    import {az} from 'vuejs-datepicker/dist/locale'
    export default {
        name: "newEnterprises",
        components:{
            Datepicker,
            Multiselect
        },
        data:function () {
            return {
                az:az,
                newEnterprises:{
                    category_id:'',
                    files: '',
                    name:'',
                    company_email:[],
                    weekdays:'',
                    saturday:'',
                    sunday:'',
                    contact_name:'',
                    email:'',
                    contact_phone:'',
                    contact_birthday:'',
                    phone_numbers: [],
                    addresses:[{name:''}],
                    eve_caqiris:'',
                    catdirilma:'',
                    saat24:'',
                    about:'',
                    ct_files:[],
                    sosial_links:[],
                },
                phone_numbers: [
                    {type:0,number:''},
                ],
                mobile_numbers:[
                    {type:1,number:''}
                ],
                phones:[
                    {type:2,number:''}
                ],
                categories:[],
                previewImageClass: '',
                url: null,
                showPreview: false,
                imagePreview: '',
                errors:{
                    contact_birthday:false,
                    contact_name:false,
                    contact_phone:false,
                    email:false,
                    files:false,
                    name:false,
                    about:false,
                    saturday:false,
                    sunday:false,
                    weekdays:false
                },
                message: 'Şəkil ölçüsü 265x265px',
            }
        },
        mounted() {
            this.getEnterprisesCategory();
            this.getSocials();
        },
        methods:{
            submitFile() {
                this.resetErrors();
                this.newEnterprises.phone_numbers = this.phone_numbers.concat(this.mobile_numbers, this.phones);
                let formdata= new FormData();
                let postData = this.newEnterprises;
                let date = new Date(this.newEnterprises.contact_birthday);
                let category_id_old = this.newEnterprises.category_id;
                postData.contact_birthday = date.getFullYear() + "-" + ('0' + (date.getMonth() +1) ).slice(-2)+ "-" + ('0'+date.getDate()).slice(-2);
                postData.eve_caqiris = this.newEnterprises.eve_caqiris ? 1 : 0;
                postData.catdirilma = this.newEnterprises.catdirilma ? 1 : 0;
                postData.saat24 = this.newEnterprises.saat24 ? 1 : 0;
                postData.category_id = this.newEnterprises.category_id.id || 0;

                for(let i in postData){
                    switch (i) {
                        case 'addresses' :
                            for(let j in postData[i]){
                                formdata.append(`${i}[`+j+`][name]`, postData[i][j].name);
                            }
                            break
                        case 'sosial_links' :
                            for(let j in postData[i]){
                                formdata.append(`${i}[`+j+`][type]`, j);
                                formdata.append(`${i}[`+j+`][link]`, postData[i][j].link);
                            }
                            break
                        case 'phone_numbers':
                            for(let j in postData[i]){
                                formdata.append(`${i}[`+j+`][type]`, postData[i][j].type);
                                formdata.append(`${i}[`+j+`][number]`, postData[i][j].number);
                            }
                            break
                        default :
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
                axios.post('/enterprises/create', formdata).then (
                    res =>{
                        if (res.data.status == 200) {
                            this.$router.push('/enterprises?id='+postData.category_id)
                        }
                        else {
                            this.errors = res.data.data
                        }
                    }).catch(error => {
                        postData.category_id = category_id_old;
                        this.errors = error.response.data.data;
                        for(let i in this.errors) {
                            if(this.errors !== false) this.errors[i] =this.errors[i][0]
                        }
                });
            },

            resetErrors: function(){
                this.errors = {
                    contact_birthday:false,
                    contact_name:false,
                    contact_phone:false,
                    email:false,
                    files:false,
                    name:false,
                    about:false,
                    saturday:false,
                    sunday:false,
                    weekdays:false
                }
            },
            getEnterprisesCategory:function () {
                axios.get('/enterprises/categories',this.category_id).then(
                    response=> {
                        for(let i in response.data.data){
                            if ((response.data.data[i].id === 1) || (response.data.data[i].id === 6)) {
                              this.categories.push({
                                id: response.data.data[i].id,
                                name: response.data.data[i].name
                              })
                            }
                        }
                    }
                )
            },
            handleFileUpload() {
                this.newEnterprises.files = this.$refs.file.files[0];
                let reader = new FileReader();
                this.previewImageClass = 'changebg';
                reader.addEventListener("load", function () {
                    this.showPreview = true;
                    this.imagePreview = reader.result;
                }.bind(this), false);
                if (this.newEnterprises.files) {
                    if (/\.(jpe?g|png|gif)$/i.test(this.newEnterprises.files.name)) {
                        reader.readAsDataURL(this.newEnterprises.files);
                    }
                }
            },
            addPhone() {
                const phoneList = {
                    type:0,
                    number:''
                };
                this.phone_numbers.push(phoneList)
            },
            removePhone(index){
                this.phone_numbers.splice(index,1)
            },
            addMobile() {
                const mobileList = {
                    type:1,
                    number:''
                };
                this.mobile_numbers.push(mobileList)
            },
            removeMobile(index){
                this.mobile_numbers.splice(index,1)
            },
            addWork() {
                const workList = {
                    name:''
                };
                this.newEnterprises.addresses.push(workList)
            },
            removeWork(index){
                this.newEnterprises.addresses.splice(index,1)
            },

            deleteImage_ctTmp:function (item){
                this.newEnterprises.ct_files.splice(this.newEnterprises.ct_files.indexOf(item), 1);
            },

            handleFilesUpload_ct(){
                let ct_uploadedFiles = this.$refs.ct_files.files;
                for( var j = 0; j < ct_uploadedFiles.length; j++ ){
                    this.newEnterprises.ct_files.push( ct_uploadedFiles[j] );
                }
                this.getImagePreviews_ct();
            },
            getImagePreviews_ct(){
                for( let j = 0; j < this.newEnterprises.ct_files.length; j++ ){
                    if ( /\.(jpe?g|png|gif)$/i.test( this.newEnterprises.ct_files[j].name ) ) {
                        let reader = new FileReader();
                        reader.addEventListener("load", function(){
                            this.$refs['img'+parseInt( j )][0].src = reader.result;
                        }.bind(this), false);
                        reader.readAsDataURL( this.newEnterprises.ct_files[j] );
                    }
                }
            },
            getSocials: function () {
                axios.post('/general/list-sosial-link').then(
                    response=> {
                        if (response.data.status !== 200) {
                            return Swal.fire({
                                type: 'error',
                                text: response.data.message()
                            })
                        }

                        for(var i in response.data.data){
                            this.newEnterprises.sosial_links.push({
                                name: response.data.data[i],
                                link: ''
                            })
                        }

                    })
            },
        }
    }
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
