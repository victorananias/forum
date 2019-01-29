<template>
    <div>
        <div v-for="(reply, index) in items" :key="reply.id">
            <reply :data="reply" @deleted="remove(index)"></reply>
        </div>
        <new-reply @created="add($event)" :endpoint="endpoint"></new-reply>
    </div>
</template>

<script>
import Reply from './Reply.vue'
import NewReply from './NewReply.vue'

export default {
    props: ['data'],
    components: { Reply, NewReply },
    data() {
        return {
            items: this.data,
            endpoint: location.pathname
        }
    },
    methods: {
        remove(index) {
            this.items.splice(index, 1);
            this.$emit('removed');
            flash('A resposta foi deletada.')
        },
        add(reply) {
            this.items.unshift(reply);
            this.$emit('added');
        }
    }
}
</script>

