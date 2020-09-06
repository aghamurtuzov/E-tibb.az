<template>
    <div class="sign-block">
        <breadcrumb name="ödənİş" icon="fa-money" :routes="[{'name':'Ödəniş'}]"></breadcrumb>
        <div class="row">
            <div class="col-md-7">
                <div class="block">
                    <h4> <span><i class="fa fa-diamond" aria-hidden="true"></i></span> PAKETLƏR</h4>
                    <p>Əgər Siz <a href="">www.e-tibb.az</a> saytında klinikalar kataloqunda olmaq istəyirsinizsə, aşağıdakı seçimlərdən yararlana bilərsiniz: </p>
                    <div class="row">
                        <div class="col-md-4 " v-for="item in package_lists">
                            <div class="tariffs">
                                <div class="tariff-head">
                                    <p>{{item.name}}</p>
                                    <span>{{item.price}} <sup>M</sup> </span>
                                </div>
                                <div class="tariff-body text-center">
                                    <h1>{{item.month}}</h1>
                                    <p class="tariff-month">Aylıq</p>
                                    <p class="tariff-info">{{item.description}}</p>
                                    <button class="check-button" @click="selectPackage(item.id)">
                                        <label>
                                            <i class="fa fa-check-circle-o" aria-hidden="true" v-if="item.id == PostData.package"></i>
                                            <i class="fa fa-circle-thin" aria-hidden="true" v-else></i>
                                            <span class="check-btn-text">PAKETİ SEÇ</span>
                                        </label>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="block">
                    <h4> <span><i class="fa fa-trophy" aria-hidden="true"></i></span> VİP xidmətlər</h4>
                    <p>Əgər Siz <a href="">www.e-tibb.az</a> saytında klinikalar kataloqunda olmaq istəyirsinizsə, aşağıdakı seçimlərdən yararlana bilərsiniz: </p>
                    <ul class="tariffs-list list-unstyled new-addition">
                        <li v-for="social_item in social_list">
                            <label class="check-button" >
                                <span class="check-btn-text">{{social_item.name}}</span>
                                <input type="checkbox" v-model="social_item.checked" @change="selectSocial(social_item.id)">
                                <span class="checkmark"></span>
                            </label>
                            <div class="general-info">
                                <div class="input-group input-up">
                                    <multiselect v-model="social_item.interval"
                                                 label="name"
                                                 selectedLabel=""
                                                 deselectGroupLabel=""
                                                 deselectLabel=""
                                                 select-label=""
                                                 :reset-after="false"
                                                 :allow-empty="false"
                                                 :internal-search="false"
                                                 :close-on-select="true"
                                                 :clear-on-select="false"
                                                 :hide-selected="false"
                                                 :options="social_item.prices"
                                                 @input="selectSocialDuration(social_item)"
                                                 track-by="value" >
                                    </multiselect>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-12">
                <div class="tariffs-sum">
                    <div class="block">
                        <p>Cəmi: <span>{{total_price}} <sup>M</sup>  </span></p>
                        <button type="button" class="btn-effect" @click="getPackage()">PREMIUM ET</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Multiselect from 'vue-multiselect';
    import {axios} from "../config/enterprise_axios";
    export default {
        components: {
            Multiselect
        },
        name: "Account",
        data(){
            return {
                PostData:{
                    package: '',
                    services:[],
                    service_month:[]
                },
                package_lists:[],
                social_list:[],
                prices:[],
                total_price:0
            }
        },
        mounted() {
            this.getPackList();
            this.getSocialList();
        },
        methods: {
            selectPackage: function (id) {
                this.PostData.package = id;
                for(let i in this.package_lists) {
                    this.package_lists[i].checked = this.package_lists[i].id == id
                }

                this.calculateTotal();
            },
            calculateTotal(){
                this.total_price = 0;
                for(let i in this.package_lists){
                    if(this.PostData.package == this.package_lists[i].id) this.total_price += parseInt(this.package_lists[i].price)
                }

                for(let i of this.social_list){
                    if(i.checked){
                        this.total_price += parseInt(i.interval.price)
                    }
                }
            },
            getPackList(){
                axios.get('/pay/get-packages').then(
                    response=> {
                        this.package_lists=response.data.data
                    }
                )
            },
            getPackage:function () {
                let formData = new FormData;
                for(let i in this.PostData){
                    if(Array.isArray(this.PostData[i])){
                        for(let j in this.PostData[i]){
                            formData.append(`${i}[`+j+`]`, this.PostData[i][j]);
                        }
                    }else{
                        formData.append(i, this.PostData[i])
                    }
                }
                axios.post('/pay/create',formData).then(
                    response=> {
                        if(response.data.status == 200)
                            location.href = response.data.data
                    }
                )
            },

            getSocialList() {
                axios.get('/pay/get-services').then(
                    response=>{
                        let socials = [];
                        for(let i of response.data.data){
                            let obj = {};
                            obj.name = i.name;
                            obj.id = i.id;
                            obj.checked = false;
                            obj.prices = [];
                            obj.interval = { name: 'Paket seçin', value:'0'};
                            for(let j of i.prices ){
                                obj.prices.push({
                                    price: j.price,
                                    value: j.month,
                                    name: j.month + ' ayliq -' + j.price +' AZN'
                                });
                            }
                            socials.push(obj);
                        }
                        this.social_list = socials;
                    }
                )
            },
            selectSocial:function (id) {
                let index = this.PostData.services.indexOf(id);
                if(index<0) {
                    this.PostData.services.push(id)
                }else{
                    this.PostData.services.splice(index, 1)
                }
                this.calculateTotal();
            },

            selectSocialDuration: function(item){
                this.PostData.service_month.push(item.id + '-' + item.interval.value + '-' +item.interval.price);
                this.calculateTotal();
            }
        }
    }
</script>

<style scoped>

</style>