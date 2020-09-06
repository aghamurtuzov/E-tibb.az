<template>
    <div class="user-inner clinic-inner blog">
        <div class="head-line">
            <p><span><i class="fa fa-user-o" aria-hidden="true"></i></span>İstİfadəçİ</p>
            <ol class="breadcrumb">
                <li><a href="index.html">Admin</a></li>
                <li class="active">İstifadəçi</li>
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
                <div class="row">
                    <div class="col-md-9">
                        <div class="clinic-information">
                            <div class="block">
                                <div class="logo-login">
                                    <div class="clinic-basics">
                                        <div class="name">
                                            <p>Adı</p>
                                            <h3>Nigar Gasimli</h3>
                                        </div>
                                    </div>
                                    <p>Qeydiyyat nömrəsi
                                        <span>0006</span>
                                    </p>
                                </div>
                                <address>
                                    <ul class="list-inline">
                                        <li>
                                            <p>E-mail</p>
                                            <span>xataiestetik@gmail.com</span>
                                        </li>
                                        <li>
                                            <p>Mobil telefon</p>
                                            <span>+994 50 123 45 67</span>
                                        </li>
                                        <li>
                                            <p>Ünvan</p>
                                            <span>Baki̇ şəhəri̇ Xətai̇ rayonu, S. Vezi̇rov 11/2</span>
                                        </li>
                                    </ul>
                                </address>
                                <div class="clinic-edit">
                                    <button class="btn btn-blocked">
                                        BLOCK ET
                                    </button>
                                    <button class="btn btn-edit">
                                        DÜZƏLİŞ et
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="clinic-notify">
                            <div class="block">
                                <p>
                                    Bİldİrİş göndər
                                </p>
                                <div class="input-group">
                                    <textarea name="notify" id="notify" cols="30" rows="11" placeholder="Bildiriş mətni"  class="form-control"></textarea>
                                </div>
                                <button type="submit" class="btn btn-email">E-mail İlə</button>
                                <button type="submit" class="btn btn-sms">SMS İlə</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="tariffs-stocks">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="clinic-tariffs">
                                <div class="block">
                                    AKSİYALAR
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="information-blog">
                                <div class="block">
                                    <ul class="list-inline information-head">
                                        <li>
                                            <label class="check-button">
                                                <input type="checkbox" >
                                                <span class="checkmark"></span>
                                            </label>
                                        </li>
                                        <li>
                                            <p>Xidmət növü </p>
                                        </li>
                                        <li>
                                            <p>Ödəniş</p>
                                        </li>
                                        <li>
                                            <p>Paket</p>
                                        </li>
                                        <li>
                                            <p>Xidmət</p>
                                        </li>
                                        <li>
                                            <p>Xidmət tarixi</p>
                                        </li>
                                        <li>
                                            <button type="button" class="btn-add">
                                                <span><i class="fa fa-plus" aria-hidden="true"></i></span>YENİ
                                            </button>
                                        </li>
                                    </ul>
                                    <ul class="list-inline information-body">
                                        <li>
                                            <label class="check-button">
                                                <input type="checkbox" >
                                                <span class="checkmark"></span>
                                            </label>
                                        </li>
                                        <li>
                                            <p>Valeh Əliyev</p>
                                        </li>
                                        <li>
                                            <p>Nəğd</p>
                                        </li>
                                        <li>
                                            <p>1 Aylıq</p>
                                        </li>
                                        <li>
                                            <p>Premium</p>
                                        </li>
                                        <li>
                                            <p>15.05.2019 - 15.06.2019</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="clinic-tariffs-edit">
                                <div class="block">
                                    <button type="button" class="btn">
                                        Sİl
                                    </button>
                                    <button type="button" class="btn">
                                        YeNİLƏ
                                    </button>
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
    export default {
        name: "User"
    }
</script>

<style scoped>

</style>