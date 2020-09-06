<template>
    <div class="setting-clinic clinic-new new-addition blog">
        <div class="head-line">
            <p><span><i class="fa fa-cog" aria-hidden="true"></i></span>Tənzİmləmələr</p>
            <ol class="breadcrumb">
                <li><a href="index.html">Admin</a></li>
                <li class="active">Tənzimləmələr</li>
            </ol>
        </div>
        <div class="row">
            <div class="col-md-7">
                <div class="addition-info">
                    <div class="block">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="validate-block">
                                    <div :class="previewImageClass"  class="add-img-info">
                                        <div :class="deletePreviewImage">
                                            <div id="preview">
                                                <img v-bind:src="imagePreview" v-show="showPreview"/>
                                            </div>
                                            <button class="delete-uploadimg" @click="deleteImage()"><span><i class="fa fa-trash-o" aria-hidden="true"></i></span></button>
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
                            <div class="col-md-8">
                                <div class="general-info">
                                    <p class="info">İnfo</p>
                                    <div class="validate-block">
                                        <div class="input-group input-up">
                                            <label>
                                                <input type="text" placeholder=" " class="form-control inputText" v-model="PostEnterprises.name">
                                                <span>Obyektin adı*</span>
                                            </label>
                                        </div>
                                        <span class="validation" v-if="errors.name">{{errors.name}}</span>
                                    </div>
                                    <div class="input-group input-up">
                                        <label>
                                            <input type="text" placeholder=" " class="form-control inputText" v-model="PostEnterprises.company_email" :disabled="!!PostEnterprises.company_email">
                                            <span>Obyekt E-maili*</span>
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
                            <div class="col-md-4">
                                <div class="general-info">
                                    <div class="input-group input-up">
                                        <label>
                                            <input type="text" placeholder=" " class="form-control inputText" v-model="PostEnterprises.weekdays">
                                            <span>B.e - Cümə</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="general-info">
                                    <div class="input-group input-up">
                                        <label>
                                            <input type="text" placeholder=" " class="form-control inputText" v-model="PostEnterprises.saturday">
                                            <span>Şənbə</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="general-info">
                                    <div class="input-group input-up">
                                        <label>
                                            <input type="text" placeholder=" " class="form-control inputText" v-model="PostEnterprises.sunday">
                                            <span>Bazar</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="addition-contact">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="block">
                                <p class="info">Obyekt TELEFONU</p>
                                <div v-for="(item, index) in PostEnterprises.phone_numbers.a0">
                                    <div class="general-info phoneList">
                                        <div class="input-group input-up">
                                            <label>
                                                <input type="text" placeholder=" " class="form-control inputText" v-model="PostEnterprises.phone_numbers.a0[index]">
                                                <span>Obyekt telefonu*</span>
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
                                <div v-for="(item, index) in PostEnterprises.phone_numbers.a1">
                                    <div class="general-info mobileList">
                                        <div class="input-group input-up">
                                            <label>
                                                <input type="text" placeholder=" " class="form-control inputText" v-model="PostEnterprises.phone_numbers.a1[index]">
                                                <span>Obyekt Mobil telefonu*</span>
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
                        <div v-for="(item, index) in PostEnterprises.addresses">
                            <div class="general-info workList">
                                <div class="input-group input-up">
                                    <label>
                                        <input type="text" placeholder=" " class="form-control inputText" v-model="PostEnterprises.addresses[index].name">
                                        <span>Obyekt ünvanı*</span>
                                    </label>
                                </div>
                                <button type="button" class="btn btn-addition" @click="addWork()" v-if="index === 0"><span>+</span></button>
                                <button type="button" class="btn btn-cancel" @click="removeWork(index)" v-if="index > 0"><span>-</span></button>
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
                <!--<div class="sign-block">
                    <div class="block">
                        <h4> <span><i class="fa fa-diamond" aria-hidden="true"></i></span> PAKETLƏR</h4>
                        <p>Əgər Siz <a href="">www.e-tibb.az</a> saytında klinikalar kataloqunda olmaq istəyirsinizsə, aşağıdakı seçimlərdən yararlana bilərsiniz: </p>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="tariffs">
                                    <div class="tariff-head">
                                        <p>Sadə</p>
                                        <span>200 <sup>M</sup> </span>
                                    </div>
                                    <div class="tariff-body text-center">
                                        <h1>1</h1>
                                        <p class="tariff-month">Aylıq</p>
                                        <p class="tariff-info">Kataloqda bir aylıq iştirak
                                            üçün sadə paket.</p>
                                        <button class="check-button">
                                            <label>
                                                <span class="check-btn-text">PAKETİ SEÇ</span>
                                                <input type="checkbox" >
                                                <span class="checkmark"></span>
                                            </label>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="tariffs">
                                    <div class="tariff-head">
                                        <p>Gümüş</p>
                                        <span>550 <sup>M</sup> </span>
                                    </div>
                                    <div class="tariff-body text-center">
                                        <h1>3</h1>
                                        <p class="tariff-month">Aylıq</p>
                                        <p class="tariff-info">Kataloqda bir aylıq iştirak
                                            üçün sadə paket.</p>
                                        <button class="check-button">
                                            <label>
                                                <span class="check-btn-text">PAKETİ SEÇ</span>
                                                <input type="checkbox" >
                                                <span class="checkmark"></span>
                                            </label>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="tariffs">
                                    <div class="tariff-head">
                                        <p>Qızıl</p>
                                        <span>1000<sup>M</sup> </span>
                                    </div>
                                    <div class="tariff-body text-center">
                                        <h1>6</h1>
                                        <p class="tariff-month">Aylıq</p>
                                        <p class="tariff-info">Kataloqda bir aylıq iştirak
                                            üçün sadə paket.</p>
                                        <button class="check-button">
                                            <label>
                                                <span class="check-btn-text">PAKETİ SEÇ</span>
                                                <input type="checkbox" >
                                                <span class="checkmark"></span>
                                            </label>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>-->
            </div>
            <div class="col-md-5">
                <div class="bio-diplom">
                    <div class="block">
                        <div class="biography">
                            <p class="info">Şİrkət haqqında</p>
                            <div class="input-group">
                                <textarea name="" id="" cols="30" rows="5" class="form-control" v-model="PostEnterprises.about"></textarea>
                            </div>
                        </div>
                        <div class="certificate">
                            <p class="info">sertİFİKATLAR</p>
                            <div class="certificate-list">
                                <div class="certificate-images">
                                    <div v-for="(file, key) in EnterprisesData.certificate" class="diploma-images">
                                        <img class="preview" :src="file.file_photo_thumb"  alt="certificate">
                                        <button class="delete-uploadimg" @click="deleteImage_ct(key)" ><span><i class="fa fa-trash-o" aria-hidden="true"></i></span></button>
                                    </div>
                                    <div v-for="(file, key) in PostEnterprises.ct_files" class="diploma-images">
                                        <img class="preview"  v-bind:ref="'img'+parseInt( key )"  alt="certificate">
                                        <button class="delete-uploadimg" @click="deleteImage_ctTmp(key)"><span><i class="fa fa-trash-o" aria-hidden="true"></i></span></button>
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
                <div class="addition-user">
                    <div class="block">
                        <p class="info">Əlaqələndİrİcİ şəxs</p>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="general-info">
                                    <div class="validate-block">
                                        <div class="input-group input-up">
                                            <label>
                                                <input type="text" placeholder=" " class="form-control inputText" v-model="PostEnterprises.contact_name">
                                                <span>Əlaqələndirici şəxs*</span>
                                            </label>
                                        </div>
                                        <span class="validation" v-if="errors.contact_name">{{errors.contact_name}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="general-info">
                                    <div class="validate-block">
                                        <div class="input-group input-up">
                                            <label>
                                                <input type="text" placeholder=" " class="form-control inputText" v-model="PostEnterprises.email">
                                                <span>Əlaqələndirici şəxsin E-maili*</span>
                                            </label>
                                        </div>
                                        <span class="validation" v-if="errors.email">{{errors.email}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="general-info">
                                    <div class="validate-block">
                                        <div class="input-group input-up">
                                            <label>
                                                <input type="text" placeholder=" " class="form-control inputText" v-model="PostEnterprises.contact_phone">
                                                <span>Əlaqələndirici şəxsin telefonu</span>
                                            </label>
                                        </div>
                                        <span class="validation" v-if="errors.contact_phone">{{errors.contact_phone}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="general-info">
                                    <div class="validate-block">
                                        <div class="input-group input-up">
                                            <div class="textup">
                                                <datepicker  placeholder="Əlaqələndirici şəxsin doğum günü*" v-model="PostEnterprises.contact_birthday"></datepicker>
                                            </div>
                                        </div>
                                        <span class="validation" v-if="errors.contact_birthday">{{errors.contact_birthday}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="social-block edit-social">
                    <div class="block">
                        <p class="info">Sosİal şəbəkələr</p>
                        <div class="general-info">
                            <ul  class="list-unstyled">
                                <li v-if="PostEnterprises.sosial_links.length > 0" v-for="(social,index) in sosial_links">
                                    <div class="input-group input-up">
                                        <label>
                                            <input type="text" placeholder=" " v-model="PostEnterprises.sosial_links[index].link" class="form-control inputText" >
                                            <span :class=social.name>{{social.name}}</span>
                                        </label>
                                    </div>
                                </li>
                                <li>
                                    <div class="input-group input-up">
                                        <label>
                                            <input type="text" placeholder=" " v-model="PostEnterprises.phone_numbers.a2[0]" class="form-control inputText" >
                                            <span class="Whatsapp">Whatsapp</span>
                                        </label>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!--<div class="sign-block">
                    <div class="block">
                        <h4> <span><i class="fa fa-trophy" aria-hidden="true"></i></span> VİP xidmətlər</h4>
                        <p>Əgər Siz <a href="">www.e-tibb.az</a> saytında klinikalar kataloqunda olmaq istəyirsinizsə, aşağıdakı seçimlərdən yararlana bilərsiniz: </p>
                        <ul class="tariffs-list list-unstyled">
                            <li>
                                <label class="check-button">
                                    <span class="check-btn-text">Premium iştirak</span>
                                    <input type="checkbox" >
                                    <span class="checkmark"></span>
                                </label>
                                <select class="form-control selectpicker" name="money" title="Paket &nbsp; &nbsp; seçin">
                                    <option value="">1 aylıq - 50 M</option>
                                    <option value="">3 aylıq - 550 M</option>
                                    <option value="">6 aylıq - 1000 M</option>
                                </select>
                            </li>
                            <li>
                                <label class="check-button">
                                    <span class="check-btn-text">Facebook-da ayda <b>2 </b> post</span>
                                    <input type="checkbox" >
                                    <span class="checkmark"></span>
                                </label>
                                <select class="form-control selectpicker" name="money" title="Paket &nbsp; &nbsp; seçin">
                                    <option value="">1 aylıq - 50 M</option>
                                    <option value="">3 aylıq - 550 M</option>
                                    <option value="">6 aylıq - 1000 M</option>
                                </select>
                            </li>
                            <li>
                                <label class="check-button">
                                    <span class="check-btn-text">İnstagram-da ayda <b>2</b> post</span>
                                    <input type="checkbox" >
                                    <span class="checkmark"></span>
                                </label>
                                <select class="form-control selectpicker" name="money" title="Paket &nbsp; &nbsp; seçin">
                                    <option value="">1 aylıq - 50 M</option>
                                    <option value="">3 aylıq - 550 M</option>
                                    <option value="">6 aylıq - 1000 M</option>
                                </select>
                            </li>
                            <li>
                                <label class="check-button">
                                    <span class="check-btn-text">Youtube-da <b>10</b> dəqiqəlik videoreportaj</span>
                                    <input type="checkbox" >
                                    <span class="checkmark"></span>
                                </label>
                                <select class="form-control selectpicker" name="money" title="Paket &nbsp; &nbsp; seçin">
                                    <option value="">1 aylıq - 50 M</option>
                                    <option value="">3 aylıq - 550 M</option>
                                    <option value="">6 aylıq - 1000 M</option>
                                </select>
                            </li>
                        </ul>
                    </div>
                </div>-->
            </div>
            <div class="col-md-12">
                <div class="sign-block addition-alternatives">
                    <div class="block">
                        <div class="check-list">
                            <label class="check-button">
                                <span>Evə çağırış</span>
                                <input type="checkbox" v-model="PostEnterprises.eve_caqiris">
                                <span class="checkmark"></span>
                            </label>
                            <label class="check-button">
                                <span>Uşaq həkimi</span>
                                <input type="checkbox" v-model="PostEnterprises.catdirilma">
                                <span class="checkmark"></span>
                            </label>
                            <label class="check-button">
                                <span>24 Saat</span>
                                <input type="checkbox" v-model="PostEnterprises.saat24">
                                <span class="checkmark"></span>
                            </label>
                        </div>
                        <div class="tariffs-sum">
                            <!--<p>Cəmi: <span>250 <sup>M</sup>  </span></p>-->
                            <button type="button" class="btn" @click="submitFile()">ƏLAVƏ ET</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {axios} from "../config/axios";
    import Multiselect from 'vue-multiselect';
    import Datepicker from 'vuejs-datepicker';
    export default {
        name: "settingEnterprises",
        components:{
            Multiselect,
            Datepicker
        },
        data:function () {
            return {
                id: this.$route.params.id,
                EnterprisesData:{},
                PostEnterprises:{
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
                    phone_numbers: {
                        a0: [],
                        a1: [],
                        a2: []
                    },
                    addresses:[{name:''}],
                    eve_caqiris:'',
                    catdirilma:'',
                    saat24:'',
                    about:'',
                    ct_files:[],
                    sosial_links:[],
                    deletedCertificates: [],
                    deletedImages: 0,
                },
                showPreview: true,
                imagePreview: '',
                url: null,
                previewImageClass: '',
                deletePreviewImage: '',
                sosial_links: [],
                errors:{
                    contact_birthday:false,
                    contact_name:false,
                    contact_phone:false,
                    email:false,
                    files:false,
                    name:false,
                }
            }
        },
        mounted() {
            this.getEnterpriseData();
            this.getSocials()
        },
        methods: {
            submitFile() {
                this.resetErrors();
                let formdata= new FormData();
                let postData = this.PostEnterprises;
                let date = new Date(this.PostEnterprises.contact_birthday);
                postData.contact_birthday = date.getFullYear() + "-" + ('0' + (date.getMonth() +1) ).slice(-1,2)+ "-" + date.getDay();
                postData.eve_caqiris = this.PostEnterprises.eve_caqiris ? 1 : 0;
                postData.catdirilma = this.PostEnterprises.catdirilma ? 1 : 0;
                postData.saat24 = this.PostEnterprises.saat24 ? 1 : 0;
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
                            let index = 0;
                            for(let j of postData[i].a0){
                                formdata.append('phone_numbers['+index+'][type]', '0')
                                formdata.append('phone_numbers['+index+'][number]', j)
                                index++;
                            }
                            for(let j of postData[i].a1){
                                formdata.append('phone_numbers['+index+'][type]', '1')
                                formdata.append('phone_numbers['+index+'][number]', j)
                                index++;
                            }
                            for(let j of postData[i].a2){
                                formdata.append('phone_numbers['+index+'][type]', '2')
                                formdata.append('phone_numbers['+index+'][number]', j)
                                index++;
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

                formdata.append('id', this.id)
                axios.post('/enterprises/edit', formdata).then (
                    res =>{
                        if(res.data.status != 200){
                            this.errors = res.data.data
                        }
                    }).catch(error => {
                    this.errors = error.response.data.data
                });
            },

            resetErrors: function(){
                this.errors = {
                    contact_birthday:false,
                    contact_name:false,
                    contact_phone:false,
                    email:false,
                    files:false,
                    name:false
                }
            },

            getEnterpriseData:function () {
                axios.get('/enterprises/info/'+this.id).then(
                    response=> {
                        this.EnterprisesData=response.data.data;
                        this.imagePreview = response.data.data.enterprise.thumb;
                        this.PostEnterprises.name = this.EnterprisesData.enterprise.name;
                        for (let i in this.EnterprisesData.company_emails) {
                            this.PostEnterprises.company_email[i]=this.EnterprisesData.company_emails[i].data
                        }
                        this.PostEnterprises.weekdays = this.EnterprisesData.enterprise.weekdays;
                        this.PostEnterprises.saturday = this.EnterprisesData.enterprise.saturday;
                        this.PostEnterprises.sunday = this.EnterprisesData.enterprise.sunday;
                        this.PostEnterprises.addresses = this.EnterprisesData.addresses;
                        if(this.PostEnterprises.addresses.length == 0) this.PostEnterprises.addresses.push({name: ''});
                        let socials = this.EnterprisesData.social_links;
                        for(let i in this.sosial_links){
                            if(typeof socials[i] == 'undefined'){
                                let obj = {};
                                obj.id = 0;
                                obj.link = " ";
                                obj.link_type = i.toString();
                                socials[i] = obj;
                            }
                        }
                        this.PostEnterprises.sosial_links = socials;
                        this.PostEnterprises.eve_caqiris=this.EnterprisesData.enterprise.eve_caqiris;
                        this.PostEnterprises.catdirilma=this.EnterprisesData.enterprise.catdirilma;
                        this.PostEnterprises.saat24=this.EnterprisesData.enterprise.saat24;
                        this.PostEnterprises.about=this.EnterprisesData.enterprise.about;
                        this.PostEnterprises.contact_name=this.EnterprisesData.enterprise.contact_name;
                        this.PostEnterprises.email=this.EnterprisesData.enterprise.email;
                        this.PostEnterprises.contact_phone=this.EnterprisesData.enterprise.contact_name;
                        this.PostEnterprises.contact_birthday=this.EnterprisesData.enterprise.contact_birthday;
                        for(let i of this.EnterprisesData.phones){
                            this.PostEnterprises.phone_numbers["a"+i.number_type].push(i.number)
                        }

                        for(let i in [0, 1, 2]){
                            if(this.PostEnterprises.phone_numbers["a"+i].length == 0)
                                this.PostEnterprises.phone_numbers["a"+i].push("")
                        }
                    }
                )
            },
            deleteImage:function () {
                this.deletePreviewImage = 'deletebg';
                this.imagePreview='';
                this.PostEnterprises.deletedImages = 1;
            },
            handleFileUpload() {
                this.PostEnterprises.files = this.$refs.file.files[0];
                let reader = new FileReader();
                this.previewImageClass = 'changebg';
                reader.addEventListener("load", function () {
                    this.showPreview = true;
                    this.imagePreview = reader.result;
                }.bind(this), false);
                if (this.PostEnterprises.files) {
                    if (/\.(jpe?g|png|gif)$/i.test(this.PostEnterprises.files.name)) {
                        reader.readAsDataURL(this.PostEnterprises.files);
                    }
                }
            },
            addPhone() {
                this.PostEnterprises.phone_numbers.a0.push("")
            },
            removePhone(index){
                this.PostEnterprises.phone_numbers.a0.splice(index,1)
            },
            addMobile() {
                this.PostEnterprises.phone_numbers.a1.push("")
            },
            removeMobile(index){
                this.PostEnterprises.phone_numbers.a1.splice(index,1)
            },
            addWork() {
                const workList = {
                    name:''
                };
                this.PostEnterprises.addresses.push(workList)
            },
            removeWork(index){
                this.PostEnterprises.addresses.splice(index,1)
            },
            getSocials: function () {
                axios.get('/doctors/list-sosial-link').then(
                    response=> {
                        if (response.data.status !== 200) {
                            return Swal.fire({
                                type: 'error',
                                text: response.data.message()
                            })
                        }
                        for(let i in response.data.data){
                            this.sosial_links.push({
                                name: response.data.data[i]
                            })

                        }

                    })
            },

            deleteImage_ct:function (key) {
                this.PostEnterprises.deletedCertificates.push(this.EnterprisesData.certificate[key].id);
                this.EnterprisesData.certificate.splice(key,1);
            },

            deleteImage_ctTmp:function (key){
                this.PostEnterprises.ct_files.splice(key,1);
            },
            handleFilesUpload_ct(){
                let ct_uploadedFiles = this.$refs.ct_files.files;
                for( var j = 0; j < ct_uploadedFiles.length; j++ ){
                    this.PostEnterprises.ct_files.push( ct_uploadedFiles[j] );
                }
                this.getImagePreviews_ct();
            },
            getImagePreviews_ct(){
                for( let j = 0; j < this.PostEnterprises.ct_files.length; j++ ){
                    if ( /\.(jpe?g|png|gif)$/i.test( this.PostEnterprises.ct_files[j].name ) ) {
                        let reader = new FileReader();
                        reader.addEventListener("load", function(){
                            this.$refs['img'+parseInt( j )][0].src = reader.result;
                        }.bind(this), false);
                        reader.readAsDataURL( this.PostEnterprises.ct_files[j] );
                    }
                }
            },
        }
    }
</script>

<style scoped>

</style>