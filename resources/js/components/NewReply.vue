<template>
    <div class="row">
        <div class="col-md-12 mb-5">
            <form v-if="signedIn">
                <div class="form-group">
                    <textarea class="form-control" name="body" rows="5" v-model="body"
                        placeholder="Tem algo a dizer?" required></textarea>
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
    export default {
        data() {
            return {
                body: ''
            }
        },
        methods: {
            addReply() {
                axios.post(`${location.pathname}/replies` , { body: this.body })
                    .then(({data}) => {
                        this.body = '';
                        flash('Sua resposta foi salva.');
                        this.$emit('created', data);
                    });
            }
        },
        computed: {
            signedIn() {
                return window.App.signedIn;
            }
        }
    }
</script>

