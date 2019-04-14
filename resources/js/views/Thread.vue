<script>
    import Replies from '../components/Replies.vue';
    import SubscribeButton from '../components/SubscribeButton.vue';
    import LockButton from '../components/LockButton.vue';

    export default {
        props: ['thread'],
        components: { Replies, SubscribeButton, LockButton },
        data() {
            return {
                repliesCount: this.thread.replies_count,
                locked: this.thread.locked,
                editing: false,
                title: this.thread.title,
                body: this.thread.body,
                form: {}
            }
        },
        created () {
            this.resetForm();
        },
        methods: {
            destroy () {
                axios.delete(`/threads/${this.thread.channel.slug}/${this.thread.slug}`)
                    .then(() => window.open('/threads', '_self'));
            },
            update () {
                axios.patch(`/threads/${this.thread.channel.slug}/${this.thread.slug}`, this.form)
                    .then(() => {
                        this.editing = false;
                        this.title = this.form.title;
                        this.body = this.form.body;

                        flash('Your thread has been updated.');
                    });
            },
            resetForm () {
                this.form = {
                    title: this.title,
                    body: this.body
                };

                this.editing = false;
            }
        }
    }
</script>
