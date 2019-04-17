<template>
    <button class="btn btn-sm" @click="toggle" :disabled="! authorize('isAdmin')">
        <i class="fas fa-lock-open" v-if="! $parent.locked"></i>
        <i class="fas fa-lock" v-else></i>
    </button>
</template>

<script>
    export default {
        methods: {
            toggle() {
                axios[this.$parent.locked ? 'delete' : 'post'](`/locked-threads/${this.$parent.thread.slug}`);
                this.$parent.locked = ! this.$parent.locked;
             }
        }
    }
</script>