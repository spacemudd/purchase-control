<template>
    <loading-screen v-if="$isLoading('GETTING_PURCHASE_REQUEST')" size="is-large"></loading-screen>
    <span v-else>
        <request-header :can-send-requests-to-purchasing="canSendRequestsToPurchasing"></request-header>

        <b-tabs>
            <b-tab-item label="Items">
                <request-items :request-id.number="requestId"></request-items>
            </b-tab-item>
            <b-tab-item label="Log">
                <audit-trail resource-type="requests"
                             :resource-id.number="requestId">
                </audit-trail>
            </b-tab-item>
        </b-tabs>
    </span>
</template>

<script>
    export default {
        props: {
            requestId: {
                type: Number,
                required: true,
            },
            canSendRequestsToPurchasing: {
                type: Number,
                required: false,
                default: 0,
            },
        },
        mounted() {
            this.$store.dispatch('PurchaseControlRequest/getResource', this.requestId);
        },
    }
</script>
