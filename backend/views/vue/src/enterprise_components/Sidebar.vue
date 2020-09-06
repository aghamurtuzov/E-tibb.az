<template>
    <div class="menu">
        <ul class="left-sidebar">
            <router-link to="/"  tag="li">
                <a class="admin-icon"><span>Profil</span></a>
            </router-link>
            <router-link to="/doctors" tag="li"
                         :class="(((path_custom.includes('doctor')) === true)||((path_custom.includes('doctors')) === true)) ? 'router-link-exact-active router-link-active' : ''">
                <a class="doctors-icon"><span>Həkimlər</span></a>
            </router-link>
           <!-- <router-link :to="{path:'/enterprises?' + 'id=' + 1}" tag="li">
                <a class="clinic-icon"><span>Klinikalar</span></a>
            </router-link>
            <router-link :to="{path:'/enterprises?' + 'id=' + 6}" tag="li">
                <a class="pharmacy-icon"><span>Apteklər</span></a>
            </router-link>-->
            <router-link to="/stocks" tag="li"
                         :class="(((path_custom.includes('stock')) === true)||((path_custom.includes('stocks')) === true)) ? 'router-link-exact-active router-link-active' : ''">
                <a class="stocks-icon"><span>Aksiyalar</span></a>
            </router-link>
            <router-link to="/promocode" tag="li">
                <a class=""><span class="pt-0">Promokodlar</span></a>
            </router-link>
            <router-link to="/review" tag="li">
                <a class="review-icon"><span>Rəylər</span></a>
            </router-link>
           <!-- <router-link to="/reservations" tag="li">
                <a class="reservation-icon"><span>Rezervasiya</span></a>
            </router-link>-->
<!--            <router-link to="/account" tag="li">-->
<!--                <a class="account-icon"><span>Ödə</span></a>-->
<!--            </router-link>-->
            <router-link to="/blogs" tag="li"
                         :class="(((path_custom.includes('blog')) === true)||((path_custom.includes('blogs')) === true)) ? 'router-link-exact-active router-link-active' : ''">
                <a class="blog-icon"><span>Bloqlar</span></a>
            </router-link>
            <router-link to="/news" tag="li"
                         :class="(((path_custom.includes('news')) === true)) ? 'router-link-exact-active router-link-active' : ''">
                <a class="news-icon"><span>Xəbərlər</span></a>
            </router-link>
            <router-link to="/setting" tag="li">
                <a class="setting-icon"><span>Tənzimləmələr</span></a>
            </router-link>
        </ul>
    </div>
</template>

<script>
    export default {
        name: "Sidebar",
        data:function () {
          return {
            id: this.$route.params.id,
            path_custom: ''
          }
        },
        mounted() {
          var path = this.$route.path;
          this.path_custom = path.split('/');
        },
        watch:{
          $route (to, from){
            var path = this.$route.path;
            this.path_custom = path.split('/');
          }
        }
    }
</script>

<style scoped>

</style>
