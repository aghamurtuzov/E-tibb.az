<template>
    <div class="users blog">
        <breadcrumb name="Bİzə yazanlar" icon="fa-user-o" :routes="[{'name': 'Bİzə yazanlar'}]"></breadcrumb>
        <div class="row">
            <Search :isnormalsearch="isnormalsearch" v-on:SearchRequestedFeedback = "getUsers" />
            <div class="col-md-12">
                <div class="information-blog">
                  <div class="notification-bar">
                    Cədvəl tam görünmürsə sağa doğru sürüşdürün və ya cihazınızın "ekran fırlanması" özəlliyini aktivləşdirin
                  </div>
                  <div class="block mtx-0">
                    <div class="table__Container">
                        <table class="table table-hover table-striped data-null">
                            <thead v-if="users.length>0">
                            <tr>
                                <th>Adı,soyadı</th>
                                <th>E-mail</th>
                                <th>Başlıq</th>
                                <th>Tarix</th>
                            </tr>
                            </thead>
                            <thead v-else class="thead-bottom">
                            <tr>
                                <th>Məlumat yoxdur</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(user) in users">
                                <td>{{user.name}}</td>
                                <td>{{user.email}}</td>
                                <td>{{user.title}}</td>
                                <td class="double-th">{{user.datetime}}
                                    <button class="btn btn-view" @click="showFeedback(user)">
                                        <span><i class="fa fa-eye" aria-hidden="true"></i></span>Bax
                                    </button>
                                </td>
                               <!-- <td>
                                    <button class="active-user btn" @click="activeUser(user)" v-if="user.status == 0"> Aktiv et</button>
                                    <button class="block-user btn" @click="blockUser(user)" v-else>Blok et</button>
                                </td>-->
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <pagination :paginations="paginations" :callback="getUsers"></pagination>
                  </div>
                </div>
            </div>
        </div>
        <div class="modal-block modal-feedback-block"  v-if="fillFeedback">
            <div class="modal fade in" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content pb-0">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="fillFeedback=false">
                            <span aria-hidden="true">
                                <img src="/admin/cp/img/popup-close.png" alt="popup-close">
                            </span>
                            </button>
                            <h5 class="modal-title">Bİzə yazanlar</h5>
                        </div>
                        <div class="modal-body new-addition feedback-modal">
                            <ul class="information-feedback list-unstyled feedback-inner">
                               <li>
                                   <p>Adı,soyadı</p>
                                   <span>{{selected_user.name}}</span>
                               </li>
                                <li>
                                    <p>E-mail</p>
                                    <span>{{selected_user.email}}</span>
                                </li>
                                <li>
                                    <p>Başlıq</p>
                                    <span>{{selected_user.title}}</span>
                                </li>
                                <li>
                                    <p>Mətn</p>
                                    <span>{{selected_user.text}}</span>
                                </li>
                                <li>
                                    <p>Tarix</p>
                                    <span>{{selected_user.datetime}}</span>
                                </li>
                                <li class="feedback-notify">
                                    <p>Mail göndər</p>
                                    <div class="input-group">
                                        <textarea name="notify" id="notify" cols="30" rows="9" placeholder="Bildiriş mətni" v-model="messageText" class="form-control"></textarea>
                                    </div>
                                    <div class="btn-textemail">
                                        <button type="submit" class="btn btn-email" @click="sendMessages('/contact/mail')">Göndər</button>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-backdrop fade in"></div>
        </div>
    </div>

</template>

<script>
    import {axios} from "../config/axios";
    import Search from '../Search';
    import {sendMessage} from '../config/helper';
    import Swal from 'sweetalert2';
    export default {
        name: "Users",
        data:function () {
            return {
                users:[],
                paginations:{},
                fillFeedback:false,
                selected_user:{},
                isnormalsearch: 'feedback',
                isSearched: 0,
                dates: '',
                datetime:'',
                errors_feedback: {
                    datetime:false
                },
                messageText: ''
            }
        },
        components: {
          Search
        },
        mounted() {
            this.getUsers()
        },
        methods: {

            resetErrorsFeedback: function(){
                this.errors_feedback = {
                    datetime:false
                }
            },

            getUsers(page, query, isSearched) {
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
                    this.resetErrorsFeedback();
                    if(query) {
                        this.dates = query.datetime ? new Date(query.datetime) : '';
                        this.datetime = this.dates !== '' ? this.dates.getFullYear() + "-" + ('0' + (this.dates.getMonth() + 1)).slice(-2) + "-" +('0'+this.dates.getDate()).slice(-2) : '';
                    }
                    axios.get(`/contact/search?count=${count}&datetime=${this.datetime}`)
                        .then(response => {
                            if(response.status === 200) {
                                if(response.data.data!==null) {
                                    if (response.data.data.list != null) {
                                        this.users = response.data.data.list;
                                        this.paginations = response.data.data.pagination
                                    }
                                    else {
                                        this.users = [];
                                        this.paginations = {};
                                    }
                                }
                                else {
                                    this.users = [];
                                    this.paginations = {};
                                }
                            }
                        })
                        .catch(error => {
                            if (error.response.data.data !== null) {
                                if(error.response.data.data) {
                                    error.response.data.data.datetime ? this.errors_feedback.datetime = error.response.data.data.datetime[0] : '';
                                }
                            }
                            else {
                                this.users = [];
                                this.paginations = {};
                            }
                        })
                }
                else {
                    axios.get('/contact'+'?count='+ count +'&page=' +page).then(
                        response=> {
                            this.users=response.data.data.list || [];
                            this.paginations = response.data.data.pagination
                        }
                    )
                }
            },

            showFeedback(user){
                this.fillFeedback = true;
                this.selected_user=user
            },

            searchFeedback(query) {
                console.log('feedback search', query);
            },

            sendMessages(url) {
                let id = this.selected_user.id;
                let message = this.messageText;
                if (!message) {
                    Swal.fire({
                        title: "Bildiriş!",
                        text: "Mesaj xanası boşdur!",
                        icon: "warning",
                        buttons: true,
                        showCancelButton: true,
                        dangerMode: true,
                    })
                }
                else {
                    sendMessage(url, id, message)
                        .then(data => (data === 'error') ? (  Swal.fire({
                            title: "Xəta!",
                            text: "Mesajınız göndərilə bilmədi!",
                            icon: "warning",
                            buttons: true,
                            showCancelButton: false,
                            dangerMode: true,
                        })) : (Swal.fire({
                            title: "Uğurlu!",
                            text: "Mesajınız göndərildi!",
                            icon: "success",
                            buttons: true,
                            showCancelButton: false,
                            dangerMode: true,
                        })
                            .then((result) => {
                                if (result.value) {
                                    this.messageText = '';
                                }
                            })));
                }

            }

        }
    }
</script>

<style scoped>
   .feedback-inner textarea {
       border-width: 1px;
       border-style: solid;
       border-radius: 10px !important;
       resize: none;
       padding: 20px 15px;
       margin: 10px 0 20px;
   }

    .feedback-inner button {
        background-color: #7ab6e4;
        font-size: 14px;
        text-transform: uppercase;
        height: 40px;
        border-radius: 20px;
        width: 100%;
        color: #fff;
        font-weight: 600;
        padding: 3px 12px;
        display: block;
    }
</style>
