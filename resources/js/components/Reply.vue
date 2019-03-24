<template>
    <div class="card mb-4" :id="'reply-' + data.id"> 
        <div class="card-header">
            <div class="level">
                <div class="flex">
                    <a :href="'/profiles/' + data.owner.username" v-text="data.owner.username">
                    </a> said <span v-text="createdAt"></span>...
                </div>
                <div v-if="signedIn">
                    <favorite :reply="data"></favorite>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div v-if="editing">
                <form @submit.prevent="update">
                    <div class="form-group">
                        <vue-tribute :options="tributeOptions">
                            <textarea class="form-control" name="body" rows="5" :id="'body'+id" v-text="body" required></textarea>
                        </vue-tribute>
                    </div>
                    <button class="btn btn-sm btn-primary">Update</button>
                    <button type="button" class="btn btn-sm btn-link" @click="editing = false">Cancel</button>
                </form>
            </div>
            <div v-else v-html='htmlBody'></div>
        </div>

        <div class="card-footer level" v-if="canUpdate">
            <button class="btn text-secondary mr-2" @click="editing = true">
                <i class="far fa-edit fa-lg"></i>
            </button>
            <button class="btn text-secondary btn-sm mr-2" @click="destroy()">
                <i class="fas fa-trash-alt fa-lg"></i>
            </button>
        </div>
    </div>
</template>

<script>
    import Favorite from './Favorite.vue';
    import moment from 'moment';
    import VueTribute from 'vue-tribute';

    export default {
        props: ['data'],
        components: { Favorite, VueTribute },
        data() {
            return {
                editing: false,
                id: this.data.id,
                body: this.data.body,
                htmlBody: this.data.htmlBody
            }
        },
        methods: {
            update() {
                axios.patch(`/replies/${this.data.id}`, {
                    body: $(`#body${this.id}`).val()
                })
                .catch(error => {
                    console.log('Error');
                    console.log(error.response);

                    flash(error.response.data, 'danger');
                })
                .then(response => {
                    this.body = response.data.body;
                    this.htmlBody = response.data.htmlBody;
                    this.editing = false;
                });
            },
            destroy() {
                axios.delete(`/replies/${this.data.id}`);
                this.$emit('deleted', this.data.id);
            }
        },
        computed: {
            signedIn() {
                return window.App.signedIn;
            },
            canUpdate() {
                return this.authorize(user =>  this.data.user_id == user.id);
            },
            createdAt() {
                return moment(this.data.created_at).fromNow();
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
