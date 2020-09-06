<template>
    <nav aria-label="Page navigation" class="text-right pagination_Rp" v-if="paginations.pageCount > 1">
        <ul class="pagination">
            <li>
                <a @click.prevent="callback(paginations.currentPage - 1)" href="#" aria-label="Previous">
                    <span aria-hidden="true">ƏVVƏLKİ</span>
                </a>
            </li>
            <template v-for="pagination in paginations.pageCount">
                <li v-if="Math.abs(paginations.currentPage - pagination) < 5 || paginations.pageCount == pagination" :class="{'active' :paginations.currentPage == pagination}">
                    <a @click.prevent="callback(pagination)" href="#">{{ pagination }}</a>
                </li>
                <li v-if="(paginations.currentPage - pagination) >=1 && Math.abs(paginations.currentPage - pagination) <2 && pagination < paginations.pageCount && paginations.currentPage !== paginations.pageCount" :class="{'active' :paginations.currentPage == pagination}">
                  <span style="display: none;">{{paginations.currentPage}} --- {{pagination}}</span>
                  <a @click.prevent href="#">...</a>
                </li>
            </template>
            <li>
                <a @click.prevent="callback(paginations.currentPage + 1)" href="#" aria-label="Next">
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
        }
    }
</script>

<style scoped>

</style>
