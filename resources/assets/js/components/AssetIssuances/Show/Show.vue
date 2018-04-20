<template>
    <div>

        <div v-if="$isLoading('GETTING_FULL_ASSET_ISSUANCE')">
            <loading-screen size='is-large'></loading-screen>
        </div>
        <div v-else>

            <asset-issuance></asset-issuance>

            <b-tabs>
                <b-tab-item label="Issued Items">
                    <asset-issuance-items></asset-issuance-items>
                </b-tab-item>
                <b-tab-item label="Log">
                    <audit-trail resource-type="asset-issuances"
                                 :resource-id.number="issuanceId">
                    </audit-trail>
                </b-tab-item>
            </b-tabs>

        </div>

    </div>
</template>

<script>
    import AssetIssuance from './AssetIssuance.vue';
    import AssetIssuanceItems from './../Items/IssuedItems/Browse.vue';

    export default {
        props: {
            issuanceId: {
                type: Number,
                required: true,
            },
        },
        components: {
            AssetIssuance,
            AssetIssuanceItems,
        },
        data() {
            return {
                //
            }
        },
        mounted() {
            this.$store.dispatch('AssetIssuance/getFullAssetIssuance', this.issuanceId);
        },
        methods: {
            //
        }
    }
</script>
