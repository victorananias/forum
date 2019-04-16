<template>
    <div>
        <input id="trix" type="hidden" :name="name" :value="value">
        <trix-editor input="trix" ref="trix" :placeholder="placeholder"></trix-editor>
    </div>
</template>

<script>
    import Trix from 'trix';

    export default {
        props: ['name', 'placeholder', 'value'],
        mounted () {
            this.$refs.trix.addEventListener('trix-change', e => {
                this.$emit('input', e.target.innerHTML)
            });
        },
        watch: {
            value (value) {
                document.querySelector("trix-editor").value = value;
            }
        }
    }

</script>

<style scoped>

</style>