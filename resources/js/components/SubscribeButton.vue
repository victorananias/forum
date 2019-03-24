<template>
    <button @click="subscribe" :class="classes">
        {{ active ? 'Subscribe' : 'Subscribed' }}
    </button>
</template>

<script>
    export default {
        props: ['initialActive'],
        data() {
            return {
                active: this.initialActive
            }
        },
        methods: {
            subscribe() {
                let requestType = this.active ? 'post' : 'delete';

                axios[requestType](`${location.pathname}/subscriptions`)
                    .then(() => this.active = !this.active);
            }
        },
        computed: {
            classes() {
                return ['btn', this.active ? 'btn-primary' : 'btn-outline-secondary'];
            }
        }
    }
</script>

