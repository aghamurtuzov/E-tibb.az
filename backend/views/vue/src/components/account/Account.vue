<template>
    <div class="account blog">
        <breadcrumb name="MÜHASİBAT" icon="fa-money" :routes="[{'name':'Mühasibat'}]"></breadcrumb>
        <div class="row">
            <Search :isnormalsearch="isnormalsearch" v-on:SearchRequested = "search" />
            <div class="col-md-12">
                <div class="account-lists">
                    <div class="block">
                        <ul class="nav nav-tabs information-head">
                            <li @click="tab = 'payments'" :class="tab == 'payments' ? 'active': '' ">Ödənişlər</li>
                            <li  @click="tab = 'accounts'" :class="tab == 'accounts' ? 'active': '' " >Hesablar</li>
                            <li @click="tab = 'expenses'" :class="tab == 'expenses' ? 'active': '' " >Xərclər</li>
                        </ul>
                    </div>
                </div>
                <div class="information-blog">
                    <div class="notification-bar mtx-0">
                      Cədvəl tam görünmürsə sağa doğru sürüşdürün və ya cihazınızın "ekran fırlanması" özəlliyini aktivləşdirin
                    </div>
                    <div class="block pb-0 mtx-0">
                        <div class="tab-content">
                            <div role="tabpanel" v-if="tab == 'payments'" >
                              <div class="table__Container">
                                <table class="table table-hover table-striped" >
                                    <thead  v-if="payment_list.length > 0">
                                    <tr>
                                        <th>Xidmət növü</th>
                                        <th>Ödəniş növü</th>
                                        <th>Paket</th>
                                        <th>Ödəniş</th>
                                        <th>Xidmət tarixi</th>
                                    </tr>
                                    </thead>
                                    <thead v-else class="thead-bottom">
                                    <tr>
                                        <th>Hal-hazırda heç bir məlumat yoxdur</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="payment in payment_list">
                                        <td>
                                            {{payment.service_name}}
                                        </td>
                                        <td>
                                            {{payment.payment_method}}
                                        </td>
                                        <td>
                                            {{payment.package_name}}
                                        </td>
                                        <td>
                                            {{payment.price}} Azn
                                        </td>
                                        <td>
                                            {{payment.services_duration}}
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                              </div>
                            </div>
                            <div role="tabpanel" v-if="tab == 'accounts'" >
                              <div class="table__Container">
                                <table class="table table-hover table-striped" >
                                    <thead  v-if="account_list.length > 0">
                                    <tr>
                                        <th>
                                            <label class="check-button">
                                                <input type="checkbox" v-model="selectAll" @change="selectAllAccounts()">
                                                <span class="checkmark"></span>
                                            </label>
                                        </th>
                                        <th>Bank adı</th>
                                        <th>Kart nömrəsi</th>
                                        <th>Kart növü</th>
                                        <th>Balans</th>
                                        <th>Status</th>
                                        <th>
                                            <button  class="btn-add"  @click="creatnewAccount()">
                                                <span><i class="fa fa-plus" aria-hidden="true"></i></span>YENİ
                                            </button>
                                        </th>
                                    </tr>
                                    </thead>
                                    <thead v-else class="thead-bottom">
                                    <tr>
                                        <th>Hal-hazırda heç bir məlumat yoxdur</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="item in account_list">
                                        <td>
                                            <label class="check-button">
                                                <input type="checkbox" :checked="selectAll" v-model="item.checked">
                                                <span class="checkmark"></span>
                                            </label>
                                        </td>
                                        <td>
                                            {{item.bank}}
                                        </td>
                                        <td>
                                            {{item.card_number}}
                                        </td>
                                        <td>
                                            {{item.type_name}}
                                        </td>
                                        <td>
                                            {{item.balance}}
                                        </td>
                                        <td><p :class="getClassByStatus(item.status)">{{item.status_name}}</p></td>
                                        <td>
                                            <button  class="btn btn-view"  @click="editnewAccount(item)">
                                                <span><i class="fa fa-pencil" aria-hidden="true"></i></span>Düzəliş et
                                            </button>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                              </div>
                            </div>
                            <div role="tabpanel" v-if="tab == 'expenses'" >
                              <div class="table__Container">
                                <table class="table table-hover table-striped" >
                                    <thead  v-if="expense_list.length > 0">
                                        <tr>
                                            <th>Bank adı</th>
                                            <th>Səbəb</th>
                                            <th>Qiymət</th>
                                            <th>Tarix</th>
                                            <th class="double-th">
                                                <span class="line_40">
                                                  Əməliyyat
                                                </span>
                                                <button  class="btn-add"  @click="creatnewExpense()">
                                                    <span><i class="fa fa-plus" aria-hidden="true"></i></span>YENİ
                                                </button>
                                            </th>
                                        </tr>
                                    </thead>
                                    <thead v-else class="thead-bottom">
                                        <tr>
                                            <th>Hal-hazırda heç bir məlumat yoxdur</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="item in expense_list">
                                        <td>
                                            {{item.bank}}
                                        </td>
                                        <td>
                                            {{item.reason}}
                                        </td>
                                        <td>
                                            {{item.price}}
                                        </td>
                                        <td>
                                            {{item.datetime}}
                                        </td>
                                        <td>
                                            {{item.action_name}}
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                              </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="stocks" v-if="tab == 'accounts'">
                    <div class="clinic-tariffs-edit" v-if="account_list.length > 0">
                        <div class="block">
                            <button type="button" class="btn" @click="deleteAccount()">
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
                            <h4 class="modal-title">Yeni hesab</h4>
                        </div>
                        <div class="modal-body new-addition">
                            <div class="general-info">
                                <div class="validate-block">
                                    <div class="input-group input-up">
                                        <label>
                                            <input type="text" placeholder=" " class="form-control inputText"  v-model="newAccount.bank">
                                            <span>Bankın adı</span>
                                        </label>
                                    </div>
                                    <span class="validation" v-if="errors.bank">{{errors.bank}}</span>
                                </div>
                            </div>
                            <div class="general-info">
                                <div class="input-group input-up">
                                    <label>
                                        <input type="number" placeholder=" " class="form-control inputText" v-model="newAccount.card_number">
                                        <span>Kartın nömrəsi</span>
                                    </label>
                                </div>
                            </div>
                            <div class="general-info">
                                <div class="validate-block">
                                    <div class="input-group input-up">
                                        <label>
                                            <input type="number" placeholder=" " class="form-control inputText"  v-model="newAccount.balance">
                                            <span>Balans</span>
                                        </label>
                                    </div>
                                    <span class="validation" v-if="errors.balance">{{errors.balance}}</span>
                                </div>
                            </div>
                            <div class="general-info">
                                <div class="validate-block">
                                    <div class="input-group">
                                        <multiselect v-model="newAccount.type"
                                                     label="name"
                                                     :internal-search="false"
                                                     :close-on-select="true"
                                                     :clear-on-select="false"
                                                     :hide-selected="false"
                                                     :options="card_types"
                                                     selectedLabel=""
                                                     deselectGroupLabel=""
                                                     deselectLabel=""
                                                     select-label=""
                                                     :reset-after="false"
                                                     :allow-empty="false">
                                        </multiselect>
                                    </div>
                                    <span class="validation" v-if="errors.type">{{errors.type}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="switch-part ">
                                <span>Status</span>
                                <label class="switch">
                                    <input type="checkbox" v-model="newAccount.status_n">
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
                                            <input type="text" placeholder=" " class="form-control inputText"  v-model="PostData.bank">
                                            <span>Bankın adı</span>
                                        </label>
                                    </div>
                                    <span class="validation" v-if="errors.bank">{{errors.bank}}</span>
                                </div>
                            </div>
                            <div class="general-info">
                                <div class="input-group input-up">
                                    <label>
                                        <input type="text" placeholder=" " class="form-control inputText"  v-model="PostData.card_number">
                                        <span>Kartın nömrəsi</span>
                                    </label>
                                </div>
                            </div>
                            <div class="general-info">
                                <div class="validate-block">
                                    <div class="input-group input-up">
                                        <label>
                                            <input type="number" placeholder=" " class="form-control inputText"  v-model="PostData.balance" :disabled="!!PostData.balance">
                                            <span>Balans</span>
                                        </label>
                                    </div>
                                    <span class="validation" v-if="errors.balance">{{errors.balance}}</span>
                                </div>
                            </div>
                            <div class="general-info">
                                <div class="validate-block">
                                    <div class="input-group">
                                        <multiselect v-model="PostData.type"
                                                     label="name"
                                                     :internal-search="false"
                                                     :close-on-select="true"
                                                     :clear-on-select="false"
                                                     :hide-selected="false"
                                                     :options="card_types"
                                                     selectedLabel=""
                                                     deselectGroupLabel=""
                                                     deselectLabel=""
                                                     select-label=""
                                                     :reset-after="false"
                                                     :allow-empty="false">
                                        </multiselect>
                                    </div>
                                    <span class="validation" v-if="errors.type">{{errors.type}}</span>
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
        <div class="modal-block"  v-if="fillExpense">
            <div class="modal fade in" id="myModal3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="fillExpense=false">
                            <span aria-hidden="true">
                                <img src="/admin/cp/img/popup-close.png" alt="popup-close">
                            </span>
                            </button>
                            <h4 class="modal-title">Əlavə et</h4>
                        </div>
                        <div class="modal-body new-addition">
                            <div class="general-info">
                                <div class="validate-block">
                                    <div class="input-group">
                                        <multiselect v-model="newExpense.account_id"
                                                     label="name"
                                                     :internal-search="false"
                                                     :close-on-select="true"
                                                     :clear-on-select="false"
                                                     :hide-selected="false"
                                                     :options="bank_accounts"
                                                     selectedLabel=""
                                                     deselectGroupLabel=""
                                                     deselectLabel=""
                                                     select-label=""
                                                     :reset-after="false"
                                                     :allow-empty="false">
                                        </multiselect>
                                        <!--<label>
                                            <input type="text" placeholder=" " class="form-control inputText"  v-model="newExpense.account_id">
                                            <span>Bankın hesabı</span>
                                        </label>-->
                                    </div>
                                    <span class="validation" v-if="errors.account_id">{{errors.account_id}}</span>
                                </div>
                            </div>
                            <div class="general-info">
                                <div class="validate-block">
                                    <div class="input-group input-up">
                                        <label>
                                            <input type="text" placeholder=" " class="form-control inputText" v-model="newExpense.reason">
                                            <span>Səbəb</span>
                                        </label>
                                    </div>
                                    <span class="validation" v-if="errors.reason">{{errors.reason}}</span>
                                </div>
                            </div>
                            <div class="general-info">
                                <div class="validate-block">
                                    <div class="input-group input-up">
                                        <label>
                                            <input type="number" placeholder=" " class="form-control inputText"  v-model="newExpense.price">
                                            <span>Qiymət</span>
                                        </label>
                                    </div>
                                    <span class="validation" v-if="errors.price">{{errors.price}}</span>
                                </div>
                            </div>
                            <div class="general-info">
                                <div class="validate-block">
                                    <div class="input-group">
                                        <multiselect v-model="newExpense.action"
                                                     label="name"
                                                     :internal-search="false"
                                                     :close-on-select="true"
                                                     :clear-on-select="false"
                                                     :hide-selected="false"
                                                     :options="action_types"
                                                     selectedLabel=""
                                                     deselectGroupLabel=""
                                                     deselectLabel=""
                                                     select-label=""
                                                     :reset-after="false"
                                                     :allow-empty="false">
                                        </multiselect>
                                    </div>
                                    <span class="validation" v-if="errors.action">{{errors.action}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn-add"  @click="submitExpense()">
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
    import Multiselect from 'vue-multiselect';
    import Search from '../Search';
    export default {
        name: "Account",
        components: {
            Multiselect, Search
        },
        data() {
            return {
                tab: 'payments',
                payment_list:[],
                account_list:[],
                expense_list:[],
                newAccount:{
                    bank:'',
                    card_number:'',
                    type: {name: 'Kartın növünü seçin'},
                    status_n:'',
                    balance:''
                },
                PostData: {
                    bank:'',
                    card_number:'',
                    type:'',
                    status_n:'',
                    balance:''
                },
                newExpense:{
                    account_id:{name: 'Bank hesabını seçin'},
                    reason:'',
                    price:'',
                    action: {name: 'Əməliyyat növünü seçin'},
                },
                errors: {
                    bank:false,
                    type:false,
                    balance:false,
                    account_id: false,
                    reason: false,
                    price: false,
                    action:false
                },

                status_name: '',
                status: '',
                fillSlider:false,
                editSlider:false,
                fillExpense:false,
                card_types:[],
                action_types:[],
                bank_accounts:[],
                selectAll:false,
                isnormalsearch: 0
            }
        },
        mounted (){
            this.getGeneralPayments();
            this.getGeneralAccounts();
            this.CardTypes();
            this.getGeneralExpenses();
            this.ActionTypes();
        },
        methods: {
            submitFile() {
                this.resetErrors();
                let formdata = new FormData();
                formdata.append('bank',this.newAccount.bank);
                formdata.append('card_number',this.newAccount.card_number);
                formdata.append('balance',this.newAccount.balance);
                formdata.append('type',this.newAccount.type.type);
                formdata.append('status',this.newAccount.status_n ? 1 : 0);
                axios.post('/bank-accounts/create', formdata).then(
                    res => {
                        if(res.data.status == 200) {
                            this.getGeneralAccounts();
                            this.fillSlider = false;
                        }
                        if(res.data.status != 200) {
                            this.errors=res.data.data
                        }
                    }).catch(error=> {
                        this.errors=error.response.data.data;
                        for(let i in this.errors) {
                            if(this.errors !== false) this.errors[i]=this.errors[i][0]
                        }
                })
            },
            editFile() {
                this.resetErrors();
                let formdata = new FormData();
                formdata.append('bank',this.PostData.bank);
                formdata.append('card_number',this.PostData.card_number);
                formdata.append('balance',this.PostData.balance);
                formdata.append('type',this.PostData.type.type);
                formdata.append('id',this.PostData.id);
                formdata.append('status',this.PostData.status_n ? 1 : 0);
                axios.post('/bank-accounts/edit', formdata).then(
                    res => {
                        if(res.data.status == 200) {
                            this.getGeneralAccounts();
                            this.editSlider = false;
                            this.PostData= {
                                bank:'',
                                card_number:'',
                                type:'',
                                status_n:'',
                                balance:''
                            }
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
            submitExpense() {
                this.resetErrors();
                let formdata = new FormData();
                formdata.append('account_id',this.newExpense.account_id.id || '');
                formdata.append('reason',this.newExpense.reason);
                formdata.append('price',this.newExpense.price);
                formdata.append('action',this.newExpense.action.value || '');
                axios.post('/operations/create', formdata).then(
                    res => {
                        if(res.data.status == 200) {
                            this.getGeneralExpenses();
                            this.fillExpense = false;
                        }
                        if(res.data.status != 200) {
                            this.errors=res.data.data
                        }
                    }).catch(error=> {
                    this.errors=error.response.data.data;
                    for(let i in this.errors) {
                        if(this.errors !== false) this.errors[i]=this.errors[i][0]
                    }
                })
            },
            resetErrors:function(){
                this.errors= {
                    bank:false,
                    type:false,
                    balance:false,
                    account_id: false,
                    reason: false,
                    price: false,
                    action:false
                }
            },
            creatnewAccount(){
                this.fillSlider = true;
            },

            creatnewExpense(){
                this.fillExpense = true;
            },

            editnewAccount(item){
                this.resetErrors();
                this.PostData = item;
                for(let i in this.card_types) {
                    if(this.card_types[i].type == this.PostData.type) {
                        this.PostData.type = this.card_types[i]
                    }
                }

                this.PostData.status_n = item.status == '1';
                this.editSlider = true;
            },

            getGeneralPayments(){
                axios.get('/accounting/cart-payments').then(
                    response=> {
                      if (response.data.data !== null) {
                        this.payment_list=response.data.data.list
                      }

                    }
                )
            },

            getGeneralAccounts() {
                axios.get('/bank-accounts').then(
                    response=> {
                        this.account_list=response.data.data.list;

                        for (let i in this.account_list) {
                            this.bank_accounts.push({
                                id:this.account_list[i].id,
                                name:this.account_list[i].bank + ' ' + this.account_list[i].card_number
                            })
                        }
                    }
                )
            },

            getGeneralExpenses(){
                axios.get('/operations').then(
                    response=> {
                        this.expense_list=response.data.data.list
                    }
                )
            },

            CardTypes (){
                axios.get('/bank-accounts/list-type').then(
                    response=> {
                        for (let i in response.data.data) {
                            this.card_types.push({
                                type:response.data.data[i].type,
                                name:response.data.data[i].name
                            })
                        }
                    }
                )
            },

            ActionTypes (){
                axios.get('/operations/list-actions').then(
                    response=> {
                        for (let i in response.data.data) {
                            this.action_types.push({
                                value:response.data.data[i].value,
                                name:response.data.data[i].name
                            })
                        }
                    }
                )
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

            selectAllAccounts() {
                for (let i in this.account_list) {
                    this.account_list[i].checked=this.selectAll
                }
            },

            deleteAccount:function () {
                let ids=[];

                for (let i in this.account_list) {
                    if(this.account_list[i].checked) {
                        ids.push(this.account_list[i].id)
                    }
                }

                if(ids.length >0){
                    axios.post('/bank-accounts/del',{ids}).then(
                        response=> {
                            this.getGeneralAccounts()
                        });
                }
            },

            search(query) {
            }

        }
    }
</script>

<style scoped>

</style>
