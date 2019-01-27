<template>
    <button type="submit" :class="classes" @click="toggle()">
        <i class="fas fa-heart"></i>
        <span v-text="favoritesCount"></span>
    </button>
</template>

<script>
    export default {
        props: ['reply'],
        data() {
            return {
                favoritesCount: this.reply.favorites_count,
                isFavorited: false
            }
        },
        methods: {
            toggle() {
                if (this.isFavorited) {
                    axios.delete(`/replies/${this.reply.id}/favorites`);
                } else {
                    axios.post(`/replies/${this.reply.id}/favorites`);

                    this.isFavorited = true;
                    this.favoritesCount++;
                }
            }
        },
        computed: {
            classes() {
                return ['btn', this.isFavorited ? 'btn-primary' : 'btn-default'];
            }
        }
    }
</script>

