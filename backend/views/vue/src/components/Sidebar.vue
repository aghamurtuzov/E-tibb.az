<template>
    <div class="menu">
        <ul class="left-sidebar">
            <router-link to="/"  tag="li">
                <a @click="menuChange('/')" class="admin-icon"><span>Statistika</span></a>
            </router-link>
            <router-link to="/doctors" tag="li"
                         :class="(((path_custom.includes('doctor')) === true)||((path_custom.includes('doctors')) === true)) ? 'router-link-exact-active router-link-active' : ''">
                <a @click="menuChange('/doctors')" class="doctors-icon"><span>Həkimlər</span></a>
            </router-link>
<!--            <router-link to="/news" tag="li"-->
<!--                         :class="(((path_custom.includes('news')) === true)) ? 'router-link-exact-active router-link-active' : ''">-->
<!--                <a class="news-icon"><span>Xəbərlər</span></a>-->
<!--            </router-link>-->
            <router-link :to="{path:'/newsList?' + 'id=' + 37}" tag="li"
                         :class="(((path_custom.includes('news')) === true)||(enterpriseType === '37')) ? 'router-link-exact-active router-link-active' : ''">
                <a @click="menuChange('/newsList?id=37')" class="news-icon"><span>Xəbərlər</span></a>
            </router-link>
            <router-link :to="{path:'/newsList?' + 'id=' + 34}" tag="li"
                         :class="(((path_custom.includes('blogs')) === true)||(enterpriseType === '34')) ? 'router-link-exact-active router-link-active' : ''">
                <a @click="menuChange('/newsList?id=34')" class="blog-icon"><span>Bloqlar</span></a>
            </router-link>
            <router-link :to="{path:'/enterprises?' + 'id=' + 1}" tag="li"
                         :class="(((path_custom.includes('clinics')) === true)||(enterpriseType === '1')) ? 'router-link-exact-active router-link-active' : ''">
                <a @click="menuChange('/enterprises?id=1')" class="clinic-icon"><span>Klinikalar</span></a>
            </router-link>
            <router-link :to="{path:'/enterprises?' + 'id=' + 6}" tag="li"
                         :class="(((path_custom.includes('pharmacy')) === true)||(enterpriseType === '6')) ? 'router-link-exact-active router-link-active' : ''">
                <a @click="menuChange('/enterprises?id=6')" class="pharmacy-icon"><span>Apteklər</span></a>
            </router-link>
            <router-link to="/stocks" tag="li"
                         :class="(((path_custom.includes('stock')) === true)||((path_custom.includes('stocks')) === true)) ? 'router-link-exact-active router-link-active' : ''">
                <a @click="menuChange('/stocks')" class="stocks-icon"><span>Aksiyalar</span></a>
            </router-link>
            <router-link to="/promocode" tag="li">
                <a @click="menuChange('/promocode')" class="promocode-icon"><span>Promokodlar</span></a>
            </router-link>
            <router-link to="/questions" tag="li"
                         :class="((path_custom.includes('questions')) === true) ? 'router-link-exact-active router-link-active' : ''">
                <a @click="menuChange('/questions')" class="quiz-icon"><span>Suallar</span></a>
            </router-link>
            <router-link to="/review" tag="li"
                         :class="((path_custom.includes('review')) === true) ? 'router-link-exact-active router-link-active' : ''">
                <a @click="menuChange('/review')" class="review-icon"><span>Rəylər</span></a>
            </router-link>
<!--            <router-link to="/reservations" tag="li">-->
<!--                <a class="reservation-icon "><span>Rezervasiya</span></a>-->
<!--            </router-link>-->
            <!--<router-link to="/letters" tag="li">
                <a class="letters-icon"><span>Məktublar</span></a>
            </router-link>-->
            <!--<router-link :to="{path:'/enterprises?' + 'id=' + 40}" tag="li">
                <a class="medical-icon"><span>Tibbi mağaza</span></a>
            </router-link>-->
<!--            <router-link to="/account" tag="li">-->
<!--                <a class="account-icon"><span>Mühasibat</span></a>-->
<!--            </router-link>-->
            <!--<router-link to="/blocked" tag="li">
                <a class="blocked-icon"><span>Blok olunanlar</span></a>
            </router-link>-->
            <router-link to="/slider" tag="li">
                <a @click="menuChange('/slider')" class="stocks-icon"><span>Slider</span></a>
            </router-link>
            <!--<router-link to="/admin" tag="li">
                <a class="live-icon red"><span>Canlı danışıq</span></a>
            </router-link>-->
            <!--<router-link to="/staticpages" tag="li" >
                <a class="page-icon"><span>Statik səhifələr</span></a>
            </router-link>-->
            <router-link to="/feedback" tag="li">
                <a @click="menuChange('/feedback')" class="user-icon"><span>Bizə yazanlar</span></a>
            </router-link>
            <router-link to="/donate" tag="li"
                         :class="(((path_custom.includes('donate')) === true)) ? 'router-link-exact-active router-link-active' : ''">
                <a @click="menuChange('/donate')" class="donate-icon"><span>Qan ver</span></a>
            </router-link>
            <router-link to="/users" tag="li">
                <a @click="menuChange('/users')" class="user-icon"><span>İstifadəçilər</span></a>
            </router-link>
            <router-link v-if="user_role == 'superadmin'" to="/administrators" tag="li"
                         :class="(((path_custom.includes('administrator')) === true)||((path_custom.includes('administrators')) === true)) ? 'router-link-exact-active router-link-active' : ''">
              <a @click="menuChange('/administrators')" class="administrator-icon"><span>İdarəçilər</span></a>
            </router-link>
            <!--<router-link to="/company" tag="li">
                <a class="companies-icon"><span>Kampaniyalar</span></a>
            </router-link>-->
            <!--<router-link to="/statics" tag="li">
                <a class="statics-icon red"><span>Statistika</span></a>
            </router-link>-->
        </ul>
    </div>
</template>

<script>
    import axios_main from 'axios';
    export default {
        name: "Sidebar",
        data:function () {
            return {
                id: this.$route.params.id,
                path_custom: '',
                enterpriseType: '',
                user_role:''
            }
        },
        mounted() {
            this.getUser();
            var path = this.$route.path;
            this.path_custom = path.split('/');
            this.enterpriseType = this.$route.query.id;
        },
        watch:{
            $route (to, from){
                var path = this.$route.path;
                this.path_custom = path.split('/');
                this.enterpriseType = this.$route.query.id;
            },
        },
        methods: {
            menuChange(to) {
                if(this.$route.fullPath === to) {
                    location.reload();
                }
            },
            getUser(){
                axios_main.get('/api/site/user').then(
                    response=>{
                        this.user_role = response.data.data.role.item_name;
                    }
                )
            }
        }
    }
</script>

<style scoped>

</style>
