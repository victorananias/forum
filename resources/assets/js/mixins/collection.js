export default {
    data() {
        return {
            items: []
        }
    },
    methods: {
        add(item) {
            this.items.unshift(item);
            this.$emit('added');
        },
        remove(index) {
            this.items.splice(index, 1);
            this.$emit('removed');
        }

    }
}