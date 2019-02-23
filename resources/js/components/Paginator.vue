<template>
    <ul class="pagination" v-if="shouldPaginate">
        <li class="page-item"><a class="page-link" v-if="prevUrl" @click="page--">Anterior</a></li>
        <!-- <li class="page-item" v-for="p in dataSet.pages" v-><a class="page-link">1</a></li> -->
        <!-- <li class="page-item"><a class="page-link">2</a></li>
        <li class="page-item"><a class="page-link">3</a></li> -->
        <li class="page-item"><a class="page-link" v-if="nextUrl" @click="page++">Próximo</a></li>
    </ul>
</template>

<script>
    export default {
        props: [ 'dataSet' ],
        data() {
            return {
                page: 1,
                prevUrl: false,
                nextUrl: false
            }
        },
        watch: {
            dataSet() {
                this.page = this.dataSet.current_page;
                this.prevUrl = this.dataSet.prev_page_url;
                this.nextUrl = this.dataSet.next_page_url;
            },
            page() {
                this.broadcast().updateUrl();
            }
        },
        computed: {
            shouldPaginate() {
                return !!this.prevUrl || !!this.nextUrl;
            }
        },
        methods: {
            broadcast() {
                return this.$emit('changed', this.page);
            },
            updateUrl() {
                history.pushState(null, null, `?page=${this.page}`);
            }
        }
    }
</script>

