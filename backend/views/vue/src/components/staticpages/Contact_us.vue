<template>
    <div class="blog">
        <breadcrumb name="bİZİMLƏ ƏLAQƏ" icon="fa-file-o" :routes="[{'name':'Bizimlə əlaqə'}]"></breadcrumb>
        <div class="contact-block">
            <div class="block">
                <div class="row new-addition">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="general-info">
                                    <div class="input-group input-up first-input">
                                        <label>
                                            <input type="text" placeholder=" "  class="form-control inputText">
                                            <span>Ünvan</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="general-info">
                                    <div class="input-group input-up">
                                        <label>
                                            <input type="text" placeholder=" "  class="form-control inputText">
                                            <span>Telefon</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="general-info">
                                    <div class="input-group input-up">
                                        <label>
                                            <input type="text" placeholder=" "  class="form-control inputText">
                                            <span>Mobil</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="general-info">
                                    <div class="input-group input-up">
                                        <label>
                                            <input type="text" placeholder=" "  class="form-control inputText">
                                            <span>E-mail</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="general-info">
                                    <div class="input-group input-up">
                                        <label>
                                            <input type="text" placeholder=" "  class="form-control inputText">
                                            <span>İş saatları həftə içi</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="general-info">
                                    <div class="input-group input-up">
                                        <label>
                                            <input type="text" placeholder=" "  class="form-control inputText">
                                            <span>İş saatları şənbə</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <img src="/admin/cp/img/contactinner.png" class="center-block img-responsive" alt="contact">
                    </div>
                </div>
            </div>
        </div>
        <div class="cooperation-save">
            <div class="row">
                <div class="col-md-12">
                    <div class="block">
                        <button type="button" class="btn btn-orange">Yadda saxla</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Contact_us"
    }
</script>

<style scoped>

</style>