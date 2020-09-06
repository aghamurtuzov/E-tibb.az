<template>
    <div class="menu">
        <ul class="left-sidebar">
            <router-link to="/"  tag="li">
                <a class="admin-icon"><span>Profil</span></a>
            </router-link>
            <router-link to="/stocks" tag="li"
                         :class="(((path_custom.includes('stock')) === true)||((path_custom.includes('stocks')) === true)) ? 'router-link-exact-active router-link-active' : ''">
                <a class="stocks-icon"><span>Aksiyalar</span></a>
            </router-link>
            <router-link to="/promocode" tag="li">
                <a class="promocode-icon"><span>Promokodlar</span></a>
            </router-link>
            <router-link to="/reservations" tag="li"
                         :class="(((path_custom.includes('reservation')) === true)||((path_custom.includes('reservations')) === true)) ? 'router-link-exact-active router-link-active' : ''">
                <a class="reservation-icon"><span>Rezervasiya</span></a>
            </router-link>
<!--            <router-link to="/account" tag="li">-->
<!--                <a class="account-icon"><span>Ödə</span></a>-->
<!--            </router-link>-->
            <router-link to="/blogs" tag="li"
                         :class="(((path_custom.includes('blog')) === true)||((path_custom.includes('blogs')) === true)) ? 'router-link-exact-active router-link-active' : ''">
                <a class="blog-icon"><span>Bloq</span></a>
            </router-link>
            <router-link to="/review" tag="li">
                <a class="review-icon"><span>Rəylər</span></a>
            </router-link>
            <router-link to="/questions" tag="li">
                <a class="quiz-icon"><span>Sual-Cavab</span></a>
            </router-link>
            <!--<router-link to="/users" tag="li">
                <a class="user-icon"><span>Bizə yaz</span></a>
            </router-link>-->
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
