<template>
    <b-modal :active.sync="show">
        <new-modal></new-modal>
    </b-modal>
</template>

<script>
    import NewModal from './NewModal';

    export default {
        components: {
            NewModal,
        },
        data() {
            return {

            }
        },
        computed: {
            show: {
                get() {
                    return this.$store.getters['PurchaseOrderItem/showModal'];
                },
                set(value) {
                    return this.$store.commit('PurchaseOrderItem/showModal', value);
                },
            },
        },
    }
</script>
