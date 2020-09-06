<template>
    <div id="enterprise_app">
        <Head/>
        <Layout/>
    </div>
</template>

<script>
    import Head from './enterprise_components/Head'
    import Layout from './enterprise_components/Layout'
    export default {
        name: "Enterprise_app",
        components: {
            Head,
            Layout
        }
    }
</script>

<style scoped>

</style>