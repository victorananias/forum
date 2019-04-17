<template>
    <div>
        <div class="level">

            <img :src="avatar" width="100" onerror="this.src = '/avatar.png'">

            <h4 class="ml-3">
                {{ user.username }}
            </h4>
        </div>

        <form v-if="canUpdate" method="POST"  enctype="multipart/form-data" class="mt-3">
            <div class="form-group">
                <image-upload @loaded="onLoad" class="form-control-file"></image-upload>
            </div>
        </form>
    </div>
</template>

<script>
    import ImageUpload from './ImageUpload.vue';
    import moment from 'moment';

    export default {
        props: ['user'],
        components: { ImageUpload },
        data() {
            return {
                avatar: this.user.avatar_path
            }
        },
        computed: {
            canUpdate() {
                return this.authorize(user => user.id === this.user.id);
            },
            createdAt() {
                return moment(this.user.created_at, 'YYYY-MM-DD H:mm:ss').format('H:mm DD/MM/YYYY')
            }
        },
        methods: {
            onLoad(e) {
                this.avatar = e.src;
                this.persist(e.file)
            },
            persist(file) {
                let form = new FormData();

                form.append('avatar', file);

                axios.post(`/api/users/${this.user.username}/avatar`, form)
                    .then(() => flash('Avatar uploaded'));
            }
        }
    }
</script>

<style scoped>

</style>