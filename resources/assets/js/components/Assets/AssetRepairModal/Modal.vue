<template>
    <div class="modal is-active" @keyup.esc="closeModal">
        <div class="modal-background" @click="closeModal"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">
                    {{ $t('words.new-work-order') }}
                </p>
                <button class="delete" aria-label="close" @click="closeModal"></button>
            </header>
            <create-work-order v-if="creatingWorkOrderView" :assetId.number="assetId" @closeModal="closeModal"></create-work-order>
        </div>
    </div>
</template>

<script>
    import CreateWorkOrder from './CreatingWorkOrder.vue';

    export default {
        components: {
            CreateWorkOrder,
        },
        props: {
            assetId: {
                require: true,
            }
        },
        data() {
            return {
            }
        },
        mounted() {

        },
        computed: {
            creatingWorkOrderView() {
                return Boolean(this.$store.getters['AssetRepairFacility/currentView'] === 'create-work-order');
            },
        },
        mounted() {
            this.$store.commit('AssetRepairFacility/setAssetId', this.assetId);
        },
        methods: {
            closeModal() {
                this.$emit('closeModal');
            },
            submitForm() {
                this.$store.dispatch('Asset/submitChangeStatus', this.newChangedStatus);
            }
        },
    }
</script>

<style lang="sass">
    .control.has-icons-right .input, .control.has-icons-right .select select
        padding-right: 4.25em

        .modal-card-body
            min-height: 310px

    .dropdown-content
        position: fixed;

</style>
