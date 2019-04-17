<template>
    <div>
        <input id="trix" type="hidden" :name="name" :value="value">
        <trix-editor input="trix" ref="trix" :placeholder="placeholder"></trix-editor>
    </div>
</template>

<script>
    import Trix from 'trix';

    export default {
        props: { name: { default: '' }, placeholder: { default: '' }, value: { default:'' }, required: { default: false }},
        mounted () {
            this.$refs.trix.addEventListener('trix-change', e => this.$emit('input', e.target.innerHTML));
        },
        watch: {
            value (value) {
                if (this.$refs.trix.innerHTML === value) return;
                document.querySelector("trix-editor").value = value;
            }
        }
    }

</script>

<style scoped>

</style>