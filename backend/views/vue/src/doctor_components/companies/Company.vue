<template>
    <div class="company-inner blog">
        <div class="head-line">
            <p><span><i class="fa fa-bullhorn" aria-hidden="true"></i></span>KAMPANİYALAR</p>
            <ol class="breadcrumb">
                <li><a href="index.html">Admin</a></li>
                <li class="active">Kampaniyalar</li>
            </ol>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="blog-file-upload">
                    <div class="block">
                        <div class="upload-img relative">
                            <button class="delete-img"></button>
                            <img src="img/company1.png" alt="company-upload" class="img-responsive">
                        </div>
                        <div class="upload-body">
                            <form>
                                <div class="file-upload-wrapper" data-text="">
                                    <div class="input-group input-up">
                                        <label>
                                            <input type="text" placeholder=" " class="form-control inputText">
                                            <span>Başlıq şəkli</span>
                                        </label>
                                    </div>
                                    <div class="upload-file-block">
                                        <label class="custom-file-upload">
                                            <span>Fayl yüklə</span>
                                        </label>
                                        <input name="file-upload-field" type="file" class="file-upload-field" value="" id="file-upload">
                                    </div>
                                </div>
                            </form>
                            <div class="date-hour-add">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group input-up">
                                            <div class="upload-date">
                                                <label>
                                                    <input type="text" placeholder=" " class="form-control inputText">
                                                    <span>Başlanğıc tarixi</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group input-up">
                                            <div class="upload-hour">
                                                <label>
                                                    <input type="text" placeholder=" " class="form-control inputText">
                                                    <span>Bitiş tarixi</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="cost-sale">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group input-up">
                                            <div class="upload-hour">
                                                <label>
                                                    <input type="text" placeholder=" " class="form-control inputText">
                                                    <span>Qiymət</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group input-up">
                                            <div class="upload-hour">
                                                <label>
                                                    <input type="text" placeholder=" " class="form-control inputText">
                                                    <span>Endirim %</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="upload-file-content">
                    <div class="block">
                        <div class="input-group input-up">
                            <label>
                                <input type="text" placeholder=" " class="form-control inputText">
                                <span>Başlıq şəkli</span>
                            </label>
                        </div>
                        <div class="upload-article">
                            <p>Məqalə</p>
                            <form action="">
                                            <textarea name="article" id="article" cols="30" rows="12" class="form-control">
                                            Dünya Göz Klinikasında, World POS vasitəsilə, Worldcard-la edilən ödənişlərdə, 12 aya qədər, hissəli öidəniş imkanı mövcuddur.

 Ətraflı məlumat üçün ; *0444-ə zəng edə bilər və ya filiallarımıza yaxınlaşa bilərsiniz.

Kampaniya şərtləri
Kampaniya Worldcard-la Dünya Göz Klinikasında Yapı Kredi POS terminallarında edilən hissəli ödəmələrdə keçərlidir. Pulkart və Virtual kart kampaniyaya daxil deyildir. Həmçinin ballar ilə edilən alış-verişlər də kampaniyaya daxil deyildir. World Üzv Ticarət Obyektlərində kampaniya məlumatlarında meydana gələbiləcək səhvlərə görə Yapı Kredi cavabdehlik daşımır. Kampaniya haqqında ətraflı məlumatı www.worldcard.com.az saytından və *0444 Yapı Kredi Telefon Mərkəzindən öyrənə bilərsiniz.
                                            </textarea>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="edit-post">
                    <div class="block">
                        <div class="switch-part ">
                            <span>Gizlə</span>
                            <label class="switch">
                                <input type="checkbox">
                                <span class="slider round"></span>
                            </label>
                            <span>Yayımla</span>
                        </div>
                        <div class="editor-buttons">
                            <button type="button" class="delete-post">
                                SİL
                            </button>
                            <button type="button" class="share-post">
                                PAYLAŞ
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Company"
    }
</script>

<style scoped>

</style>