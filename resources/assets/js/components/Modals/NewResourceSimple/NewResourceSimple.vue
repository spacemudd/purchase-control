<template>
    <div class="modal is-active" v-if="open">
        <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">

                </p>
                <button class="delete" aria-label="close" @click="closeModal"></button>
            </header>
            <section class="modal-card-body">
                ....
            </section>
            <footer class="modal-card-foot">
                <button class="button is-success">{{ $t('words.save') }}</button>
                <button class="button" @click="closeModal">{{ $t('words.cancel') }}</button>
            </footer>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                //
            }
        },
        computed: {
            open() {
                return this.$store.getters['PurchaseOrder/showNewModal'];
            }
        },
        mounted() {
            //
        },
        methods: {
            closeModal() {
                this.$store.commit('PurchaseOrder/setModalActive', false);
            },
        }
    }
</script>

<style lang="sass">
    //
</style>
