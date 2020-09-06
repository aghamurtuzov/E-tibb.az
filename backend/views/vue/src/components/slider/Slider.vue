<template>
    <div class="slider-block blog">
        <breadcrumb name="Slİder" icon="fa-certificate" :routes="[{'name':'Slider'}]"></breadcrumb>
        <div class="row">
           <!-- <div class="col-md-12">
                <div class="search">
                    <div class="block">
                        <select class="form-control selectpicker" name="money" title="Axtarış növünü seçin">
                            <option value="">Qeydiyyat nömrəsinə görə</option>
                            <option value="">Əraziyə görə</option>
                            <option value="">Kartla ödəniş</option>
                            <option value="">Nəğd ödəniş</option>
                            <option value="">Premium</option>
                        </select>
                        <div class="search-blog">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Axtarış sözü"
                                       aria-describedby="basic-addon1">
                            </div>
                            <button type="submit" class="btn-effect">
                                <span><i class="fa fa-search" aria-hidden="true"></i></span>AXTAR
                            </button>
                        </div>
                    </div>
                </div>
            </div>-->
            <div class="col-md-12">
                <div class="information-blog">
                  <div class="notification-bar mtx-0">
                    Cədvəl tam görünmürsə sağa doğru sürüşdürün və ya cihazınızın "ekran fırlanması" özəlliyini aktivləşdirin
                  </div>
                  <div class="block mtx-0">
                    <div class="table__Container">
                        <table class="table table-hover table-striped">
                            <thead>
                            <tr>
                                <th>
                                    <label class="check-button">
                                        <input type="checkbox" v-model="selectAll" @change="selectAllPromotions()">
                                        <span class="checkmark"></span>
                                    </label>
                                </th>
                                <th>Slide şəkli</th>
                                <th>Slide adı</th>
                                <th>Slide url</th>
                                <th>Slide status</th>
                                <th>
                                    <button  class="btn-add"  @click="creatNewSlider()">
                                        <span><i class="fa fa-plus" aria-hidden="true"></i></span>YENİ
                                    </button>
                                </th>
                            </tr>
                            </thead>
                            <tbody v-if="slider_lists.length>0">
                            <tr v-for="item in slider_lists">
                                <td>
                                    <label class="check-button">
                                        <input type="checkbox" :checked="selectAll" v-model="item.checked">
                                        <span class="checkmark"></span>
                                    </label>
                                </td>
                                <td>
                                    <img :src="item.photo" alt="list-item1" class="img-square">
                                </td>
                                <td>{{item.name}}</td>
                                <td><p class="slider-url">{{item.url}}</p></td>
                                <td><p :class="getClassByStatus(item.status)">{{item.status_name}}</p></td>
                                <td>
                                    <button  class="btn btn-view"  @click="editNewSlider(item)">
                                        <span><i class="fa fa-pencil" aria-hidden="true"></i></span>Düzəliş et
                                    </button>
                                </td>
                            </tr>
                            </tbody>
                            <tbody v-else class="thead-bottom" >
                            <tr>
                                <td>Məlumat yoxdur</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <pagination :paginations="paginations" :callback="getSliders"></pagination>
                  </div>
                </div>
                <div class="stocks">
                    <div class="clinic-tariffs-edit" v-if="slider_lists.length > 0">
                        <div class="block">
                            <button type="button" class="btn" @click="deleteSlide()">
                                Sİl
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-block"  v-if="fillSlider">
            <div class="modal fade in" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="fillSlider=false">
                            <span aria-hidden="true">
                                <img src="/admin/cp/img/popup-close.png" alt="popup-close">
                            </span>
                            </button>
                            <h4 class="modal-title">Yenİ slİde</h4>
                        </div>
                        <div class="modal-body new-addition">
                            <div class="general-info">
                                <div class="validate-block">
                                    <div class="input-group input-up">
                                        <label>
                                            <input type="text" placeholder=" " class="form-control inputText" v-model="newSlider.name">
                                            <span>Slide adı</span>
                                        </label>
                                    </div>
                                    <span class="validation" v-if="errors.name">{{errors.name}}</span>
                                </div>
                            </div>
                            <div class="general-info file-part">
                                <div class="validate-block">
                                    <div class="input-group input-up relative">
                                        <label>
                                            <input type="file" id="file" ref="file" name="file" v-on:change="handleFileUpload()" >
                                            <div v-if="filename.length > 0">
                                                <span class="change-description">Slide şəkli</span>
                                                <p>{{filename}}</p>
                                            </div>
                                            <span v-else>Slide şəkli</span>
                                        </label>
                                    </div>
                                    <span class="validation" v-if="errors.photo">{{errors.photo}}</span>
                                    <!--<div class="input-group input-up">
                                    <label>File
                                        <input type="file" id="file" ref="file" v-on:change="handleFileUpload()"/>
                                        <span>Slide şəkli</span>
                                    </label>
                                    </div>-->
                                </div>
                            </div>
                            <div class="general-info">
                                <div class="input-group input-up">
                                    <label>
                                        <input type="text" placeholder=" " class="form-control inputText"  v-model="newSlider.text1">
                                        <span>Mətn 1</span>
                                    </label>
                                </div>
                            </div>
                            <div class="general-info">
                                <div class="input-group input-up">
                                    <label>
                                        <input type="text" placeholder=" " class="form-control inputText" v-model="newSlider.text2">
                                        <span>Mətn 2</span>
                                    </label>
                                </div>
                            </div>
                            <div class="general-info">
                                <div class="input-group input-up">
                                    <label>
                                        <input type="text" placeholder=" " class="form-control inputText" v-model="newSlider.text3">
                                        <span>Mətn 3</span>
                                    </label>
                                </div>
                            </div>
                            <div class="general-info">
                                <div class="input-group input-up">
                                    <label>
                                        <input type="text" placeholder=" " class="form-control inputText" v-model="newSlider.url">
                                        <span>Slider url</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="switch-part ">
                                <span>Status</span>
                                <label class="switch">
                                    <input type="checkbox" v-model="newSlider.status_n">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                            <button class="btn-add"  @click="submitFile()">
                                <span><i class="fa fa-floppy-o" aria-hidden="true"></i></span>Yadda saxla
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-backdrop fade in"></div>
        </div>
        <div class="modal-block" v-if="editSlider">
            <div class="modal fade in" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="editSlider=false">
                            <span aria-hidden="true">
                                <img src="/admin/cp/img/popup-close.png" alt="popup-close">
                            </span>
                            </button>
                            <h4 class="modal-title">Düzəlİş et</h4>
                        </div>
                        <div class="modal-body new-addition">
                            <div class="general-info">
                                <div class="validate-block">
                                    <div class="input-group input-up">
                                        <label>
                                            <input type="text" placeholder=" " class="form-control inputText" v-model="PostData.name">
                                            <span>Slide adı</span>
                                        </label>
                                    </div>
                                    <span class="validation" v-if="errors.name">{{errors.name}}</span>
                                </div>
                            </div>
                            <div class="general-info file-part">
                                <div class="validate-block">
                                    <div class="slider-image" v-if="showImage">
                                        <img :src="PostData.photo" alt="list-item1" class="img-responsive">
                                        <button class="delete-uploadimg" @click="deleteSlideImg()">
                                            <span><i aria-hidden="true" class="fa fa-trash-o"></i></span>
                                        </button>
                                    </div>
                                    <div class="input-group input-up relative" v-else>
                                        <label>
                                            <input type="file" id="file1" name="file1" ref="file" v-on:change="handleFileUpload()" >
                                            <div v-if="filename.length > 0">
                                                <span class="change-description">Slide şəkli</span>
                                                <p>{{filename}}</p>
                                            </div>
                                            <span v-else>Slide şəkli</span>
                                        </label>
                                    </div>
                                    <span class="validation" v-if="errors.photo">{{errors.photo}}</span>
                                </div>
                            </div>
                            <div class="general-info">
                                <div class="input-group input-up">
                                    <label>
                                        <input type="text" placeholder=" " class="form-control inputText"  v-model="PostData.text1">
                                        <span>Mətn 1</span>
                                    </label>
                                </div>
                            </div>
                            <div class="general-info">
                                <div class="input-group input-up">
                                    <label>
                                        <input type="text" placeholder=" " class="form-control inputText" v-model="PostData.text2">
                                        <span>Mətn 2</span>
                                    </label>
                                </div>
                            </div>
                            <div class="general-info">
                                <div class="input-group input-up">
                                    <label>
                                        <input type="text" placeholder=" " class="form-control inputText" v-model="PostData.text3">
                                        <span>Mətn 3</span>
                                    </label>
                                </div>
                            </div>
                            <div class="general-info">
                                <div class="input-group input-up">
                                    <label>
                                        <input type="text" placeholder=" " class="form-control inputText" v-model="PostData.url">
                                        <span>Slider url</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="switch-part ">
                                <span>Status</span>
                                <label class="switch">
                                    <input type="checkbox" v-model="PostData.status_n">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                            <button class="btn-add"  @click="editFile()">
                                <span><i class="fa fa-floppy-o" aria-hidden="true"></i></span>Yadda saxla
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-backdrop fade in"></div>
        </div>
    </div>
</template>

<script>
    import {axios} from '../config/axios';
    export default {
        name: "Slider",
        data: function () {
            return {
                newSlider:{
                    name:'',
                    photo:'',
                    text1:'',
                    text2:'',
                    text3:'',
                    url:'',
                    status_n:''
                },
                PostData: {
                    name:'',
                    photo:'',
                    text1:'',
                    text2:'',
                    text3:'',
                    url:'',
                    status_n:'',
                    deletedImages:0
                },
                errors: {
                    name:false,
                    photo:false
                },
                slider_lists: [],
                paginations: {},
                fillSlider:false,
                editSlider:false,
                status_name: '',
                status: '',
                selectAll:false,
                file: '',
                filename: '',
                showImage:true,
            }
        },
        mounted: function () {
            this.getSliders();
        },
        methods: {
            submitFile() {
                this.resetErrors();
                let formdata = new FormData();
                formdata.append('name',this.newSlider.name);
                formdata.append('photo',this.file);
                formdata.append('text1',this.newSlider.text1);
                formdata.append('text2',this.newSlider.text2);
                formdata.append('text3',this.newSlider.text3);
                formdata.append('url',this.newSlider.url);
                formdata.append('status',this.newSlider.status_n ? 1 : 0);
                axios.post('/slider/create', formdata).then(
                    res => {
                        if(res.data.status == 200) {
                            this.getSliders();
                            this.fillSlider = false;
                        }
                        if(res.data.status != 200) {
                            this.errors=res.data.data
                        }
                    }).catch(error=> {
                    console.log(error.response)
                        this.errors=error.response.data.data;
                        for(let i in this.errors) {
                            if(this.errors !== false) this.errors[i]=this.errors[i][0]
                        }
                })
            },
            editFile() {
                this.resetErrors();
                let formdata = new FormData();
                formdata.append('name',this.PostData.name);
                formdata.append('photo',this.file);
                formdata.append('deletedImages', this.showImage ? '0':'1');
                formdata.append('text1',this.PostData.text1);
                formdata.append('text2',this.PostData.text2);
                formdata.append('text3',this.PostData.text3);
                formdata.append('url',this.PostData.url);
                formdata.append('id',this.PostData.id);
                formdata.append('status',this.PostData.status_n ? 1 : 0);
                axios.post('/slider/edit', formdata).then(
                    res => {
                        if(res.data.status == 200) {
                            this.getSliders();
                            this.editSlider = false;
                            this.showImage = true;
                            this.PostData = {
                                name:'',
                                    photo:'',
                                    text1:'',
                                    text2:'',
                                    text3:'',
                                    url:'',
                                    status:'',
                                    deletedImages:0
                            };
                        }
                        if(res.data.status != 200) {
                            this.errors=res.data.data
                        }

                    }).catch(error=> {
                        this.errors=error.response.data.data;
                        for(let i in this.errors) {
                            if(this.errors !==false) this.errors[i]=this.errors[i][0]
                        }
                    })
            },
            resetErrors:function(){
                this.errors= {
                    name:false,
                    photo:false
                }
            },
            getSliders(page = 1) {
                axios.get(`/slider?count=${this.$count}&page=${page}`)
                    .then(response => {
                        this.slider_lists = response.data.data.list;
                        this.paginations = response.data.data.pagination
                    })
            },

            creatNewSlider(){
                this.fillSlider = true;
            },

            editNewSlider(item){
                this.resetErrors();
                this.PostData = item;
                this.PostData.status_n = item.status == '1';
                this.editSlider = true;
            },

            handleFileUpload(){
                this.file = this.$refs.file.files[0];
                this.filename = this.file.name
            },

            getClassByStatus(status) {
                switch (parseInt(status)) {
                    case 1:
                        return 'confirmed';
                        break;
                    case 2:
                        return 'waiting';
                        break;
                    case 0:
                        return 'rejected';
                        break;
                }
            },

            selectAllPromotions(){
                for(let i in this.slider_lists){
                    this.slider_lists[i].checked = this.selectAll
                }
            },

            deleteSlide:function () {
                let ids = [];
                for(let i in this.slider_lists){
                    if(this.slider_lists[i].checked) {
                        ids.push(this.slider_lists[i].id)
                    }
                }

                if(ids.length >0){
                    axios.post('/slider/del',{ids}).then(
                        response=> {
                            this.getSliders()
                        });
                }

            },

            deleteSlideImg() {
                this.showImage=false;
                this.PostData.deletedImages = 1;
            }
        }
    }
</script>

<style scoped>

</style>
