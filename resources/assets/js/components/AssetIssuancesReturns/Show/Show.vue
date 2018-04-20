<template>
    <div>

        <div v-if="$isLoading('GETTING_FULL_ASSET_ISSUANCE_RETURN')">
            <loading-screen size='is-large'></loading-screen>
        </div>
        <div v-else>

            <b-tabs>
                <b-tab-item :label="$t('words.returned-items')">
                    <asset-issuance-return></asset-issuance-return>
                </b-tab-item>
                <b-tab-item label="Log">
                    <audit-trail resource-type="issuance-returns"
                                 :resource-id.number="issuanceReturnId">
                    </audit-trail>
                </b-tab-item>
            </b-tabs>

        </div>

    </div>
</template>

<script>
    import AssetIssuanceReturn from './AssetIssuanceReturn.vue';
    import AssetIssuanceReturnItems from './../Items/ReturnedItems/Browse.vue';

    export default {
        props: {
            issuanceReturnId: {
                type: Number,
                required: true,
            },
        },
        components: {
            AssetIssuanceReturn,
            AssetIssuanceReturnItems,
        },
        data() {
            return {
                //
            }
        },
        mounted() {
            this.$store.dispatch('AssetIssuanceReturn/getFullAssetIssuanceReturn', this.issuanceReturnId);
        },
        methods: {
            //
        }
    }
</script>
