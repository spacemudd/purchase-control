<template>
    <div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">{{ cardTitle }}</p>
            </header>
            <section class="modal-card-body">
                <div class="columns is-vcentered">
                    <div class="column is-12">
                        <p class="has-text-centered">
                            {{ message }}
                        </p>
                    </div>
                </div>
            </section>
        </div>
        <footer class="modal-card-foot">
            <button class="button" type="button" @click="$parent.close()">{{ $t('words.cancel') }}</button>
            <button class="button is-success" @click="emitResponse">{{ buttonText }}</button>
        </footer>
    </div>
</template>

<script>
    export default {
        props: {
            message: {
                type: String,
                required: true,
            },
            cardTitle: {
                type: String,
                required: true,
            },
            buttonText: {
                type: String,
                required: true,
            }
        },
        data() {
            return {
                //
            }
        },
        mounted() {
            //
        },
        methods: {
            emitResponse() {
                this.$emit('confirmed');
            }
        }
    }
</script>

<style lang="sass">
    .modal-card-body
        min-height: unset
</style>
