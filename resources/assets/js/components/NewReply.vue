<template>
    <div>
        <form v-if="signedIn">
            <div class="form-group">
                <textarea class="form-control" name="body" rows="5" v-model="body"
                    placeholder="Tem algo a dizer?" required></textarea>
            </div>
            <button type="button" class="btn btn-dark float-right" @click="addReply">Responder</button>
        </form>
        <p v-else class="text-center"><a href="/login">Entre</a> para participar da discução.</p>
    </div>
</template>

<script>
    export default {
        props: ['endpoint'],
        data() {
            return {
                body: '', 
            }
        },
        methods: {
            addReply() {
                axios.post(this.endpoint + '/replies', { body: this.body })
                    .then(({data}) => {
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

