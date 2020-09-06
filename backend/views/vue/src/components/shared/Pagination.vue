<template>
    <nav aria-label="Page navigation" class="text-right pagination_Rp" v-if="paginations.pageCount > 1">
        <ul class="pagination">
            <li :class="paginations.currentPage === 1 ? 'disabled' : ''">
                <a @click="goPage(paginations.currentPage - 1, query)" :href="query ? baseUrl+ path+'?id='+ query + '&page='+ (paginations.currentPage - 1) : baseUrl+ path+'?page='+ (paginations.currentPage - 1)" aria-label="Previous">
                    <span aria-hidden="true">ƏVVƏLKİ</span>
                </a>
            </li>
            <template v-for="pagination in paginations.pageCount">
                <li class="test1" v-if="(((pagination-paginations.currentPage) < 5) && (0 <= (pagination-paginations.currentPage))) || paginations.pageCount === pagination || paginations.pageCount === 1" :class="{'active' :paginations.currentPage == pagination}">
                    <a @click="goPage(pagination, query)" :href="query ? baseUrl+ path+'?id='+ query +'&page='+pagination : baseUrl+ path+'?page='+pagination">{{ pagination }}</a>
                </li>
                <li
                  v-if="(pagination-paginations.currentPage) > 4 && (pagination - paginations.currentPage) <= 5 && pagination < paginations.pageCount && paginations.currentPage !== paginations.pageCount"
                  :class="{'active' :paginations.currentPage == pagination}">
                    <a @click.prevent :href="paginations.currentPage">...</a>
                </li>
            </template>
            <li :class="paginations.currentPage === paginations.pageCount ? 'disabled' : ''">
                <a @click="goPage(paginations.currentPage + 1, query)" :href="query ? baseUrl+ path+'?id=' + query +'&page='+ (paginations.currentPage + 1) : baseUrl+ path+'?page='+ (paginations.currentPage + 1)" aria-label="Next">
                    <span aria-hidden="true">NÖVBƏTİ</span>
                </a>
            </li>
        </ul>
    </nav>
</template>

<script>
    export default {
        name: "Pagination",
        props:{
            paginations:Object,
            callback:Function
        },
        data:function () {
            return {
               path: this.$route.path.split('/')[1],
               query: this.$route.query.id,
               baseUrl : '/admin/general#'
            }
        },
        watch: {
            paginations: function(newVal, oldVal) {
                this.$props.paginations = newVal;
                this.paginations = newVal;
            }
        },
        methods: {
            goPage(page, query){
                this.$router.push({ query: { page: page, id: query }});
                this.callback();
            }
        }
    }
</script>

<style scoped>

</style>
