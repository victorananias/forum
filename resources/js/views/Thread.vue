<script>
    import Replies from '../components/Replies.vue';
    import SubscribeButton from '../components/SubscribeButton.vue';
    import LockButton from '../components/LockButton.vue';

    export default {
        props: ['thread'],
        components: { Replies, SubscribeButton, LockButton },
        data() {
            return {
                locked: this.thread.locked,
                repliesCount: this.thread.replies_count
            }
        }
    }
</script>
