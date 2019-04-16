<template>
    <div class="row">
        <div class="col-md-12 mb-5">
            <form v-if="signedIn">
                <div class="form-group">
<!--                    <vue-tribute :options="tributeOptions">-->
<!--                        <textarea class="form-control" name="body" rows="5" id="body"-->
<!--                                  placeholder="Have something to say?" required></textarea>-->
                        <wysiwyg placeholder="Have something to say?" v-model="body"></wysiwyg>
<!--                    </vue-tribute>-->
                </div>
                <div class="form-group">
                    <button type="button" class="btn btn-primary" @click="addReply">Reply</button>
                </div>
            </form>

            <p v-else class="text-center"><a href="/login">Login</a> to participate on the discussion.</p>

        </div>

    </div>
</template>

<script>

    export default {
        data () {
            return {
                body: ''
            }
        },
        methods: {
            addReply() {
                console.log(this.body);
                return;
                axios.post(`${location.pathname}/replies` , { body: this.body })
                    .then(response => {
                        this.body = '';
                        flash('Your reply has been saved.');
                        this.$emit('created', response.data);
                    })
                    .catch(error => {
                        flash(error.response.data, 'danger');
                    });
            }
        },
        computed: {
            // tributeOptions() {
            //     return {
            //         values(text, cb) {
            //             axios.get('/api/users', { username: text })
            //                 .then(({data}) => cb(data));
            //         },
            //         fillAttr: 'username',
            //         lookup: 'username',
            //     }
            // }
        }
    }
</script>

