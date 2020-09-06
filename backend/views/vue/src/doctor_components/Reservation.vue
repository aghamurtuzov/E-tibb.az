<template>
    <div class="reservation blog">
        <div class="head-line">
            <p><img src="img/reservation-icon.png" alt="reservation-icon">REZERVASİYA</p>
            <ol class="breadcrumb">
                <li><a href="index.html">Admin</a></li>
                <li class="active">Rezervasiya</li>
            </ol>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="search">
                    <div class="block">
                        <select class="form-control selectpicker" name="money" title="Axtarış növünü seçin">
                            <option value="">Qeydiyyat nömrəsinə görə</option>
                            <option value="">Klinikaya görə</option>
                            <option value="">Həkimə görə</option>
                            <option value="">Pasient adına görə</option>
                            <option value="">Saat aralığına görə</option>
                            <option value="">Tarixə görə</option>
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
                <div class="information-blog">
                    <div class="block">
                        <ul class="list-inline information-head">
                            <li>
                                <p>Həkimin Adı, Soyadı,<p>
                                <p>  Qeydiyyat nömrəsi</p>
                            </li>
                            <li>
                                <p>İxtisas</p>
                            </li>
                            <li>
                                <p>Klinika</p>
                            </li>
                            <li>
                                <p>Tarix</p>
                            </li>
                            <li>
                                <p>Saat</p>
                            </li>
                            <li>
                                <p>Ödəniş növü</p>
                            </li>
                            <li>
                                <p>Rezerv edənin Adı, Soyadı,</p>
                                <p>Qeydiyyat nömrəsi</p>
                            </li>
                            <li>
                                <button type="button" class="btn-add">
                                    <span><i class="fa fa-plus" aria-hidden="true"></i></span>YENİ
                                </button>
                            </li>
                        </ul>
                        <ul class="list-inline information-body">
                            <li>
                                <p>Nigar Gasimli</p>
                                <p>0006</p>
                            </li>
                            <li>
                                <p>Allerqoloq</p>
                            </li>
                            <li>
                                <p>New med</p>
                            </li>
                            <li>
                                <p>02.07.2019</p>
                            </li>
                            <li>
                                <p>09:00 - 09:30</p>
                            </li>
                            <li>
                                <p>Kartla</p>
                            </li>
                            <li>
                                <p>Valeh Məmmədov</p>
                                <p>21265</p>
                            </li>
                            <li>
                                <a href="" class="btn btn-view">
                                    <span><i class="fa fa-eye" aria-hidden="true"></i></span>Bax
                                </a>
                            </li>
                        </ul>
                        <ul class="list-inline information-body bg-gray">
                            <li>
                                <p>Zərifə Məmmədova</p>
                                <p>0005</p>
                            </li>
                            <li>
                                <p>Androloq</p>
                            </li>
                            <li>
                                <p>Medical plaza</p>
                            </li>
                            <li>
                                <p>01.07.2019</p>
                            </li>
                            <li>
                                <p>11:30 - 12:00</p>
                            </li>
                            <li>
                                <p>Nəğd</p>
                            </li>
                            <li>
                                <p>Gültəkin Əhmədova</p>
                                <p>67853</p>
                            </li>
                            <li>
                                <a href="" class="btn btn-view">
                                    <span><i class="fa fa-eye" aria-hidden="true"></i></span>Bax
                                </a>
                            </li>
                        </ul>
                        <ul class="list-inline information-body">
                            <li>
                                <p>Nigar Gasimli</p>
                                <p>0006</p>
                            </li>
                            <li>
                                <p>Allerqoloq</p>
                            </li>
                            <li>
                                <p>New med</p>
                            </li>
                            <li>
                                <p>02.07.2019</p>
                            </li>
                            <li>
                                <p>09:00 - 09:30</p>
                            </li>
                            <li>
                                <p>Kartla</p>
                            </li>
                            <li>
                                <p>Valeh Məmmədov</p>
                                <p>21265</p>
                            </li>
                            <li>
                                <a href="" class="btn btn-view">
                                    <span><i class="fa fa-eye" aria-hidden="true"></i></span>Bax
                                </a>
                            </li>
                        </ul>
                        <ul class="list-inline information-body bg-gray">
                            <li>
                                <p>Nigar Gasimli</p>
                                <p>0006</p>
                            </li>
                            <li>
                                <p>Allerqoloq</p>
                            </li>
                            <li>
                                <p>New med</p>
                            </li>
                            <li>
                                <p>02.07.2019</p>
                            </li>
                            <li>
                                <p>09:00 - 09:30</p>
                            </li>
                            <li>
                                <p>Kartla</p>
                            </li>
                            <li>
                                <p>Valeh Məmmədov</p>
                                <p>21265</p>
                            </li>
                            <li>
                                <a href="" class="btn btn-view">
                                    <span><i class="fa fa-eye" aria-hidden="true"></i></span>Bax
                                </a>
                            </li>
                        </ul>
                        <ul class="list-inline information-body">
                            <li>
                                <p>Nigar Gasimli</p>
                                <p>0006</p>
                            </li>
                            <li>
                                <p>Allerqoloq</p>
                            </li>
                            <li>
                                <p>New med</p>
                            </li>
                            <li>
                                <p>02.07.2019</p>
                            </li>
                            <li>
                                <p>09:00 - 09:30</p>
                            </li>
                            <li>
                                <p>Kartla</p>
                            </li>
                            <li>
                                <p>Valeh Məmmədov</p>
                                <p>21265</p>
                            </li>
                            <li>
                                <a href="" class="btn btn-view">
                                    <span><i class="fa fa-eye" aria-hidden="true"></i></span>Bax
                                </a>
                            </li>
                        </ul>
                        <ul class="list-inline information-body bg-gray">
                            <li>
                                <p>Nigar Gasimli</p>
                                <p>0006</p>
                            </li>
                            <li>
                                <p>Allerqoloq</p>
                            </li>
                            <li>
                                <p>New med</p>
                            </li>
                            <li>
                                <p>02.07.2019</p>
                            </li>
                            <li>
                                <p>09:00 - 09:30</p>
                            </li>
                            <li>
                                <p>Kartla</p>
                            </li>
                            <li>
                                <p>Valeh Məmmədov</p>
                                <p>21265</p>
                            </li>
                            <li>
                                <a href="" class="btn btn-view">
                                    <span><i class="fa fa-eye" aria-hidden="true"></i></span>Bax
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Reservation"
    }
</script>

<style scoped>

</style>