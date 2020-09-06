<template>
    <div class="head-line">
        <p><span><i class="fa fa-money" aria-hidden="true"></i></span>MÜHASİBAT</p>
        <ol class="breadcrumb">
            <b-breadcrumb :items="items"></b-breadcrumb>
            <!--<li><a href="index.html">Admin</a></li>
            <li class="active">Mühasibat</li>-->
        </ol>
    </div>
</template>

<script>
    export default {
        name: "shared",
        data() {
            return {
                items: [
                    {
                        text: 'Admin',
                        href: '/'
                    },
                    {
                        text: 'Mühasibat',
                        active: true
                    }
                ]
            }
        }
    }
</script>

<style scoped>

</style>