<template>
    <header>
        <nav class="navbar">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="transparent" @click="togglebtn()">
                        <span class="sr-only">Toggle navigation</span>
                        <img src="/admin/cp/img/bars.png" alt="bars" class="img-responsive">
                    </button>
                    <a class="navbar-brand" href="#">
                        <img src="/admin/cp/img/logo.png" alt="logo" class="img-responsive">
                    </a>
                    <div class="user_Rp">
                        <ul class="nav navbar-right">
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                    <img src="/admin/cp/img/admin-profile.png" alt="admin-profile">
                                    <i class="fa fa-angle-down" aria-hidden="true"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li><a href="/api/site/logout">Çıxış</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav navbar-right">
                        <!-- <li>
                             <button class="transparent">
                                 <span><i class="fa fa-bell" aria-hidden="true"></i></span>
                                 <span class="badge">22</span>
                             </button>
                         </li>
                         <li>
                             <button class="transparent">
                                 <span><i class="fa fa-envelope" aria-hidden="true"></i></span>
                                 <span class="badge">01</span>
                             </button>
                         </li>-->
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                <img src="/admin/cp/img/admin-profile.png" alt="admin-profile">{{user.name}} <i class="fa fa-angle-down" aria-hidden="true"></i>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- <li><a href="#">Redaktə edin</a></li>-->
                                <li><a href="/api/site/logout">Çıxış</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
</template>

<script>
    import axios_main from 'axios';
    export default {
        name: 'Head',
        data: function(){
            return {
                active: false,
                user:''
            }
        },
        mounted(){
            this.getUser();
        },
        methods: {
            togglebtn() {
                this.active = !this.active;
                this.$root.$emit('clicked', this.active);
            },
            getUser(){
                axios_main.get('/api/site/user').then(
                    response=>{
                        this.user = response.data.data
                    }
                )
            }
        }
    }
</script>
