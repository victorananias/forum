<template>
    <div>
        <div v-for="(reply, index) in items" :key="reply.id">
            <reply :reply="reply" @deleted="remove(index)"></reply>
        </div>

        <paginator :dataSet="dataSet" @changed="fetch"></paginator>

        <div class="alert alert-info" role="alert" v-if="$parent.locked">
            This Thread has been locked. No more replies are allowed.
        </div>

        <new-reply @created="add($event)" v-else></new-reply>
    </div>
</template>

<script>
    import Reply from './Reply.vue';
    import NewReply from './NewReply.vue';
    import Paginator from './Paginator.vue';
    import collection from '../mixins/collection';

    export default {
        components: { Reply, NewReply, Paginator },
        mixins: [ collection ],
        data() {
            return {
                dataSet: false
            }
        },
        created() {
            this.fetch();
        },
        methods: {
            fetch(page) {
                axios.get(this.url(page)).then(this.refresh);
            },
            url(page) {
                if (!page) {
                    let query = location.search.match(/page=(\d+)/);
                    page = query ? query[1] : 1;
                }

                return `${location.pathname}/replies?page=${page}`;
            },
            refresh({data}) {
                window.scrollTo(0, 0);
                this.dataSet = data;
                this.items = data.data
            }
        }
    }
</script>

