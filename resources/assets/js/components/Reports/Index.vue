<template>
    <div class="columns">

        <div class="column is-4">
            <stock-report-builder></stock-report-builder>
        </div>

        <div class="column is-4">
            <asset-issuance-builder></asset-issuance-builder>
        </div>

        <div class="column is-4">
            <inventory-auditing></inventory-auditing>
        </div>

    </div>
</template>

<script>
    import StockReportBuilder from './Modules/StockReport/Builder.vue';
    import AssetIssuanceBuilder from './Modules/AssetIssuanceReport/Builder.vue';
    import InventoryAuditing from './Modules/InventoryAuditing/Builder.vue';

    export default {
        components: {
            StockReportBuilder,
            AssetIssuanceBuilder,
            InventoryAuditing,
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
            //
        }
    }
</script>

<style lang="sass">
    //
</style>
