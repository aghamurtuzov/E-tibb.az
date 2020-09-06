<template>
    <div class="new-addition blog">
        <breadcrumb name="Yenİ HƏKİM" icon="fa-user-md" :routes="[{'name':'Yeni həkim'}]"></breadcrumb>
        <div class="row">
            <div class="col-md-7">
                <div class="addition-info">
                        <div class="block">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="validate-block">
                                            <div :class="previewImageClass" class="add-img-info">
                                                <div id="preview">
                                                    <!--<img v-if="url" :src="url" />-->
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
                                    <div class="col-md-8">
                                        <div class="general-info">
                                            <p class="info">İnfo</p>
                                            <div class="validate-block">
                                                <div class="input-group input-up">
                                                    <label>
                                                        <input type="text" placeholder=" " class="form-control inputText" v-model="newDoctor.name">
                                                        <span>Ad, Soyad*</span>
                                                    </label>
                                                </div>
                                                <span class="validation" v-if="errors.name">{{errors.name}}</span>
                                            </div>
                                            <div class="input-group input-up">
                                                <div class="textup">
                                                    <datepicker  placeholder="Doğum günü*" v-model="newDoctor.birthday"></datepicker>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="addition-data">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="general-info">
                                                <div class="validate-block">
                                                    <div class="input-group input-up">
                                                        <label>
                                                            <input type="text" placeholder=" " class="form-control inputText" v-model="newDoctor.experience1">
                                                            <span>İş təcrübəsi*</span>
                                                        </label>
                                                    </div>
                                                    <span class="validation" v-if="errors.experience1">{{errors.experience1}}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="general-info">
                                                <div class="validate-block">
                                                    <div class="input-group">
                                                        <multiselect v-model="newDoctor.doctorSpecialist" label="name" :options="doctorSpecialists" track-by="id" placeholder="İxtisas*" :multiple="true" :taggable="true" @tag="addTag"></multiselect>
                                                    </div>
                                                    <span class="validation" v-if="errors.specialists">{{errors.specialists}}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="general-info">
                                                <div class="input-group">
                                                    <multiselect v-model="newDoctor.doctorDegree" label="name" selectedLabel="" deselectGroupLabel="" deselectLabel="" select-label="" :reset-after="false" :allow-empty="false" :internal-search="false" :close-on-select="true"  :clear-on-select="false" :hide-selected="false" :options="doctorDegrees"  track-by="id" ></multiselect>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="general-info">
                                                <div class="validate-block">
                                                    <div class="input-group input-up">
                                                        <label>
                                                            <input type="text" placeholder=" " class="form-control inputText" v-model="newDoctor.email">
                                                            <span>E-mail*</span>
                                                        </label>
                                                    </div>
                                                    <span class="validation" v-if="errors.email">{{errors.email}}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="general-info">
                                                <div class="input-group input-up">
                                                    <label>
                                                        <input type="text" placeholder=" " class="form-control inputText" v-model="newDoctor.phone_numbers[0].number">
                                                        <span>İş telefonu*</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="general-info">
                                                <div class="input-group input-up">
                                                    <label>
                                                        <input type="text" placeholder=" " class="form-control inputText" v-model="newDoctor.phone_numbers[1].number">
                                                        <span>Mobil telefonu*</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="general-info">
                                                <div class="input-group input-up">
                                                    <multiselect v-model="newDoctor.gender" label="name" :reset-after="false" :allow-empty="false" :internal-search="false" :close-on-select="true"  :clear-on-select="false" :hide-selected="false" :options="Genders"  track-by="id"></multiselect>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                <div class="addition-workplace">
                    <div class="block">
                        <p class="info">İş yerlərİ</p>
                        <div v-for="(item, index) in newDoctor.workplaces_list">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="general-info">
                                        <div class="input-group input-up">
                                            <label>
                                                <input type="text" placeholder=" " v-model="newDoctor.workplaces_list[index].name" class="form-control inputText">
                                                <span>İş yeri adı*</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="general-info">
                                        <div class="input-group input-up">
                                            <label>
                                                <input type="text" placeholder=" " v-model="newDoctor.workplaces_list[index].address" class="form-control inputText" >
                                                <span>İş yeri ünvanı*</span>
                                            </label>
                                        </div>
                                        <button type="button" class="btn btn-addition" @click="addWork()" v-if="index === 0"><span>+</span></button>
                                        <button type="button" class="btn btn-cancel" @click="removeWork(index)" v-if="index > 0"><span>-</span></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="addition-alternatives">
                    <div class="block">
                        <div class="check-list">
                            <label class="check-button">
                                <span>Evə çağırış</span>
                                <input v-model="newDoctor.home_doctor" type="checkbox" >
                                <span class="checkmark"></span>
                            </label>
                            <label class="check-button">
                                <span>Uşaq həkimi</span>
                                <input v-model="newDoctor.child_doctor" type="checkbox" >
                                <span class="checkmark"></span>
                            </label>
                        </div>
                        <button type="button" @click="submitFile()" class="btn">ƏLAVƏ ET</button>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="bio-diplom">
                    <div class="block">
                        <div class="biography">
                            <p class="info">Bİoqrafİya</p>
                            <div class="input-group">
                                <textarea name="" id="" cols="30" rows="5" class="form-control" v-model="newDoctor.about"></textarea>
                            </div>
                        </div>
                        <div class="certificate">
                            <div class="validate-block">
                                <p class="info">DİpLOM</p>
                                <div class="certificate-list">
                                    <div class="certificate-images">
                                        <img class="preview" v-for="(file, key) in newDoctor.dp_files" v-bind:ref="'image'+parseInt( key )" alt="certificate">
                                    </div>
                                    <div class="file-upload-wrapper" data-text="">
                                        <label class="custom-file-upload">
                                            <span><i class="fa fa-plus" aria-hidden="true"></i></span>
                                        </label>
                                        <input type="file" id="dp_files" ref="dp_files" accept="image/*" multiple v-on:change="handleFilesUpload_dp()"  name="dp_files[]" class="file-upload-field">
                                    </div>
                                </div>
                                <span class="validation" v-if="errors.dp_files">{{errors.dp_files}}</span>
                            </div>
                        </div>
                        <div class="certificate diploma">
                            <p class="info">sertİFİKATLAR</p>
                            <div class="certificate-list">
                                <div class="certificate-images">
                                    <img class="preview" v-for="(file, key) in newDoctor.ct_files" v-bind:ref="'img'+parseInt( key )" alt="certificate">
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
                                <li v-for="(social,index) in newDoctor.sosial_links">
                                    <div class="input-group input-up">
                                        <label>
                                            <input type="text" placeholder=" " v-model="newDoctor.sosial_links[index].link" class="form-control inputText" >
                                            <span :class=social.name>{{social.name}}</span>
                                        </label>
                                    </div>
                                </li>
                                <li>
                                    <div class="input-group input-up">
                                        <label>
                                            <input type="text" placeholder=" " v-model="newDoctor.phone_numbers[2].number" class="form-control inputText" >
                                            <span class="Whatsapp">Whatsapp</span>
                                        </label>
                                    </div>
                                </li>
                            </ul>
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
    import Swal from 'sweetalert2';
    export default {
        name: "newDoctor",
        components: {
            Datepicker,
            Multiselect
        },
        data: function(){
            return {
                newDoctor: {
                    name: '',
                    birthday:'',
                    phone_numbers: [
                        {type:0,number:''},
                        {type:1,number:''},
                        {type:2,number:''}
                    ],
                    email:'',
                    experience1:'',
                    workplaces_list: [
                        {name:'',address: ''}
                    ],
                    sosial_links:[],
                    files: '',
                    dp_files: [],
                    ct_files:[],
                    doctorDegree: {id: 0, name:'Elmi dərəcə*'},
                    doctorSpecialist: [],
                    gender: {id: 0, name: 'Cinsiyyəti*'},
                    home_doctor:'',
                    child_doctor: '',
                    about:''
                },
                showPreview: false,
                imagePreview: '',
                doctorSpecialists: [],
                doctorDegrees: [],
                Genders: [],
                url: null,
                previewImageClass: '',
                errors:{
                    name:false,
                    experience1:false,
                    dp_files:false,
                    email:false,
                    files:false,
                    specialists:false
                }
                /*socials: [],*/
               /* workplaces_list:[{name:'',address: ''}]*/,
            }
        },
        mounted: function(){
            this.getSpecialist();
            this.getDegree();
            this.getGender();
            this.getSocials();
        },
        methods:{
            submitFile() {
                this.resetErrors();
                let formdata= new FormData();
                let postData = this.newDoctor;

                let date = new Date(this.newDoctor.birthday);
                postData.birthday = date.getFullYear() + "-" + ('0' + (date.getMonth() +1) ).slice(-1,2)+ "-" + date.getDay();
                postData.gender = this.newDoctor.gender.id || 0;
                postData.doctorDegree = this.newDoctor.doctorDegree.id || 0;
                postData.home_doctor = this.newDoctor.home_doctor ? 1 : 0;
                postData.child_doctor = this.newDoctor.child_doctor ? 1 : 0;
                postData.specialists = [];
                for(let i of this.newDoctor.doctorSpecialist){
                    postData.specialists.push(i.id)
                }
                for(let i in postData){
                    switch (i) {
                        case 'workplaces_list' :
                            for(let j in postData[i]){
                                formdata.append(`${i}[`+j+`][name]`, postData[i][j].name);
                                formdata.append(`${i}[`+j+`][address]`, postData[i][j].address);
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
                axios.post('/doctors/create', formdata).then (
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
                    name:false,
                    experience1:false,
                    dp_files:false,
                    email:false,
                    files:false,
                    specialists:false
                }
            },
            getSpecialist: function () {
                axios.get('/doctors/specialists',this.doctorSpecialist).then(
                    response=> {
                        if (response.data.status !== 200) {
                            return Swal.fire({
                                type: 'error',
                                text: response.data.message()
                            })
                        }
                        for(let i in response.data.data){
                            this.doctorSpecialists.push({
                                id: i,
                                name: response.data.data[i].name
                            })
                        }
                    })
            },
            getDegree: function () {
                axios.get('/doctors/list-academic-degree',this.doctorDegree).then(
                    response=> {
                        if (response.data.status !== 200) {
                            return Swal.fire({
                                type: 'error',
                                text: response.data.message()
                            })
                        }
                        for(let i in response.data.data){
                            this.doctorDegrees.push({
                                id: i,
                                name: response.data.data[i]
                            })
                        }
                    })
            },
            getGender: function () {
                axios.get('/doctors/list-gender',this.gender).then(
                    response=> {
                        if (response.data.status !== 200) {
                            return Swal.fire({
                                type: 'error',
                                text: response.data.message()
                            })
                        }
                        for(let i in response.data.data){
                            this.Genders.push({
                                id: i,
                                name: response.data.data[i]
                            })
                        }

                    })
            },
            getSocials: function () {
                axios.post('/doctors/list-sosial-link').then(
                    response=> {
                        if (response.data.status !== 200) {
                            return Swal.fire({
                                type: 'error',
                                text: response.data.message()
                            })
                        }

                        for(var i in response.data.data){
                            this.newDoctor.sosial_links.push({
                                name: response.data.data[i],
                                link: ''
                            })
                        }

                    })
            },
            addTag (newTag) {
                const tag = {
                    name: newTag,
                    code: newTag.substring(0, 2) + Math.floor((Math.random() * 10000000))
                };
                this.options.push(tag);
                this.value.push(tag)
            },
            addWork() {
                const row = {
                    name:'',
                    address:''
                };
                this.newDoctor.workplaces_list.push(row)
            },
            removeWork(index){
                this.newDoctor.workplaces_list.splice(index,1)
            },
            handleFilesUpload_dp(){
                let dp_uploadedFiles = this.$refs.dp_files.files;
                for( var i = 0; i < dp_uploadedFiles.length; i++ ){
                    this.newDoctor.dp_files.push( dp_uploadedFiles[i] );
                }
                this.getImagePreviews_dp();
            },
            getImagePreviews_dp(){
                for( let i = 0; i < this.newDoctor.dp_files.length; i++ ){
                    if ( /\.(jpeg|png|jpg)$/i.test( this.newDoctor.dp_files[i].name ) ) {
                        let reader = new FileReader();
                        reader.addEventListener("load", function(){
                            this.$refs['image'+parseInt( i )][0].src = reader.result;
                        }.bind(this), false);
                        reader.readAsDataURL( this.newDoctor.dp_files[i] );
                    }
                }
            },
            handleFilesUpload_ct(){
                let ct_uploadedFiles = this.$refs.ct_files.files;
                for( var j = 0; j < ct_uploadedFiles.length; j++ ){
                    this.newDoctor.ct_files.push( ct_uploadedFiles[j] );
                }
                this.getImagePreviews_ct();
            },
            getImagePreviews_ct(){
                for( let j = 0; j < this.newDoctor.ct_files.length; j++ ){
                    if ( /\.(jpeg|png|jpg)$/i.test( this.newDoctor.ct_files[j].name ) ) {
                        let reader = new FileReader();
                        reader.addEventListener("load", function(){
                            this.$refs['img'+parseInt( j )][0].src = reader.result;
                        }.bind(this), false);
                        reader.readAsDataURL( this.newDoctor.ct_files[j] );
                    }
                }
            },

            handleFileUpload() {
                this.newDoctor.files = this.$refs.file.files[0];
                let reader = new FileReader();
                this.previewImageClass = 'changebg';
                reader.addEventListener("load", function () {
                    this.showPreview = true;
                    this.imagePreview = reader.result;
                }.bind(this), false);
                if (this.newDoctor.files) {
                    if (/\.(jpeg|png|jpg)$/i.test(this.newDoctor.files.name)) {
                        reader.readAsDataURL(this.newDoctor.files);
                    }
                }
            }


        }
    }
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
