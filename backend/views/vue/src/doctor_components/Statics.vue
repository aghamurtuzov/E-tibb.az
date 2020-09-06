<template>
    <div class="statics blog">
        <div class="head-line">
            <p><span><i class="fa fa-file-o" aria-hidden="true"></i></span>Statİstİka</p>
            <ol class="breadcrumb">
                <li><a href="index.html">Admin</a></li>
                <li class="active">Statistika</li>
            </ol>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="static-blocks">
                    <div class="block">
                        HƏKİMLƏR
                    </div>
                    <div class="block">
                        KLİNİKALAR
                    </div>
                    <div class="block">
                        APTEKLƏR
                    </div>
                    <div class="block">
                        AKSİYALAR
                    </div>
                    <div class="block">
                        REZERVASİYA
                    </div>
                    <div class="block active">
                        ÖDƏNİŞLƏR
                    </div>
                    <div class="block">
                        PAKETLƏRİN
                        alışı
                    </div>
                    <div class="block">
                        BLOQLAR
                    </div>
                    <div class="block">
                        SUALLAR
                    </div>
                    <div class="block">
                        RƏYLƏR
                    </div>
                    <div class="block">
                        TOPLU
                        ÖDƏNİŞLƏR
                    </div>
                    <div class="block">
                        CANLI
                        DANIŞIQ
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-3">
                        <div class="filter-date">
                            <div class="block">
                                <form action="">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="-- / -- / --">
                                    </div>
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="-- / -- / --">
                                    </div>
                                    <button class="btn" type="submit">Axtar</button>
                                </form>
                            </div>
                            <div class="filter-list">
                                <div class="block">
                                    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                        <div class="panel panel-default">
                                            <div class="panel-heading" role="tab" id="headingOne">
                                                <h4 class="panel-title">
                                                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                        wo type
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                                                <div class="panel-body">
                                                    <ul class="list-unstyled">
                                                        <li>
                                                            <label class="check-button">
                                                                <span>Withouts pervisor</span>
                                                                <input type="checkbox" >
                                                                <span class="checkmark"></span>
                                                            </label>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="filter-list">
                                <div class="block">
                                    <div class="panel-group" id="accordion2" role="tablist" aria-multiselectable="true">
                                        <div class="panel panel-default">
                                            <div class="panel-heading" role="tab" id="headingOne1">
                                                <h4 class="panel-title">
                                                    <a role="button" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne1" aria-expanded="true" aria-controls="collapseOne">
                                                        asset
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="collapseOne1" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                                                <div class="panel-body">
                                                    <ul class="list-unstyled">
                                                        <li>
                                                            <label class="check-button">
                                                                <span>Asset 1 (Name 1)</span>
                                                                <input type="checkbox" >
                                                                <span class="checkmark"></span>
                                                            </label>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="filter-list">
                                <div class="block">
                                    <div class="panel-group" id="accordion3" role="tablist" aria-multiselectable="true">
                                        <div class="panel panel-default">
                                            <div class="panel-heading" role="tab" id="headingOne3">
                                                <h4 class="panel-title">
                                                    <a role="button" data-toggle="collapse" data-parent="#accordion3" href="#collapseOne3" aria-expanded="true" aria-controls="collapseOne">
                                                        İCRAÇI ŞƏXSLƏR
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="collapseOne3" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                                                <div class="panel-body">
                                                    <ul class="list-unstyled">
                                                        <li>
                                                            <label class="check-button">
                                                                <span>Name Surname</span>
                                                                <input type="checkbox" >
                                                                <span class="checkmark"></span>
                                                            </label>
                                                        </li>
                                                        <li>
                                                            <label class="check-button">
                                                                <span>Supervisor supervisor</span>
                                                                <input type="checkbox" >
                                                                <span class="checkmark"></span>
                                                            </label>
                                                        </li>
                                                        <li>
                                                            <label class="check-button">
                                                                <span>Dispatcher dispatcher</span>
                                                                <input type="checkbox" >
                                                                <span class="checkmark"></span>
                                                            </label>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="list-block">
                            <div class="tab-buttons">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li role="presentation" class="active">
                                        <a href="#doctor" aria-controls="doctor" role="tab" data-toggle="tab" class="btn active">
                                            Həkimlər
                                        </a>
                                    </li>
                                    <li role="clinics">
                                        <a href="#clinics" aria-controls="clinics" role="tab" data-toggle="tab" class="btn ">
                                            Klinikalar
                                        </a>
                                    </li>
                                    <li role="presentation">
                                        <a href="#stocks" aria-controls="stocks" role="tab" data-toggle="tab" class="btn ">
                                            Aksiyalar
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane active" id="doctor">
                                    <div class="information-blog for-doctors">
                                        <div class="block">
                                            <ul class="list-inline information-head">
                                                <li>
                                                    <p>Adı, soyadı</p>
                                                </li>
                                                <li>
                                                    <p>Qeydiyyat №</p>
                                                </li>
                                                <li>
                                                    <p>E-mail</p>
                                                </li>
                                                <li>
                                                    <p>Telefon</p>
                                                </li>
                                            </ul>
                                            <ul class="list-inline information-body">
                                                <li>
                                                    <p>Fatimə Əhmədova</p>
                                                </li>
                                                <li>
                                                    <p>1456</p>
                                                </li>
                                                <li>
                                                    <p>n.gasimli@gmail.com</p>
                                                </li>
                                                <li>
                                                    <p class="date">+994 55 123 45 67</p>
                                                </li>
                                            </ul>
                                            <ul class="list-inline information-body bg-gray">
                                                <li>
                                                    <p>Fatimə Əhmədova</p>
                                                </li>
                                                <li>
                                                    <p>1456</p>
                                                </li>
                                                <li>
                                                    <p>n.gasimli@gmail.com</p>
                                                </li>
                                                <li>
                                                    <p class="date">+994 55 123 45 67</p>
                                                </li>
                                            </ul>
                                            <ul class="list-inline information-body">
                                                <li>
                                                    <p>Fatimə Əhmədova</p>
                                                </li>
                                                <li>
                                                    <p>1456</p>
                                                </li>
                                                <li>
                                                    <p>n.gasimli@gmail.com</p>
                                                </li>
                                                <li>
                                                    <p class="date">+994 55 123 45 67</p>
                                                </li>
                                            </ul>
                                            <ul class="list-inline information-body bg-gray">
                                                <li>
                                                    <p>Fatimə Əhmədova</p>
                                                </li>
                                                <li>
                                                    <p>1456</p>
                                                </li>
                                                <li>
                                                    <p>n.gasimli@gmail.com</p>
                                                </li>
                                                <li>
                                                    <p class="date">+994 55 123 45 67</p>
                                                </li>
                                            </ul>
                                            <ul class="list-inline information-body">
                                                <li>
                                                    <p>Fatimə Əhmədova</p>
                                                </li>
                                                <li>
                                                    <p>1456</p>
                                                </li>
                                                <li>
                                                    <p>n.gasimli@gmail.com</p>
                                                </li>
                                                <li>
                                                    <p class="date">+994 55 123 45 67</p>
                                                </li>
                                            </ul>
                                            <ul class="list-inline information-body bg-gray">
                                                <li>
                                                    <p>Fatimə Əhmədova</p>
                                                </li>
                                                <li>
                                                    <p>1456</p>
                                                </li>
                                                <li>
                                                    <p>n.gasimli@gmail.com</p>
                                                </li>
                                                <li>
                                                    <p class="date">+994 55 123 45 67</p>
                                                </li>
                                            </ul>
                                            <nav aria-label="Page navigation" class="text-right">
                                                <ul class="pagination">
                                                    <!--<li>
                                                        <a href="#" aria-label="Previous">
                                                            <span aria-hidden="true">&laquo;</span>
                                                        </a>
                                                    </li>-->
                                                    <li class="active"><a href="#">1</a></li>
                                                    <li><a href="#">2</a></li>
                                                    <li><a href="#">3</a></li>
                                                    <li><a href="#">4</a></li>
                                                    <li><a href="#">5</a></li>
                                                    <li>
                                                        <a href="#" aria-label="Next">
                                                            <span aria-hidden="true">NÖVBƏTİ</span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </nav>
                                        </div>
                                    </div>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="clinics">dvdcbvfcbfc</div>
                                <div role="tabpanel" class="tab-pane" id="stocks">dvdcbvgvdgfcbfc</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="more-details">
                            <div class="block">
                                <!-- Nav tabs -->
                                <ul class="nav nav-tabs" role="tablist">
                                    <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Ətraflı</a></li>
                                    <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Tarixçə</a></li>
                                </ul>
                                <!-- Tab panes -->
                                <div class="tab-content">
                                    <div role="tabpanel" class="tab-pane active" id="home">
                                        <ul class="list-unstyled">
                                            <li>
                                                <p>Created date/time</p>
                                                <span>2019-02-25  21:00</span>
                                            </li>
                                            <li>
                                                <p>Created by</p>
                                                <span>executor</span>
                                            </li>
                                            <li>
                                                <p>Work order type</p>
                                                <span>Without services</span>
                                            </li>
                                            <li>
                                                <p>Work order number</p>
                                                <span>84483911</span>
                                            </li>
                                            <li>
                                                <p>Requested by</p>
                                                <span>Superviser</span>
                                            </li>
                                        </ul>
                                    </div>
                                    <div role="tabpanel" class="tab-pane" id="profile">dvdcbvfcbfc</div>
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
        name: "Statics"
    }
</script>

<style scoped>

</style>