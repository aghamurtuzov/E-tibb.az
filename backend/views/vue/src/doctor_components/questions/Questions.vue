<template>
    <div class="quiz letters blog">
        <breadcrumb name="SUAL - CAVAB" icon="fa-comments" :routes="[{name:'Sual-Cavab'}]"></breadcrumb>
        <div class="row">
            <Search :isnormalsearch="isnormalsearch" v-on:SearchRequested = "search" />
            <div class="col-md-12 question-position"  v-if="questions.length>0">
                <div class="row relative">
                    <div class="col-md-7">
                        <div class="information-blog">
                            <div class="block">
                                <table class="table table-hover table-striped">
                                    <thead>
                                    <tr>
                                        <th>İstifadəçi adı, soyadı</th>
                                        <th>Sual</th>
                                        <th>Tarix</th>
                                    </tr>
                                    </thead>
                                    <tbody class="text-content">
                                    <tr v-for="question in questions" :class="selected_item == question ? 'active' : ''" @click="selected_item = question">
                                        <td>{{question.doctor_name}}</td>
                                        <td>
                                            <p class="letter-text">{{question.question}}</p>
                                        </td>
                                        <td>{{question.q_datetime.split(" ")[0]}}</td>
                                    </tr>
                                    </tbody>
                                </table>
                                <pagination :paginations="paginations" :callback="getQuestions"></pagination>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
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
<!--                                                <div class="status-block">-->
<!--                                                    <button class="active-status btn" @click="active_question()" v-if="selected_item.status==0">Aktiv et</button>-->
<!--                                                    <button class="block-status btn" @click="block_question()" v-else>Blok et</button>-->
<!--                                                </div>-->
                                            </li>
                                            <li class="bg-lightgreen" v-if="selected_item.answer !== null && selected_item.answer.length > 0 && answer_is_delete===0" >
                                                <p class="user-information" >
                                                    {{selected_item.doctor_name}}
                                                    <span v-if="selected_item.a_datetime !== null">{{ selected_item.a_datetime.split(" ")[0]}}</span>
                                                </p>
                                                <p>{{selected_item.answer}}</p>
                                                <div class="status-block">
                                                  <button class="btn delete-answer" @click="delete_answer()" v-if="answer_is_delete===0" >Sil</button>
                                                </div>
<!--                                                <div class="status-block">-->
<!--                                                    <button class="active-status btn" @click="active_answer()" v-if="selected_item.a_status==0">Aktiv et</button>-->
<!--                                                    <button class="block-status btn" @click="block_answer()" v-else>Blok et</button>-->
<!--                                                </div>-->
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
            <div class="col-md-12 changeData" v-else>
                <div class="block">
                    <h4>Məlumat yoxdur</h4>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {axios} from "../config/doctor_axios";
    import Search from '../Search';
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
                isnormalsearch: 0
            }
        },

        mounted:function () {
            this.getQuestions(this.page)
        },
        components: {
          Search
        },

        methods: {
            getQuestions(page) {
                this.page = page;
                axios.get('/consultation'+'?count=5&page='+page,).then(
                    response=> {
                        if(response.data.data !=null) {
                            this.questions=response.data.data.list;
                            this.selected_item = this.questions[0];
                            this.paginations = response.data.data.pagination
                        }
                    }
                )
            },

            addAnswer:function () {
              console.log('test')
                axios.post('/consultation/answer',{answer: this.answer,id:this.selected_item.id}).then(
                    response=> {
                        this.selected_item.answer = this.answer;
                        this.answer = '';
                        this.getQuestions(this.page);
                        this.answer_is_delete = 0;
                    }
                )

            },

            active_question:function () {
                axios.post('/consultation/active',{id: this.selected_item.id}).then(
                    response=>{
                        this.selected_item.status= (this.selected_item.status == '0') ? 1 : 0
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

          delete_answer:function () {
            axios.post('/consultation/delete-answer',{id: this.selected_item.id}).then(
              response=>{
                this.answer_is_delete= (this.answer_is_delete === 1) ? 0 : 1
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

            search(query) {
            }
        }
    }
</script>

<style scoped>

</style>
