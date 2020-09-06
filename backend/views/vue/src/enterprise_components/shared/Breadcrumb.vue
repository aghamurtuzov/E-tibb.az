<template>
    <div class="head-line">
        <p><span><i class="fa" :class="icon" aria-hidden="true"></i></span>{{ name }}</p>
        <ol class="breadcrumb">
            <li><router-link to="/">Admin</router-link></li>
            <template v-for="route in routes">
                <li v-if="route.url"><a :href="route.url">{{ route.name }}</a></li>
                <li v-else class="active">{{ route.name }}</li>
            </template>
        </ol>
    </div>
</template>

<script>
    export default {
        name: "Breadcrumb",
        props: {
            name: String,
            icon: String,
            routes: Array,
        }
    }
</script>

<style scoped>

</style>
