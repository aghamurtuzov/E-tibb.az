<template>
    <div class="admin-block pl-0 pr-0">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-4 mb-30">
                <router-link to="/doctors">
                    <div class="block">
                        <div class="admin-heading">
                            <p>HƏKİMLƏR</p>
                        </div>
                        <div class="admin-count">
                            <p>{{Dashboard.hekimler}}</p>
                            <img src="/admin/cp/img/doctor-img.png" alt="doctor-img" class="img-responsive">
                        </div>
                        <!--<p class="counting">Son əlavə sayı: <span>1</span></p>-->
                    </div>
                </router-link>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-4 mb-30">
                <router-link to="/stocks">
                    <div class="block">
                        <div class="admin-heading">
                            <p>AKSİYALAR</p>
                        </div>
                        <div class="admin-count">
                            <p>{{Dashboard.aksiyalar}}</p>
                            <img src="/admin/cp/img/megaphone.png" alt="megaphone" class="img-responsive">
                        </div>
                        <!--<p class="counting">Son əlavə sayı: <span>2</span></p>-->
                    </div>
                </router-link>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-4 mb-30">
                <router-link to="/review">
                    <div class="block">
                        <div class="admin-heading">
                            <p>RƏYLƏR</p>
                            <!--<span class="badge">01</span>-->
                        </div>
                        <div class="admin-count">
                            <p>{{Dashboard.reyler}}</p>
                            <img src="/admin/cp/img/review.png" alt="review" class="img-responsive">
                        </div>
                        <!--<p class="counting">Son əlavə sayı: <span>6</span></p>-->
                    </div>
                </router-link>
            </div>
            <!--<div class="col-lg-3 col-md-3">
                <router-link to="/reservations">
                    <div class="block">
                        <div class="admin-heading">
                            <p>REZERVASİYA</p>
                            &lt;!&ndash;<span class="badge">01</span>&ndash;&gt;
                        </div>
                        <div class="admin-count">
                            <p>{{Dashboard.rezervasiya}}</p>
                            <img src="/admin/cp/img/medical-history.png" alt="medical-history" class="img-responsive">
                        </div>
                        &lt;!&ndash;<p class="counting">Son əlavə sayı: <span>2</span></p>&ndash;&gt;
                    </div>
                </router-link>
            </div>-->
            <!--<div class="col-lg-3 col-md-3">
                <div class="block">
                    <div class="admin-heading">
                        <p>Ümumİ balans</p>
                    </div>
                    <div class="admin-count">
                        <p>{{Dashboard.balans}} <sup>M</sup></p>
                        <img src="/admin/cp/img/wallet.png" alt="letters" class="img-responsive">
                    </div>
                    &lt;!&ndash;<p class="counting">Son əlavə sayı: <span>1</span></p>&ndash;&gt;
                </div>
            </div>-->
            <div class="col-lg-3 col-md-3 col-sm-4 mb-30">
                <router-link to="/blogs">
                    <div class="block">
                        <div class="admin-heading">
                            <p>BLOQLAR</p>
                        </div>
                        <div class="admin-count">
                            <p>{{Dashboard.bloqlar}}</p>
                            <img src="/admin/cp/img/blog.png" alt="blog" class="img-responsive">
                        </div>
                        <!--<p class="counting">Son əlavə sayı: <span>1</span></p>-->
                    </div>
                </router-link>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-4 mb-30">
                <router-link to="/news">
                    <div class="block">
                        <div class="admin-heading">
                            <p>XƏBƏRLƏR</p>
                        </div>
                        <div class="admin-count">
                            <p>{{Dashboard.xeberler}}</p>
                            <img src="/admin/cp/img/news.png" alt="news" class="img-responsive">
                        </div>
                        <!--<p class="counting">Son əlavə sayı: <span>5</span></p>-->
                    </div>
                </router-link>
            </div>
        </div>
    </div>
</template>

<script>
    import {axios} from "../config/enterprise_axios";

    export default {
        name: "Profile",
        data:function () {
            return {
                /*id: this.$route.params.id,*/
                Dashboard:{}
            }
        },
        mounted() {
            this.getDashboard()
        },
        methods:{
            getDashboard(){
                axios.get('/dashboard').then(
                    response=> {
                        this.Dashboard=response.data.data
                    }
                )
            }
        }
    }
</script>

<style scoped>

</style>
