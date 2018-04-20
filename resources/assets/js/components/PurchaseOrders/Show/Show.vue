<template>
    <loading-screen size='is-large' v-if="poLoading"></loading-screen>
    <div v-else>
        <purchase-order :purchaseOrder="purchaseOrder"
                        @refresh="getPoFull()">
        </purchase-order>

        <b-tabs>
            <b-tab-item :label="$t('words.receipt-items')">
                <purchase-order-items
                        :purchase-order="purchaseOrder"
                        :purchase-order-id="purchaseOrder.id">
                </purchase-order-items>
            </b-tab-item>
            <b-tab-item label="Log">
                <audit-trail resource-type="purchase-orders"
                             :resource-id.number="purchaseOrder.id">
                </audit-trail>
            </b-tab-item>
        </b-tabs>
    </div>
</template>

<script>
    import PurchaseOrder from './PurchaseOrder.vue';
//    import PurchaseOrderItems from './Items.vue';

    export default {
        props: {
            purchaseOrderId: {
                type: Number,
                required: true,
            },
        },
        components: {
            PurchaseOrder,
//            PurchaseOrderItems,
        },
        data() {
            return {
                poLoading: true,

                purchaseOrder: [],
            }
        },
        mounted() {
            this.getPoFull();
        },
        methods: {
            getPoFull() {
                this.poLoading = true;

                axios.post(this.apiUrl() + '/purchase-orders/show', {
                    id: this.purchaseOrderId,
                }).then(response => {
                    this.purchaseOrder = response.data;
                    this.poLoading = false;
                })
            },
        }
    }
</script>
