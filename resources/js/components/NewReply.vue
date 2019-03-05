<template>
    <div class="row">
        <div class="col-md-12 mb-5">
            <form v-if="signedIn">
                <div class="form-group">
                    <vue-tribute :options="tributeOptions">
                        <textarea class="form-control" name="body" rows="5" id="body"
                                  placeholder="Tem algo a dizer?" required></textarea>
                    </vue-tribute>
                </div>
                <div class="form-group">
                    <button type="button" class="btn btn-dark float-right" @click="addReply">Responder</button>
                </div>
            </form>

            <p v-else class="text-center"><a href="/login">Entre</a> para participar da discução.</p>

        </div>

    </div>
</template>

<script>

    import VueTribute from 'vue-tribute';

    export default {
        components: { VueTribute },
        methods: {
            addReply() {
                axios.post(`${location.pathname}/replies` , { body: $('#body').val() })
                    .then(response => {
                        $('#body').val('');
                        flash('Sua resposta foi salva.');
                        this.$emit('created', response.data);
                    })
                    .catch(error => {
                        flash(error.response.data, 'danger');
                    });
            }
        },
        computed: {
            signedIn() {
                return window.App.signedIn;
            },
            tributeOptions() {
                return {
                    values: function (text, cb) {
                        axios.get('/api/users', { name: text })
                            .then(({data}) => {
                            cb(data);
                        });
                    },
                    fillAttr: 'name',
                    lookup: 'name',
                }
            }
        }
    }
</script>
<style scoped>
    @import '~tributejs/dist/tribute.css';
</style>

