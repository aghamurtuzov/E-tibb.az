<template>
  <div class="menu">
    <ul class="left-sidebar">
      <router-link to="/"  tag="li">
        <a class="admin-icon"><span>Profil</span></a>
      </router-link>
      <router-link to="/stocks" tag="li">
        <a class="stocks-icon"><span>Aksiyalar</span></a>
      </router-link>
      <router-link to="/promocode" tag="li">
        <a class="promocode-icon"><span>Promokodlar</span></a>
      </router-link>
      <router-link to="/reservations" tag="li">
        <a class="reservation-icon"><span>Rezervasiya</span></a>
      </router-link>
      <!--            <router-link to="/account" tag="li">-->
      <!--                <a class="account-icon"><span>Ödə</span></a>-->
      <!--            </router-link>-->
      <router-link to="/blogs" tag="li">
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
    name: "Sidebarnew",
    data:function () {
      return {
        id: this.$route.params.id,
      }
    }
  }
</script>

