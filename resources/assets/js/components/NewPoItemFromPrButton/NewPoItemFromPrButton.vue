<template>
    <button class="button is-small is-primary has-icon" @click="showNewPoItemFromPrModal">
        <span class="icon is-small"><i class="fa fa-plus"></i></span>
        <span>New Item</span>
    </button>
</template>

<script>
    export default {
        props: {
            poId: {
                type: Number,
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
            showNewPoItemFromPrModal() {
                this.$store.commit('PurchaseOrderItem/showModal', true);
                this.$store.commit('PurchaseOrderItem/setPoId', this.poId);
            },
        }
    }
</script>
