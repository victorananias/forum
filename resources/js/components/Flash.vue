<template>
    <div class="alert alert-flash" 
        :class="'alert-' + type" 
        role="alert" 
        v-show="show" 
        v-text="body">
    </div>
</template>

<script>
    export default {
        props: ['message'],
        data() {
            return {
                body: '',
                show: false,
                type: ''
            }
        },
        created() {
            if (this.message) {
                this.flash(this.message);
            }

            window.events.$on('flash', data => this.flash(data));
        },
        methods: {
            flash(data) {
                this.body = data.message;
                this.type = data.type;
                this.show = true;

                this.hide();
            },
            hide() {
                setTimeout(() => {
                    this.show = false;
                }, 3000);
            }
        }
    }
</script>

<style>
    .alert-flash {
        position: fixed;
        bottom: 25px;
        right: 25px;
    }
</style>
 