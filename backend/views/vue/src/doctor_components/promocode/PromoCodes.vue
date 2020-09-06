<template>
    <div class="reservation blog">
        <breadcrumb name="Promokodlar" icon="fa-ticket" :routes="[{'name':'Promokodlar'}]"></breadcrumb>
        <div class="row">
            <div class="col-md-12">
                <div class="search">
                    <div class="block">
                        <form class="w-100" @submit="checkPromoCode()">
                          <div class="search-blog promo">
                            <div class="input-group mbx-10">
                              <input type="text" class="form-control" placeholder="Promo kodu daxil edin" aria-describedby="basic-addon1" v-model="promoCode">
                            </div>
                            <button type="submit" class="btn-effect">YOXLA</button>
                          </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="information-blog">
                    <div class="block">
                        <table class="table table-hover table-striped data-null">
                            <thead v-if="promocodes.length > 0">
                                <tr>
                                    <th>Promo kod</th>
                                    <th>Aksiya</th>
                                    <th>Təşkilatçı</th>
                                    <th>İstifadəçi</th>
                                </tr>
                            </thead>
                            <thead v-else class="thead-bottom">
                                <tr>
                                    <th>Məlumat yoxdur</th>
                                </tr>
                            </thead>
                            <tbody>
                            <tr v-for="promocode in promocodes">
                                <td>{{promocode.promocode}}</td>
                                <td>{{promocode.promotion_headline}}</td>
                                <td>{{promocode.byPromotion}}</td>
                                <td>{{promocode.used_userName}}</td>
                            </tr>
                            </tbody>
                        </table>
                        <pagination v-if="paginations" :paginations="paginations" :callback="getPromoCodes"></pagination>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-block" v-if="promoCodeExists">
            <div class="modal fade in" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="promoCodeExists=false">
                            <span aria-hidden="true">
                                <img src="/admin/cp/img/popup-close.png" alt="popup-close">
                            </span>
                            </button>
                            <h4 class="modal-title" v-if="userExists">İstifadəçi tapıldı!</h4>
                            <h4 class="modal-title unknown-title" v-else>İstifadəçi tapılmadı!</h4>
                        </div>
                        <div class="modal-body">
                            <div class="left-body">
                                <div class="modal-inn">
                                    <span>Adı, Soyadı</span>
                                    <p v-if="userExists">{{name}}</p>
                                    <p v-else>Naməlum Şəxs</p>
                                </div>
                                <div class="modal-proma">
                                    <div class="modal-inn" v-if="userExists">
                                        <span>Qeydiyyat nömrəsi</span>
                                        <p>{{user_id}}</p>
                                    </div>
                                    <div class="modal-inn">
                                        <span>Promo kodu</span>
                                        <p>{{promoCode}}</p>
                                    </div>
                                </div>
                            </div>
                            <img src="/admin/cp/img/promo-active.png" alt="promo-active" v-if="userExists">
                            <img src="/admin/cp/img/promo-unknown.png" alt="promo-unknown" v-else>
                        </div>
                        <div class="modal-footer" v-if="userExists">
                            <button type="button" class="btn-effect" @click="usePromoCode()" data-dismiss="modal">İstİfadə et</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-backdrop fade in"></div>
        </div>
    </div>
</template>

<script>
    import {axios} from "../config/doctor_axios";

    export default {
        name: "PromoCodes",
        data:function () {
            return {
                promocodes:[],
                paginations: {},
                promoCodeExists:false,
                promoCode:'',
                name:'',
                user_id:'',
                userExists:false,
                used:true
            }
        },
        mounted() {
            this.getPromoCodes()
        },
        methods: {
            getPromoCodes(page=1) {
                axios.get('/promotions/used-list'+'?count='+this.$count + '&page='+page).then(
                    response=> {
                        if (response.data.data !== null) {
                            this.promocodes = response.data.data.list;
                            this.paginations = response.data.data.pagination
                        }
                    }
                )
            },
            checkPromoCode (){
                if(this.promoCode.trim()){
                    axios.post('/promotions/check',{promocode:this.promoCode}).then(
                        response=> {
                            this.promoCodeExists = true;
                            if (response.data.status == 200) {
                                this.userExists = true;
                                this.name = response.data.data.name;
                                this.user_id = response.data.data.user_id
                            }
                        }).catch(response => {
                        this.promoCodeExists = true;
                        this.userExists = false;
                    })

                }
            },

            usePromoCode(){
                axios.post('/promotions/check',{promocode:this.promoCode,used:this.used}).then(
                    response=> {
                        this.promoCodeExists = false;
                        this.getPromoCodes()
                    })
            }

        }
    }
</script>

<style scoped>

</style>
