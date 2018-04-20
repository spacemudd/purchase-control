<template>
    <div>
        <div class="columns is-vcentered">
            <div class="column" v-if="purchaseOrder.status_human === 'draft'">

                <!-- Modals -->
                <b-modal :active.sync="showNewItemModal">
                    <purchase-order-item-new-modal :purchase-order-id="purchaseOrder.id"></purchase-order-item-new-modal>
                </b-modal>

                <div class="has-text-right">
                    <button class="button is-small is-primary" @click="showNewItemModal=true">
                        <span class="icon is-small">
                            <i class="fa fa-plus-square"></i>
                        </span>
                        <span>{{ $t('words.new-item') }}</span>
                    </button>
                </div>
            </div>
        </div>

                <loading-screen v-if="isLoading" size="is-medium"></loading-screen>
                <div key="working-screen" v-else>
                    <table class="table is-fullwidth is-size-7">
                        <thead>
                        <tr>
                            <th>{{ $t('words.code') }}</th>
                            <th>{{ $t('words.description') }}</th>
                            <th>{{ $t('words.unit-price') }}</th>
                            <th>{{ $t('words.quantity') }}</th>
                            <th>{{ $t('words.total') }}</th>
                            <th class="has-text-right">{{ $t('words.actions') }}</th>
                        </tr>
                        </thead>
                            <tbody>
                                    <tr v-for="(item, index) in purchaseOrderItems">
                                        <td>{{ item.item.code }}</td>
                                        <td>{{ item.item.description }}</td>
                                        <td>{{ item.unit_price }}</td>
                                        <td>{{ item.qty }}</td>
                                        <td>{{ item.total }}</td>
                                        <td class="has-text-right">
                                            <a  v-if="purchaseOrder.status_human == 'draft'"
                                                @click="deleteItem(item.id)"
                                                class="button is-danger is-small">
                                                <span class="icon is-small">
                                                    <i class="fa fa-ban"></i>
                                                </span>
                                                <span>{{ $t('words.delete') }}</span>
                                            </a>
                                        </td>
                                    </tr>
                            </tbody>
                    </table>
                </div>
    </div>
</template>

<script>
    export default {
        props: {
            purchaseOrderId: {
                type: Number,
                required: true
            },
            purchaseOrder: {
                type: Object,
            }
        },
        data() {
            return {
                purchaseOrderItems: null,
                isLoading: true,
                formLoading: false,
                errorBag: [],
                showNewServiceItemModal: false,
                purchaseOrderServiceItems: [],

                showNewItemModal: false,
            }
        },
        mounted() {
            this.getPurchaseOrderItems(this.purchaseOrderId);
            // this.getPurchaseOrderServiceItems(this.purchaseOrderId);
        },
        methods: {
            getPurchaseOrderServiceItems(purchase_order_id) {
                this.isLoading = true;
                axios.post(this.apiUrl() + '/purchase-orders/service-items', {
                    purchase_order_id: purchase_order_id
                }).then(response => {
                    this.purchaseOrderServiceItems = response.data;

                    this.purchaseOrderServiceItems.map(item => {
                        item.loading = false;
                    });

                    this.isLoading = false;
                })
            },
            getPurchaseOrderItems(purchase_order_id) {
                this.isLoading = true;
                axios.get(this.apiUrl() + '/procedures/purchase-orders/' + purchase_order_id + '/items').then(response => {
                    this.purchaseOrderItems = response.data;

                    this.purchaseOrderItems.map(item => {
                        item.loading = false;
                    });

                    this.isLoading = false;
                })
            },
            deleteItem(itemId) {
                this.isLoading = true;

                axios.delete(this.apiUrl() + '/procedures/purchase-orders/' + this.purchaseOrderId + '/items/' + itemId + '/delete').then(response => {
                    this.getPurchaseOrderItems(this.purchaseOrderId) // it also sets isLoading to true
                }).catch(error => {
                    this.isLoading = false;
                    console.log('Error occurred while deleting an item');
                })
            },
            deleteServiceItem(itemId) {
                this.isLoading = true;

                axios.delete(this.apiUrl() + '/purchase-orders/service-items/delete', {
                    params: {
                        id: itemId,
                    }
                }).then(response => {
                    this.getPurchaseOrderServiceItems(this.purchaseOrderId) // it also sets isLoading to true
                }).catch(error => {
                    this.isLoading = false;
                    console.log('Error occurred while deleting an item');
                })
            },
            serviceItemReceived(index, item) {
                this.isLoading = true;

                axios.post(this.apiUrl() + '/purchase-orders/service-items/receive', {
                    id: item.id
                })
                    .then(response => {
                        this.getPurchaseOrderServiceItems(this.purchaseOrderId) // it also sets isLoading to true
                    }).catch(error => {
                    this.isLoading = false;
                    this.errorBag = error.response.data.errors;
                    console.log(error.response.data.message)
                })
            },
            itemReceived(index, item) {
                this.itemLoading(index, item, true);

                axios.post(this.apiUrl() + '/procedures/purchase-orders/' + this.purchaseOrderId + '/items/' + item.id + '/received', {
                        purchase_order_id: item.purchase_order_id,
                        id: item.id
                    })
                     .then(response => {
                        this.itemLoading(index, item, false);
                        this.markItemReceived(index, item);
                     }).catch(error => {
                        this.itemLoading(index, item, false);
                        this.errorBag = error.response.data.errors;
                        alert(error.response.data.message + ' The MSN might be duplicated.');
                     })
            },
            /**
             * Sets the loading status for the record.
             *
             * @param int index
             * @param arr item
             * @param bool loading
             */
            itemLoading(index, item, loading) {
                item.loading = loading
                this.purchaseOrderItems.splice(index, 1)
                this.purchaseOrderItems.splice(index, 0, item)
            },
            markItemReceived(index, item) {
                item.received_at = true
                this.purchaseOrderItems.splice(index, 1)
                this.purchaseOrderItems.splice(index, 0, item)
            },
            canReceive(item) {
                if(this.purchaseOrder.status == 'committed') {
                    if( item.received_at) {
                        return false;
                    }
                    return true;
                }

                return false;
            },
            canEdit(item) {
//                if(this.purchaseOrder.status != 'draft') {
//                    return false;
//                }

                if(item.received_at) {
                    return false;
                }

                return true;
            },
            showEditReceiptItemModal(item) {
                this.$store.commit('PurchaseOrder/setEditingReceiptItem', {
                    receiptItemId: item.id,
                    manufacturerSerialNumber: item.manufacturer_serial_number,
                    systemTagNumber: item.system_tag_number,
                    financeTagNumber: item.finance_tag_number,
                    unitPrice: item.unit_price,
                    warranty: null,
                    warrantType: null,
                });

                this.$store.commit('PurchaseOrder/viewEditReceiptModal', true);
            },
            toggleNewServiceItemModal() {
                this.showNewServiceItemModal = ! this.showNewServiceItemModal;
            }
        }
    }
</script>
