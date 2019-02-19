<template>
    <div class="card mb-4" :id="'reply-' + data.id"> 
        <div class="card-header">
            <div class="level">
                <div class="flex">
                    <a :href="'/profiles/' + data.owner.name" v-text="data.owner.name">
                    </a> disse <span v-text="createdAt"></span>...
                </div>
                <div v-if="signedIn">
                    <favorite :reply="data"></favorite>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div v-if="editing">
                <div class="form-group">
                    <textarea class="form-control" v-model="body"></textarea>
                </div>
                <button class="btn btn-sm btn-primary" @click="update()">Update</button>
                <button class="btn btn-sm btn-link" @click="editing = false">Cancel</button>
            </div>
            <div v-else v-text='body'></div>
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

    export default {
        props: ['data'],
        components: { Favorite },
        data() {
            return {
                editing: false,
                body: this.data.body
            }
        },
        methods: {
            update() {
                axios.patch(`/replies/${this.data.id}`, {
                    body: this.body
                });

                this.editing = false;
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
                moment.locale('pt-BR');
                return moment(this.data.created_at).fromNow();
            }
        }
    }
</script>
