<template>
    <div class="static-block">
        <div class="row">
            <div class="col-md-3">
                <router-link to="/staticpages/About">
                    <div class="block">
                        <div class="static-info">
                            <img src="/admin/cp/img/about.png" alt="about">
                            <p>HAQQIMIZDA</p>
                        </div>
                    </div>
                </router-link>
            </div>
            <!--<div class="col-md-3">
                <router-link to="/staticpages/Rules">
                    <div class="block">
                        <div class="static-info">
                            <img src="/admin/cp/img/rules.png" alt="rules">
                            <p>QAYDALAR</p>
                        </div>
                    </div>
                </router-link>
            </div>
            <div class="col-md-3">
                <router-link to="/staticpages/Contact_us">
                    <div class="block">
                        <div class="static-info">
                            <img src="/admin/cp/img/contact.png" alt="contact">
                            <p>BİZİMLƏ ƏLAQƏ</p>
                        </div>
                    </div>
                </router-link>
            </div>-->
            <div class="col-md-3">
                <router-link to="/staticpages/Co_operation">
                    <div class="block">
                        <div class="static-info">
                            <img src="/admin/cp/img/partnership.png" alt="partnership">
                            <p>ƏMƏKDAŞLIQ</p>
                        </div>
                    </div>
                </router-link>
            </div>
           <!-- <div class="col-md-3">
                <router-link to="/staticpages/Blocked">
                    <div class="block">
                        <div class="static-info">
                            <img src="/admin/cp/img/banned.png" alt="banned">
                            <p>BLOK OLUNANLAR</p>
                        </div>
                    </div>
                </router-link>
            </div>-->
        </div>
    </div>
</template>

<script>
    export default {
        name: "StaticPages"
    }
</script>

<style scoped>

</style>