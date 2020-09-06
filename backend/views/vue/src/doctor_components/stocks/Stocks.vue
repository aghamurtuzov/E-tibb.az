<template>
    <div class="stocks blog">
        <breadcrumb name="Aksİyalar" icon="fa-certificate" :routes="[{'name':'Aksiyalar'}]"></breadcrumb>
        <div class="row">
            <Search :isnormalsearch="isnormalsearch" v-on:SearchRequested = "search" />
            <div class="col-md-12">
                <div class="information-blog">
                    <div class="notification-bar">
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
                                <th>Foto</th>
                                <th>Aksiya keçirən Adı, Kodu</th>
                                <th>Aksiya adı</th>
                                <th>Qiymət</th>
                                <th>Endirim %</th>
                                <th>Başlanğıc Tarix</th>
                                <th>Bitiş Tarix</th>
                                <th>Status</th>
                                <th>
                                    <router-link tag="button" class="btn-add" to="/stocks/add">
                                        <span><i class="fa fa-plus" aria-hidden="true"></i></span>YENİ
                                    </router-link>
                                </th>
                            </tr>
                            </thead>
                            <tbody v-if="stocks.length>0">
                            <tr v-for="stock in stocks">
                                <td>
                                    <label class="check-button">
                                        <input type="checkbox" :checked="selectAll" v-model="stock.checked">
                                        <span class="checkmark"></span>
                                    </label>
                                </td>
                                <td>
                                    <img :src="stock.thumb" alt="list-item1" class="img-square">
                                </td>
                                <td>{{stock.byPromotion}}</td>
                                <td>{{stock.headline}}</td>
                                <td>{{stock.price}}</td>
                                <td>{{stock.discount}}</td>
                                <td>{{stock.date_start}}</td>
                                <td>{{stock.date_end}}</td>
                                <td><p :class="getClassByStatus(stock.status)">{{stock.status_name}}</p></td>
                                <td>
                                    <router-link :to="{path: 'stock/'+stock.id, }" class="btn btn-view">
                                        <span><i class="fa fa-eye" aria-hidden="true"></i></span>Bax
                                    </router-link>
                                </td>
                            </tr>
                            </tbody>
                            <tbody v-else class="thead-bottom">
                            <tr>
                              <td colspan="10">
                                Məlumat yoxdur
                              </td>
                            </tr>
                            </tbody>
                        </table>
                      </div>
                      <pagination :paginations="paginations" :callback="getStocks"></pagination>
                    </div>
                </div>
                <div class="clinic-tariffs-edit" v-if="stocks.length > 0">
                    <div class="block">
                        <button type="button" class="btn" @click="deletePromotion()">
                            Sİl
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
    import {axios} from '../config/doctor_axios';
    import Search from '../Search';
    export default {
        name: "Stocks",
        data: function () {
            return {
                stocks: [],
                paginations: {},
                date_end: '',
                date_start: '',
                discount: '',
                price: '',
                status_name: '',
                thumb: '',
                id: '',
                byPromotion: '',
                status: '',
                selectAll:false,
                searchInput: '',
                isnormalsearch: 1
            }
        },
        mounted: function () {
            this.getStocks();
             window.beforeunload = this.leaving;
        },
        components: {
          Search
        },
        methods: {
            getStocks(page = 1) {
                axios.get(`promotions/index?count=${this.$count}&page=${page}`)
                    .then(response => {
                      // console.log(response.data.data);
                        if (response.data.data !== null) {
                          this.stocks = response.data.data.list;
                          this.paginations = response.data.data.pagination
                        }
                    })
                    .catch(e => {
                        console.log(e)
                    })
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
                for(let i in this.stocks){
                    this.stocks[i].checked = this.selectAll
                }
            },
            deletePromotion:function () {
                let ids = [];
                for(let i in this.stocks){
                    if(this.stocks[i].checked) {
                        ids.push(this.stocks[i].id)
                    }
                }

                if(ids.length >0){
                    axios.post('/promotions/del',{ids}).then(
                        response=> {
                            this.getStocks()
                        });
                }
            },
            search(query) {
            },
            leaving: function () {
              alert("Leaving...");
            }
        }
    }
</script>

<style scoped>

</style>
