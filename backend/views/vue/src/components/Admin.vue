<template>
    <div class="admin-block">
        <div class="row">
            <div class="col-lg-3 col-md-3">
                <router-link to="/doctors">
                    <div class="block">
                        <div class="admin-heading">
                            <p>HƏKİMLƏR</p>
                        </div>
                        <div class="admin-count">
                            <p>1263</p>
                            <img src="/admin/cp/img/doctor-img.png" alt="doctor-img" class="img-responsive">
                        </div>
                        <p class="counting">Son əlavə sayı: <span>1</span></p>
                    </div>
                </router-link>
            </div>
            <div class="col-lg-3 col-md-3">
                <router-link to="/clinics">
                    <div class="block">
                        <div class="admin-heading">
                            <p>KLİNİKALAR</p>
                        </div>
                        <div class="admin-count">
                            <p>150</p>
                            <img src="/admin/cp/img/hospital.png" alt="hospital" class="img-responsive">
                        </div>
                        <p class="counting">Son əlavə sayı: <span>3</span></p>
                    </div>
                </router-link>
            </div>
            <div class="col-lg-3 col-md-3">
                <router-link to="/pharmacy">
                    <div class="block">
                        <div class="admin-heading">
                            <p>APTEKLƏR</p>
                        </div>
                        <div class="admin-count">
                            <p>50</p>
                            <img src="/admin/cp/img/medicine.png" alt="medicine" class="img-responsive">
                        </div>
                        <p class="counting">Son əlavə sayı: <span>5</span></p>
                    </div>
                </router-link>
            </div>
            <div class="col-lg-3 col-md-3">
                <router-link to="/stocks">
                    <div class="block">
                        <div class="admin-heading">
                            <p>AKSİYALAR</p>
                        </div>
                        <div class="admin-count">
                            <p>300</p>
                            <img src="/admin/cp/img/megaphone.png" alt="megaphone" class="img-responsive">
                        </div>
                        <p class="counting">Son əlavə sayı: <span>2</span></p>
                    </div>
                </router-link>
            </div>
            <div class="col-lg-3 col-md-3">
                <router-link to="/quiz">
                    <div class="block">
                        <div class="admin-heading">
                            <p>SUALLAR</p>
                            <span class="badge">01</span>
                        </div>
                        <div class="admin-count">
                            <p>563</p>
                            <img src="/admin/cp/img/quiz.png" alt="quiz-img" class="img-responsive">
                        </div>
                        <p class="counting">Son əlavə sayı: <span>12</span></p>
                    </div>
                </router-link>
            </div>
            <div class="col-lg-3 col-md-3">
                <router-link to="/review">
                    <div class="block">
                        <div class="admin-heading">
                            <p>RƏYLƏR</p>
                            <span class="badge">01</span>
                        </div>
                        <div class="admin-count">
                            <p>239</p>
                            <img src="/admin/cp/img/review.png" alt="review" class="img-responsive">
                        </div>
                        <p class="counting">Son əlavə sayı: <span>6</span></p>
                    </div>
                </router-link>
            </div>
            <div class="col-lg-3 col-md-3">
                <router-link to="/reservation">
                    <div class="block">
                        <div class="admin-heading">
                            <p>REZERVASİYA</p>
                            <span class="badge">01</span>
                        </div>
                        <div class="admin-count">
                            <p>135</p>
                            <img src="/admin/cp/img/medical-history.png" alt="medical-history" class="img-responsive">
                        </div>
                        <p class="counting">Son əlavə sayı: <span>2</span></p>
                    </div>
                </router-link>
            </div>
            <div class="col-lg-3 col-md-3">
                <router-link to="letters">
                    <div class="block">
                        <div class="admin-heading">
                            <p>MƏKTUBLAR</p>
                            <span class="badge">01</span>
                        </div>
                        <div class="admin-count">
                            <p>476</p>
                            <img src="/admin/cp/img/letters.png" alt="letters" class="img-responsive">
                        </div>
                        <p class="counting">Son əlavə sayı: <span>1</span></p>
                    </div>
                </router-link>
            </div>

            <div class="col-lg-3 col-md-3">
                <router-link to="/blog">
                    <div class="block">
                        <div class="admin-heading">
                            <p>BLOQLAR</p>
                        </div>
                        <div class="admin-count">
                            <p>978</p>
                            <img src="/admin/cp/img/blog.png" alt="blog" class="img-responsive">
                        </div>
                        <p class="counting">Son əlavə sayı: <span>1</span></p>
                    </div>
                </router-link>
            </div>
            <div class="col-lg-3 col-md-3">
                <router-link to="/medical">
                    <div class="block">
                        <div class="admin-heading">
                            <p>TİBBİ MAĞAZA</p>
                        </div>
                        <div class="admin-count">
                            <p>150</p>
                            <img src="/admin/cp/img/medical.png" alt="medical" class="img-responsive">
                        </div>
                        <p class="counting">Son əlavə sayı: <span>3</span></p>
                    </div>
                </router-link>
            </div>
            <div class="col-lg-3 col-md-3">
                <router-link to="/account">
                    <div class="block">
                        <div class="admin-heading">
                            <p>MÜHASİBAT</p>
                        </div>
                        <div class="admin-count">
                            <p>50</p>
                            <img src="/admin/cp/img/accounting.png" alt="accounting" class="img-responsive">
                        </div>
                        <p class="counting">Son əlavə sayı: <span>5</span></p>
                    </div>
                </router-link>
            </div>
            <div class="col-lg-3 col-md-3">
                <div class="block">
                    <div class="admin-heading">
                        <p>CANLI DANIŞAQ</p>
                    </div>
                    <div class="admin-count">
                        <p>300</p>
                        <img src="/admin/cp/img/live-video.png" alt="live-video" class="img-responsive">
                    </div>
                    <p class="counting">Son əlavə sayı: <span>2</span></p>
                </div>
            </div>

            <div class="col-lg-3 col-md-3">
                <router-link to="/stati/admin/admin/cpages">
                    <div class="block">
                        <div class="admin-heading">
                            <p>STATİK SƏHİFƏLƏR</p>
                        </div>
                        <div class="admin-count">
                            <p>5</p>
                            <img src="/admin/cp/img/page.png" alt="page" class="img-responsive">
                        </div>
                        <p class="counting">Son əlavə sayı: <span>1</span></p>
                    </div>
                </router-link>
            </div>
            <div class="col-lg-3 col-md-3">
               <router-link to="/users">
                   <div class="block">
                       <div class="admin-heading">
                           <p>İSTİFADƏÇİLƏR</p>
                       </div>
                       <div class="admin-count">
                           <p>150</p>
                           <img src="/admin/cp/img/users.png" alt="users" class="img-responsive">
                       </div>
                       <p class="counting">Son əlavə sayı: <span>3</span></p>
                   </div>
               </router-link>
            </div>
            <div class="col-lg-3 col-md-3">
                <router-link to="/admin/admin/news">
                    <div class="block">
                        <div class="admin-heading">
                            <p>XƏBƏRLƏR</p>
                        </div>
                        <div class="admin-count">
                            <p>50</p>
                            <img src="/admin/cp/img/news.png" alt="news" class="img-responsive">
                        </div>
                        <p class="counting">Son əlavə sayı: <span>5</span></p>
                    </div>
                </router-link>
            </div>
            <div class="col-lg-3 col-md-3">
                <router-link to="/company">
                    <div class="block">
                        <div class="admin-heading">
                            <p>KAMPANİYALAR</p>
                        </div>
                        <div class="admin-count">
                            <p>300</p>
                            <img src="/admin/cp/img/companies.png" alt="companies" class="img-responsive">
                        </div>
                        <p class="counting">Son əlavə sayı: <span>2</span></p>
                    </div>
                </router-link>
            </div>
            <div class="col-lg-3 col-md-3">
                <router-link to="/statics">
                    <div class="block">
                        <div class="admin-heading">
                            <p>STATİSTİKA</p>
                        </div>
                        <div class="admin-count">
                            <p>300</p>
                            <img src="/admin/cp/img/bars-chart.png" alt="bars-chart" class="img-responsive">
                        </div>
                        <p class="counting">Son əlavə sayı: <span>2</span></p>
                    </div>
                </router-link>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Admin"
    }
</script>

<style scoped>

</style>
