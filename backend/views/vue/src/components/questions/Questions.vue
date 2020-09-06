<template>
    <div class="quiz letters blog">
        <breadcrumb name="SUAL - CAVAB" icon="fa-comments" :routes="[{name:'Sual-Cavab'}]"></breadcrumb>
        <div class="row">
            <div class="col-md-12">
                <div class="appointment statics mb-20">
                    <div class="static-blocks doctor-static">
                        <div class="block cursor" @click="isActiveOrDeactive(1)" :class="isactivequestions === 1 ? 'active' : '' ">
                            AKTİV SUALLAR
                        </div>
                        <div class="block cursor" @click="isActiveOrDeactive(0)" :class="isactivequestions === 0 ? 'active' : '' ">
                            DEAKTİV SUALLAR
                        </div>
                        <div class="block cursor mbx-0" @click="isActiveOrDeactive('')" :class="isactivequestions === 'all' ? 'active' : '' ">
                            BÜTÜN SUALLAR
                        </div>
                    </div>
                </div>
            </div>

            <Search :isnormalsearch="isnormalsearch" :errors_questions = "errors_questions" v-on:SearchRequestedQuestions = "getQuestions" />

            <div class="col-md-12 question-position">
                <div class="row relative">
                    <div class="col-md-7">
                        <div class="information-blog">
                          <div class="notification-bar">
                            Cədvəl tam görünmürsə sağa doğru sürüşdürün və ya cihazınızın "ekran fırlanması" özəlliyini aktivləşdirin
                          </div>
                          <div class="block mtx-0">
                            <div class="table__Container">
                                <table class="table table-hover table-striped table__questions">
                                    <thead>
                                    <tr>
                                        <th>
                                            <label class="check-button">
                                                <input type="checkbox" v-model="selectAll" @change="selectAllQuestions(); selectQuestions();">
                                                <span class="checkmark"></span>
                                            </label>
                                        </th>
                                        <th>Həkim adı, soyadı</th>
                                        <th>İstifadəçi adı, soyadı</th>
                                        <th style="max-width:100px;">Sual</th>
                                        <th>Tarix</th>
                                        <th style="min-width:215px; text-align:center !important;">
                                            <button style="float: none;" type="button" class="btn-trash" v-tooltip.auto="remove_message" @click="delete__custom('/consultation/all-delete', 'all')">
                                                <span><i class="fa fa-trash" aria-hidden="true"></i></span>
                                            </button>
                                            <span v-if="user_role == 'superadmin'">
                                                <button style="float: none;" type="button" class="btn-trash" v-tooltip.top="hard_remove_message" @click="delete__custom('/consultation/all-base-delete','all')">
                                                    <span><i class="fa fa-minus-square" aria-hidden="true"></i></span>
                                                </button>
                                            </span>
                                            <span v-else>
                                            </span>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody class="text-content" v-if="questions.length>0">
                                    <tr v-for="question in questions" :class="selected_item == question ? 'active' : ''" @click="(selected_item = question); reset_errors_active();" >
                                        <td>
                                            <label class="check-button">
                                                <input type="checkbox" :checked="selectAll" v-model="question.checked" @change="selectQuestions()">
                                                <span class="checkmark"></span>
                                            </label>
                                        </td>
                                        <td>
                                          <router-link :to="{path:'/doctor/' + question.doctor_id}" target="_blank">
                                            {{question.doctor_name}}
                                          </router-link>
                                        </td>
                                        <td>{{question.name}}</td>
                                        <td style="max-width:100px;">
                                            <p class="letter-text">{{question.question}}</p>
                                        </td>
                                        <td>{{question.q_datetime}}</td>
                                        <td>
                                            <span v-if="user_role == 'superadmin'">
                                                <button type="button" class="btn btn-remove-doctor" @click="delete__custom('/consultation/base-delete-one', question.id)">
                                                    <span><i class="fa fa-minus-square" aria-hidden="true"></i>Həmişəlik sil</span>
                                                </button>
                                            </span>
                                            <span v-else=""></span>

                                            <button type="button" class="btn btn-remove-doctor" @click="delete__custom('/consultation/delete-one', question.id)">
                                                <span><i class="fa fa-trash" aria-hidden="true"></i>Sil</span>
                                            </button>
                                        </td>
                                    </tr>
                                    </tbody>
                                    <tbody v-else class="thead-bottom" >
                                    <tr>
                                        <td colspan="6">Məlumat yoxdur</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <pagination :paginations="paginations" :callback="getQuestions"></pagination>
                          </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <span class="validation validation_question" v-if="errors_active">{{errors_active}}</span>
                        <div class="article-form">
                            <div class="block">
                                <div class="article-lists">
                                    <div class="text-content">
                                        <ul class="list-inline information-body">
                                            <li class="bg-whitesmoke" v-if="selected_item.question !== null && selected_item.question.length > 0 ">
                                                <p class="user-information">
                                                    {{selected_item.name}}
                                                    <span>{{ selected_item.q_datetime.split(" ")[0]}}</span>
                                                </p>
                                                <p>{{selected_item.question}} </p>
                                                <div class="status-block">
                                                    <button class="active-status btn" @click="active_question()" v-if="selected_item.status==0">Aktiv et</button>
                                                    <button class="block-status btn" @click="block_question()" v-else>Blok et</button>
                                                </div>
                                            </li>
                                            <li class="bg-lightgreen" v-if="selected_item.answer !== null && selected_item.answer.length > 0  && answer_is_delete===0" >
                                                <p class="user-information" >
                                                    {{selected_item.doctor_name}}
                                                    <span v-if="selected_item.a_datetime !== null">{{ selected_item.a_datetime.split(" ")[0]}}</span>
                                                </p>
                                                <p>{{selected_item.answer}}</p>
                                                <div class="status-block">
                                                    <button class="active-status btn" @click="active_answer()" v-if="selected_item.a_status==0">Aktiv et</button>
                                                    <button class="block-status btn" @click="block_answer()" v-else>Blok et</button>
                                                    <button class="btn delete-answer" @click="delete_answer()" v-if="answer_is_delete===0" >Sil</button>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="form-list give-answer">
                                    <div class="file-upload-wrapper uploadContainer">
                                        <div class="input-group">
                                            <label>
                                                <textarea v-model="answer" type="text" placeholder=" " class="form-control inputText"></textarea>
                                                <span>Cavab yaz </span>
                                            </label>
                                        </div>

                                      <button v-if="selected_item.answer !== null && selected_item.answer.length > 0 && answer_is_delete===0" class="btn-effect disabled" @click="addAnswer()" disabled="true">
                                        GÖNDƏR
                                      </button>
                                      <button v-else="" class="btn-effect" @click="addAnswer()">
                                        GÖNDƏR
                                      </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {axios} from "../config/axios";
    import Search from '../Search';
    import {axiosGet, axiosPost} from '../config/helper';
    import Swal from "sweetalert2";

    export default {
        name: "Quiz",
        data:function () {
            return {
                id: this.$route.params.id,
                questions:[],
                selected_item: {
                    name: '',
                    question: '',
                    doctor_name: '',
                    answer: '',
                    q_datetime: '',
                    a_datetime: '',
                    status:'',
                    a_status:''
                },
                answer_is_delete: 0,
                paginations: {},
                answer: '',
                page: 1,
                isnormalsearch: 'questions',
                errors_active: "",
                isactivequestions: 0,
                errors_questions:{
                    doctor_name:false,
                    q_datetime:false
                },
                isSearched: 0,
                doctor_name: '',
                dates: '',
                q_datetime:'',
                selectAll:false,
                selectedId: '',
                user_role: '',
                remove_message: 'Toplu sil',
                hard_remove_message: 'Həmişəlik sil (Toplu)'
            }
        },

        mounted:function () {
            this.getQuestions(this.page);
            axiosGet('/api/site/user').then(data => this.user_role = data.role.item_name);
        },

        components: {
          Search
        },

        methods: {
            reset_errors_active() {
              this.errors_active = ''
            },
            getQuestions(page, query, isSearched) {
                if(isSearched) {
                    this.isSearched = isSearched;
                }

                if(this.$route.query.page) {
                    page = this.$route.query.page;
                }
                else {
                    page = 1;
                }
                this.page = page;
                let count = 50;

                if(this.isSearched === 1) {
                    this.resetErrorsQuestions();
                    if(query) {
                        this.doctor_name = query.doctor_name;
                        console.log(query.q_datetime,new Date(query.q_datetime) )
                        this.dates = query.q_datetime ? new Date(query.q_datetime) : '';
                        this.q_datetime = this.dates !== '' ? this.dates.getFullYear() + "-" + ('0' + (this.dates.getMonth() + 1)).slice(-2) + "-" +('0'+this.dates.getDate()).slice(-2) : '';
                    }
                    axios.get(`/consultation/search?page=${page}&count=${count}&doctor_name=${this.doctor_name}&q_datetime=${this.q_datetime}&status=${this.isactivequestions}`)
                        .then(response => {
                            if(response.status === 200) {
                                if(response.data.data!==null) {
                                    if (response.data.data.list != null) {
                                        this.paginations = response.data.data.pagination;
                                        this.paginations.perPage = 5;
                                        console.log('12121212', this.paginations)
                                        this.questions = response.data.data.list;
                                    }
                                    else {
                                        this.questions = [];
                                        this.paginations = {};
                                    }
                                }
                                else {
                                    this.questions = [];
                                    this.paginations = {};
                                }
                            }
                        })
                        .catch(error => {
                            if (error.response.data.data !== null) {
                                if(error.response.data.data) {
                                    error.response.data.data.doctor_name ? this.errors_questions.doctor_name = error.response.data.data.doctor_name[0] : '';
                                    error.response.data.data.q_datetime ? this.errors_questions.q_datetime = error.response.data.data.q_datetime[0] : '';
                                }
                                console.log('teseeee', )
                            }
                            else {
                                this.questions = [];
                                this.paginations = {};
                            }
                        })
                }
                else {
                    axios.get(`consultation?count=${count}&page=${page}&type=${this.isactivequestions}`).then(
                        response=> {
                            if(response.data.data !=null) {
                                this.questions=response.data.data.list;
                                this.selected_item = this.questions[0];
                                this.paginations = response.data.data.pagination
                                console.log('252552', this.paginations, response.data.data.pagination)
                            }
                        }
                    )
                }
            },

            isActiveOrDeactive(isactivequestions) {
                this.isSearched = 0;
                if(this.$route.query.page !== '1') {
                    this.$router.push({ query: { page: 1 }});
                }
                if (isactivequestions === 1) {
                    this.isactivequestions = 1;
                    this.getQuestions(this.isactivequestions);
                }
                else if (isactivequestions === 0) {
                    this.isactivequestions = 0;
                    this.getQuestions(this.isactivequestions);
                }
                else {
                    this.isactivequestions = 'all';
                    this.getQuestions(this.isactivequestions);
                }
            },

            addAnswer:function () {
              console.log('clicked')
                axios.post('/consultation/answer',{answer: this.answer,id:this.selected_item.id}).then(
                    response=> {
                        this.selected_item.answer = this.answer;
                        this.answer = '';
                        this.getQuestions(this.page)
                      this.answer_is_delete = 0;
                    }
                )

            },

            active_question:function () {
              console.log('emeliyat start')
                axios.post('/consultation/active',{id: this.selected_item.id})
                  .then(
                    response=>{
                        this.errors_active = '';
                        this.selected_item.status= (this.selected_item.status == '0') ? 1 : 0
                    },
                    error=> {
                      if (error.response) {
                        this.errors_active = error.response.data.message;
                      }
                    }
                  )
            },

            block_question:function () {
                axios.post('/consultation/block',{id: this.selected_item.id}).then(
                    response=>{
                        this.selected_item.status= (this.selected_item.status == '1') ? 0 : 1
                    }
                )
            },

            active_answer:function () {
                axios.post('/consultation/active-answer',{id: this.selected_item.id}).then(
                    response=>{
                        this.selected_item.a_status= (this.selected_item.a_status == '0') ? 1 : 0
                    }
                )
            },

            block_answer:function () {
                axios.post('/consultation/block-answer',{id: this.selected_item.id}).then(
                    response=>{
                        this.selected_item.a_status= (this.selected_item.a_status == '1') ? 0 : 1
                    }
                )
            },

            delete_answer:function () {
              axios.post('/consultation/delete-answer',{id: this.selected_item.id}).then(
                response=>{
                  this.answer_is_delete= (this.answer_is_delete == 1) ? 0 : 1
                }
              )
            },

            resetErrorsQuestions: function(){
                this.errors_questions = {
                    doctor_name:false,
                    q_datetime:false
                }
            },

            selectAllQuestions(){
                for(let i in this.questions){
                    this.questions[i].checked = this.selectAll
                }
            },

            selectQuestions() {
                let selectedId = [];
                for (let i in this.questions) {
                    if (this.questions[i].checked) {
                        selectedId.push(this.questions[i].id)
                    }
                }
                this.selectedId = JSON.stringify(selectedId);
            },

            delete__custom(url, ids) {
                if(ids === 'all') {
                    var id = [];
                    if (this.selectedId) {
                        id = JSON.parse(this.selectedId);
                    }
                }
                else {
                    var id = ids;
                }
                if (id.length > 0) {
                    Swal.fire({
                        title: "Bu əməliyyatı icra etməyə əminsiniz?",
                        text: "Əgər silsəniz, geri qaytara bilməyəcəksiniz!",
                        icon: "warning",
                        buttons: true,
                        showCancelButton: true,
                        dangerMode: true,
                    })
                        .then((result) => {
                            if (result.value) {
                                axiosPost(url,id).then(data => this.getQuestions());
                                this.selectedId = '';
                            }
                        });
                }
                else {
                    return Swal.fire({
                        icon: 'error',
                        title: 'Bildiriş',
                        confirmButtonText:
                            '<i class="fa fa-thumbs-up"></i> Oldu!',
                        text: 'Heç bir sual seçməmisiniz!',
                    })
                }
            },
        }
    }
</script>

<style scoped>
  .validation_question {
    font-size: 17px;
    padding: 4px 15px;
  }
</style>
