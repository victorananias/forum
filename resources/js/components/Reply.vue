<template>
    <div class="card mb-4" :id="'reply-' + reply.id">
        <div class="card-header">
            <div class="level">

                <div class="flex">
                    <a :href="'/profiles/' + reply.owner.username" v-text="reply.owner.username">
                    </a> said <span v-text="createdAt"></span>...
                </div>

                <button class="btn text-secondary btn-sm ml-auto"
                        v-if="!isBest && authorize('owns', reply.thread)"
                        @click="markBestReply">
                    <i class="fas fa-star fa-lg"></i>
                </button>

                <i v-if="isBest" class="fas fa-star ml-auto fa-lg m-2 text-warning" @click="isBest = false"></i>

            </div>
        </div>

        <form @submit.prevent="update">
            <div class="card-body">
                <div v-if="editing">
                    <div class="form-group">
<!--                        <vue-tribute :options="tributeOptions">-->
<!--                            <textarea class="form-control" name="body" rows="5" :id="'body-' + reply.id" v-text="reply.body" required></textarea>-->
                        <wysiwyg v-model="body" required></wysiwyg>
<!--                        </vue-tribute>-->
                    </div>
                </div>

                <div v-else v-html='reply.body'></div>
            </div>

            <div class="card-footer level" v-if="signedIn">
                <div v-if="authorize('owns', reply) && !editing">
                    <button type="button" class="btn mr-2" @click="resetForm">
                        <i class="fas fa-pen"></i>
                    </button>

                    <button type="button" class="btn mr-2" @click="destroy">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>

                <div v-if="authorize('owns', reply) && editing">
                    <button type="submit" class="btn">
                        <i class="fas fa-save"></i>
                    </button>
                    <button type="button" class="btn" @click="editing = false">
                        <i class="fas fa-undo"></i>
                    </button>
                </div>

                <div class="ml-auto">
                    <favorite :reply="reply"></favorite>
                </div>

            </div>
        </form>
    </div>
</template>

<script>
    import Favorite from './Favorite.vue';
    import moment from 'moment';
    import VueTribute from 'vue-tribute';

    export default {
        props: ['reply'],
        components: { Favorite, VueTribute },
        data() {
            return {
                editing: false,
                isBest : this.reply.isBest,
                body: ''
            }
        },
        created() {
            window.events.$on('best-reply-selected', id => {
                this.isBest = id == this.reply.id;
            });
        },
        methods: {
            update() {
                if (! this.body) return;

                axios.patch(`/replies/${this.reply.id}`, {
                    body: this.body
                })
                .catch(error => {
                    console.log('Error');
                    console.log(error.response);

                    flash(error.response.data, 'danger');
                })
                .then(response => {
                    this.reply.body = this.body;
                    this.editing = false;
                });
            },
            destroy() {
                axios.delete(`/replies/${this.reply.id}`);
                this.$emit('deleted', this.reply.id);
            },
            markBestReply() {
                axios.post(`/api/replies/${this.reply.id}/best`, {});
                window.events.$emit('best-reply-selected', this.reply.id);
            },
            resetForm() {
                this.body = this.reply.body;
                this.editing = true;
            }
        },
        computed: {
            createdAt() {
                return moment(this.reply.created_at).fromNow();
            },
            // tributeOptions() {
            //     return {
            //         values: function (text, cb) {
            //             axios.get('/api/users', { username: text })
            //                 .then(({data}) => {
            //                     cb(data);
            //                 });
            //         },
            //         fillAttr: 'username',
            //         lookup: 'username'
            //     }
            // }
        }
    }
</script>
