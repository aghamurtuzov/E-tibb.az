<template>
    <div class="reviews letters blog">
        <breadcrumb name="Rəylər" icon="fa-thumbs-up" :routes="[{name:'Rəylər'}]"></breadcrumb>
        <div class="row">
            <div class="col-md-12">
                <Search :isnormalsearch="isnormalsearch" v-on:SearchRequested = "search" />
            </div>
            <div class="col-md-12 reservation-position" v-if="reviews.length>0">
                <div class="row relative">
                    <div class="col-md-7">
                        <div class="information-blog">
                            <div class="block">
                                <table class="table table-hover table-striped">
                                    <thead>
                                    <tr>
                                        <th>İstifadəçi</th>
                                        <th>Rəy verilən</th>
                                        <th>Reytinq</th>
                                        <th>Tarix</th>
                                    </tr>
                                    </thead>
                                    <tbody class="text-content">
                                    <tr v-for="review in reviews" @click="selected_item = review" :class="selected_item == review ? 'active' : ''">
                                        <td>{{review.name}}</td>
                                        <td>
                                            <p class="comment-text">{{review.comment_by}}</p>
                                        </td>
                                        <td>
                                            <p class="rating">
                                                <span v-for="item in [0,1,2,3,4]"><i class="fa" :class="getClass(item, review.rating)" aria-hidden="true"></i></span>
                                            </p>
                                        </td>
                                        <td>
                                            {{review.datetime.split(" ")[0]}}
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <pagination :paginations="paginations" :callback="getReviews"></pagination>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="article-form">
                            <div class="block">
                                <div class="article-lists">
                                    <div class="text-content">
                                        <ul class="list-inline information-body ">
                                            <li class="bg-whitesmoke">
                                                <p class="user-information">
                                                    {{selected_item.name}}
                                                    <span>{{ selected_item.datetime.split(" ")[0]}}</span>
                                                </p>
                                                <p>{{selected_item.comment}} </p>
                                                <div class="status-block">
                                                    <button class="active-status btn" @click="active_comment()" v-if="selected_item.status==0">Aktiv et</button>
                                                    <button class="block-status btn" @click="block_comment()" v-else>Blok et</button>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 changeData" v-else>
                <div class="block">
                    <h5>Məlumat yoxdur</h5>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {axios} from "../config/enterprise_axios";
    import Search from '../Search';
    export default {
        name: "Review",
        data: function () {
            return {
                id: this.$route.params.id,
                reviews:[],
                paginations:{},
                selected_item: {
                    name: '',
                    comment: '',
                    datetime: '',
                    status:''
                },
                isnormalsearch: 0
            }
        },
        mounted() {
            this.getReviews()
        },
        components: {
          Search
        },
        methods:{
            getClass(i, star){
                if(star > i && star >= i+1){
                    return 'fa-star'
                }else if(star > i && star < i+1){
                    return 'fa-star-half-o'
                }else{
                    return 'fa-star-o'
                }
            },
            getReviews(page=1) {
                axios.get('/comments'+'?count=5&page='+page,).then(
                    response=> {
                      if (response.data.data !== null) {
                        this.reviews=response.data.data.list;
                        this.selected_item = this.reviews[0];
                        this.paginations = response.data.data.pagination
                      }
                    }
                )
            },
            active_comment:function () {
                axios.post('/comments/active',{id: this.selected_item.id}).then(
                    response=>{
                        this.selected_item.status=(this.selected_item.status =='0') ? 1 : 0
                    }
                )
            },
            block_comment:function () {
                axios.post('/comments/block',{id: this.selected_item.id}).then(
                    response=>{
                        this.selected_item.status=(this.selected_item.status == '1') ? 0 : 1
                    }
                )
            },
            search(query) {
              console.log(query);
            }
        }
    }
</script>

<style scoped>

</style>
