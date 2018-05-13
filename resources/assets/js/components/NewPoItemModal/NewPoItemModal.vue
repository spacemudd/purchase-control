<template>
    <b-modal :active.sync="show">
        <modal></modal>
    </b-modal>
</template>

<script>
    import Modal from './Modal';

    export default {
        component: {
            Modal,
        },
        data() {
            return {
                //
            }
        },
        computed: {
            show: {
                get() {
                    return this.$store.getters['PurchaseOrdersItem/showNewModal'];
                },
                set(value) {
                    return this.$store.commit('PurchaseOrdersItems/showNewModal', value);
                },
            },
        },
    }
</script>
