<template>
    <div class="clinic-new new-addition blog">
        <div class="head-line">
            <p><span><i class="fa fa-hospital-o" aria-hidden="true"></i></span>Yenİ klİnİka</p>
            <ol class="breadcrumb">
                <li><a href="index.html">Admin</a></li>
                <li><a href="clinics.html">Klinikalar</a></li>
                <li class="active">Yeni klinika</li>
            </ol>
        </div>
        <div class="row">
            <div class="col-md-7">
                <div class="addition-info">
                    <div class="block">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="add-img-info">
                                    <form>
                                        <div class="file-upload-wrapper" data-text="">
                                            <label class="custom-file-upload">
                                                <span><i class="fa fa-camera" aria-hidden="true"></i></span>
                                            </label>
                                            <input name="file-upload-field" type="file" class="file-upload-field" value="" id="file-upload">
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="general-info">
                                    <p class="info">İnfo</p>
                                    <div class="input-group input-up">
                                        <label>
                                            <input type="text" placeholder=" " class="form-control inputText">
                                            <span>Şirkətin adı*</span>
                                        </label>
                                    </div>
                                    <div class="input-group input-up">
                                        <label>
                                            <input type="text" placeholder=" " class="form-control inputText">
                                            <span>Şirkət E-maili*</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="addition-workhours">
                    <div class="block">
                        <p class="info">İş günləri və saatları</p>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="general-info">
                                    <div class="input-group input-up">
                                        <label>
                                            <input type="text" placeholder=" " class="form-control inputText">
                                            <span>B.e - Cümə</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="general-info">
                                    <div class="input-group input-up">
                                        <label>
                                            <input type="text" placeholder=" " class="form-control inputText">
                                            <span>Şənbə</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="general-info">
                                    <div class="input-group input-up">
                                        <label>
                                            <input type="text" placeholder=" " class="form-control inputText">
                                            <span>Bazar</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="addition-user">
                    <div class="block">
                        <p class="info">Əlaqələndİrİcİ şəxs</p>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="general-info">
                                    <div class="input-group input-up">
                                        <label>
                                            <input type="text" placeholder=" " class="form-control inputText">
                                            <span>Əlaqələndirici şəxs*</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="general-info">
                                    <div class="input-group input-up">
                                        <label>
                                            <input type="text" placeholder=" " class="form-control inputText">
                                            <span>Əlaqələndirici şəxsin E-maili*</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="general-info">
                                    <div class="input-group input-up">
                                        <label>
                                            <input type="text" placeholder=" " class="form-control inputText">
                                            <span>Əlaqələndirici şəxsin telefonu</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="general-info">
                                    <div class="input-group input-up">
                                        <label>
                                            <input type="text" placeholder=" " class="form-control inputText">
                                            <span>Əlaqələndirici şəxsin mobil telefonu*</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="addition-contact">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="block">
                                <p class="info">ŞİRKƏT TELEFONU</p>
                                <div class="general-info">
                                    <div class="input-group input-up">
                                        <label>
                                            <input type="text" placeholder=" " class="form-control inputText">
                                            <span>Şirkət telefonu*</span>
                                        </label>
                                    </div>
                                    <button type="button" class="btn btn-addition"><span>-</span></button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="block">
                                <p class="info">ŞİrkətİN Mobİl telefonu</p>
                                <div class="general-info">
                                    <div class="input-group input-up">
                                        <label>
                                            <input type="text" placeholder=" " class="form-control inputText">
                                            <span>Şirkət Mobil telefonu*</span>
                                        </label>
                                    </div>
                                    <button type="button" class="btn btn-addition"><span>+</span></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="addition-address">
                    <div class="block">
                        <p class="info">ŞİRKƏT ÜNVANI</p>
                        <div class="general-info">
                            <div class="input-group input-up">
                                <label>
                                    <input type="text" placeholder=" " class="form-control inputText">
                                    <span>Şirkət ünvanı*</span>
                                </label>
                            </div>
                            <button type="button" class="btn btn-addition"><span><i class="fa fa-plus" aria-hidden="true"></i></span></button>
                        </div>
                    </div>
                </div>
                <div class="addition-alternatives">
                    <div class="block">
                        <div class="check-list">
                            <label class="check-button">
                                <span>Evə çağırış</span>
                                <input type="checkbox" >
                                <span class="checkmark"></span>
                            </label>
                            <label class="check-button">
                                <span>Uşaq həkimi</span>
                                <input type="checkbox" >
                                <span class="checkmark"></span>
                            </label>
                            <label class="check-button">
                                <span>24 Saat</span>
                                <input type="checkbox" >
                                <span class="checkmark"></span>
                            </label>
                        </div>
                        <button type="button" class="btn">ƏLAVƏ ET</button>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="bio-diplom">
                    <div class="block">
                        <div class="biography">
                            <p class="info">Şİrkət haqqında</p>
                            <div class="input-group">
                                            <textarea name="" id="" cols="30" rows="5" class="form-control">2001-2007-ci illərdə Azərbaycan Tibb Universitetinin Müalicə-profilaktika fakültəsini fərqlənmə diplomu ilə bitirib.     2007-2008-ci illərdə Musa Nağıyev adına Təcili Tibbi Yardım Xəstəxanasında ümumi cərrahiyyə ixtisası üzrə internatura keçib.
                                            </textarea>
                            </div>
                        </div>
                        <div class="certificate">
                            <p class="info">DİpLOM və sertİFİKATLAR</p>
                            <div class="certificate-list">
                                <div class="certificate-images">
                                    <img src="img/certificate.png" alt="certificate">
                                    <img src="img/certificate.png" alt="certificate">
                                    <img src="img/certificate.png" alt="certificate">
                                    <img src="img/certificate.png" alt="certificate">
                                    <img src="img/certificate.png" alt="certificate">
                                    <img src="img/certificate.png" alt="certificate">
                                    <img src="img/certificate.png" alt="certificate">
                                    <img src="img/certificate.png" alt="certificate">
                                </div>
                                <form>
                                    <div class="file-upload-wrapper" data-text="">
                                        <label class="custom-file-upload">
                                            <span><i class="fa fa-plus" aria-hidden="true"></i></span>
                                        </label>
                                        <input name="file-upload-field" type="file" class="file-upload-field" value="" id="file-upload1">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="social-block">
                    <div class="block">
                        <p class="info">Sosİal şəbəkələr</p>
                        <div class="general-info">
                            <div class="input-group input-up">
                                <label>
                                    <input type="text" placeholder=" " class="form-control inputText">
                                    <span class="facebook">Facebook</span>
                                </label>
                            </div>
                            <div class="input-group input-up">
                                <label>
                                    <input type="text" placeholder=" " class="form-control inputText">
                                    <span class="instagram">Instagram</span>
                                </label>
                            </div>
                            <div class="input-group input-up">
                                <label>
                                    <input type="text" placeholder=" " class="form-control inputText">
                                    <span class="twitter">Twitter</span>
                                </label>
                            </div>
                            <div class="input-group input-up">
                                <label>
                                    <input type="text" placeholder=" " class="form-control inputText">
                                    <span class="linkedIn">Linkedin</span>
                                </label>
                            </div>
                            <div class="input-group input-up">
                                <label>
                                    <input type="text" placeholder=" " class="form-control inputText">
                                    <span class="skype">Skype</span>
                                </label>
                            </div>
                            <div class="input-group input-up">
                                <label>
                                    <input type="text" placeholder=" " class="form-control inputText">
                                    <span class="whatsapp">Whatsapp</span>
                                </label>
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
        name: "newClinic"
    }
</script>

<style scoped>

</style>
