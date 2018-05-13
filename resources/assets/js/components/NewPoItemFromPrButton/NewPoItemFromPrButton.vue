<template>
    <button class="button is-small is-primary has-icon" @click="showNewPoItemFromPrModal">
        <span class="icon is-small"><i class="fa fa-plus"></i></span>
        <span>New Item</span>
    </button>
</template>

<script>
    export default {
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
                this.$store.commit('PurchaseOrdersItems/showNewPoItemFromModal', true);
            },
        }
    }
</script>
