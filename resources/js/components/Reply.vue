<template>
    <div class="card mb-4" :id="'reply-' + reply.id">
        <div class="card-header">
            <div class="level">

                <div class="flex">
                    <a :href="'/profiles/' + reply.owner.username" v-text="reply.owner.username">
                    </a> said <span v-text="createdAt"></span>...
                </div>

                <button class="btn text-secondary btn-sm ml-auto"
                        v-if="!isBest"
                        @click="markBestReply">
                    <i class="fas fa-star fa-lg"></i>
                </button>

                <i v-if="isBest" class="fas fa-star ml-auto fa-lg m-2 text-warning" @click="isBest = false"></i>

            </div>
        </div>

        <div class="card-body">
            <div v-if="editing">
                <form @submit.prevent="update">
                    <div class="form-group">
                        <vue-tribute :options="tributeOptions">
                            <textarea class="form-control" name="body" rows="5" :id="'reply.body' + reply.id" v-text="reply.body" required></textarea>
                        </vue-tribute>
                    </div>
                    <button class="btn btn-sm btn-primary">Update</button>
                    <button type="button" class="btn btn-sm btn-link" @click="editing = false">Cancel</button>
                </form>
            </div>
            <div v-else v-html='reply.htmlBody'></div>
        </div>

        <div class="card-footer level">
            <div v-if="authorize('updateReply', reply)">
                <button class="btn text-secondary mr-2 text-success" @click="editing = true">
                    <i class="far fa-edit"></i>
                </button>

                <button class="btn text-secondary btn-sm mr-2 text-danger" @click="destroy()">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </div>

            <div v-if="signedIn" class="ml-auto">
                <favorite :reply="reply"></favorite>
            </div>

        </div>
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
                isBest : this.reply.isBest
            }
        },
        created() {
            window.events.$on('best-reply-selected', id => {
                this.isBest = id == this.reply.id;
            });
        },
        methods: {
            update() {
                axios.patch(`/replies/${this.reply.id}`, {
                    body: $(`#body${this.reply.id}`).val()
                })
                .catch(error => {
                    console.log('Error');
                    console.log(error.response);

                    flash(error.response.data, 'danger');
                })
                .then(response => {
                    this.reply.body = response.data.body;
                    this.reply.htmlBody = response.data.reply.htmlBody;
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
            }
        },
        computed: {
            createdAt() {
                return moment(this.reply.created_at).fromNow();
            },
            tributeOptions() {
                return {
                    values: function (text, cb) {
                        axios.get('/api/users', { username: text })
                            .then(({data}) => {
                                cb(data);
                            });
                    },
                    fillAttr: 'username',
                    lookup: 'username'
                }
            }
        }
    }
</script>
