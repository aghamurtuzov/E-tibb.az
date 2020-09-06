<template>
    <div class="account blog">
        <div class="head-line">
            <p><span><i class="fa fa-money" aria-hidden="true"></i></span>MÜHASİBAT</p>
            <ol class="breadcrumb">
                <li><a href="index.html">Admin</a></li>
                <li class="active">Mühasibat</li>
            </ol>
        </div>
        <div class="row">
            <div class="col-md-12">
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
                                <input type="text" class="form-control" placeholder="Axtarış sözü" aria-describedby="basic-addon1">
                            </div>
                            <button type="submit" class="btn-effect">
                                <span><i class="fa fa-search" aria-hidden="true"></i></span>AXTAR
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="account-head">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="block active">
                                HESABLAR
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="block">
                                KARTA GERİ QAYTARILANLAR
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="block">
                                MÜŞTƏRİ BALANSI
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="block">
                                XƏRCLƏR
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="account-lists">
                    <div class="block">
                        <ul class="nav nav-tabs information-head" role="tablist">
                            <li role="presentation" class="active"><a href="#cards" aria-controls="cards" role="tab" data-toggle="tab">Kartlar</a></li>
                            <li role="presentation"><a href="#accounts" aria-controls="accounts" role="tab" data-toggle="tab">Hesablar</a></li>
                            <li role="presentation"><a href="#wallet" aria-controls="wallet" role="tab" data-toggle="tab">E-pulqabı</a></li>
                            <li role="presentation"><a href="#cash-register" aria-controls="cash-register" role="tab" data-toggle="tab">Kassa</a></li>
                        </ul>
                    </div>
                </div>
                <div class="information-blog">
                    <div class="block">
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active" id="cards">
                                <ul class="list-inline information-head">
                                    <li>
                                        <p>Hesab tipi</p>
                                    </li>
                                    <li>
                                        <p>Balans</p>
                                    </li>
                                </ul>
                                <ul class="list-inline information-body">
                                    <li>
                                        <p>Garanti Sanal kart</p>
                                    </li>
                                    <li>
                                        <p>0006</p>
                                    </li>
                                    <li>
                                        <a href="" class="btn btn-view">
                                            <span><i class="fa fa-eye" aria-hidden="true"></i></span>Bax
                                        </a>
                                    </li>
                                </ul>
                                <ul class="list-inline information-body bg-gray">
                                    <li>
                                        <p>Garanti Sanal kart</p>
                                    </li>
                                    <li>
                                        <p>0006</p>
                                    </li>
                                    <li>
                                        <a href="" class="btn btn-view">
                                            <span><i class="fa fa-eye" aria-hidden="true"></i></span>Bax
                                        </a>
                                    </li>
                                </ul>
                                <ul class="list-inline information-body">
                                    <li>
                                        <p>Garanti Sanal kart</p>
                                    </li>
                                    <li>
                                        <p>0006</p>
                                    </li>
                                    <li>
                                        <a href="" class="btn btn-view">
                                            <span><i class="fa fa-eye" aria-hidden="true"></i></span>Bax
                                        </a>
                                    </li>
                                </ul>
                                <ul class="list-inline information-body bg-gray">
                                    <li>
                                        <p>Garanti Sanal kart</p>
                                    </li>
                                    <li>
                                        <p>0006</p>
                                    </li>
                                    <li>
                                        <a href="" class="btn btn-view">
                                            <span><i class="fa fa-eye" aria-hidden="true"></i></span>Bax
                                        </a>
                                    </li>
                                </ul>
                                <ul class="list-inline information-body">
                                    <li>
                                        <p>Garanti Sanal kart</p>
                                    </li>
                                    <li>
                                        <p>0006</p>
                                    </li>
                                    <li>
                                        <a href="" class="btn btn-view">
                                            <span><i class="fa fa-eye" aria-hidden="true"></i></span>Bax
                                        </a>
                                    </li>
                                </ul>
                                <ul class="list-inline information-body bg-gray">
                                    <li>
                                        <p>Garanti Sanal kart</p>
                                    </li>
                                    <li>
                                        <p>0006</p>
                                    </li>
                                    <li>
                                        <a href="" class="btn btn-view">
                                            <span><i class="fa fa-eye" aria-hidden="true"></i></span>Bax
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="accounts">dvdcbvfcbfc</div>
                            <div role="tabpanel" class="tab-pane" id="wallet">dvdcbvfcbfc</div>
                            <div role="tabpanel" class="tab-pane" id="cash-register">dvdcbvfcbfc</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Account"
    }
</script>

<style scoped>

</style>