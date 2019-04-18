<template>
    <a :class="classes" @click="toggle()">
        <i class="fas fa-heart"></i>
        <span v-text="count"></span>
    </a>
</template>

<script>
    export default {
        props: ['reply'],
        data() {
            return {
                count: this.reply.favoritesCount,
                active:  this.reply.isFavorited
            }
        },
        methods: {
            toggle() {
                this.active ? this.destroy() : this.create();
            },
            create() {
                axios.post(this.endpoint);

                this.active = true;
                this.count++;
            },
            destroy() {
                axios.delete(this.endpoint);

                this.active = false;
                this.count--;
            }
        },
        computed: {
            classes() {
                return ['btn', 'btn-sm', this.active ? 'text-primary' : 'btn-default'];
            },
            endpoint() {
                return `/replies/${this.reply.id}/favorites`;
            }
        }
    }
</script>

