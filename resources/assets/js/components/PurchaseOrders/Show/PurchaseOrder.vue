<template>
    <div class="box">
        <!-- Modals -->
        <b-modal :active.sync="confirmCommitModal">
            <confirm-modal @confirmed="commitPo"
                           :message="$t('messages.are-you-sure')"
                           :button-text="$t('words.approve')"
                           :card-title="$t('words.approve')">
            </confirm-modal>
        </b-modal>

        <b-modal :active.sync="confirmVoidModal">
            <confirm-modal @confirmed="voidPo"
                           :message="$t('messages.void-confirmation')"
                           :button-text="$t('words.void')"
                           :card-title="$t('words.void')">
            </confirm-modal>
        </b-modal>

        <b-modal :active.sync="showScanningModal">
            <scan-documents :model-id.number="purchaseOrder.id"
                            model-type="PurchaseOrder">
            </scan-documents>
        </b-modal>

        <b-modal :active.sync="showEditModal">
            <edit-purchase-order-modal v-if="canCreate"
                                       :purchase-order-id.number="purchaseOrder.id">
            </edit-purchase-order-modal>
        </b-modal>
        <!-- // Modals -->
        <div class="columns">
            <div class="column is-12">
                <div class="columns">
                    <div class="column is-6">
                        <h1 class="title">
                            {{ purchaseOrderNumber }}
                            <span v-if="!purchaseOrderNumber" class="has-text-grey-lighter">Draft</span>
                        </h1>
                        <p class="subtitle is-6">{{ $t('words.purchase-order-number') }}</p>
                    </div>
                    <div class="column is-6 has-text-right">

                        <a class="button is-small" :href="newSubPoCreateLink">
                            <span class="icon is-small"><i class="fa fa-shopping-cart"></i></span>
                            <span>New Sub PO</span>
                        </a>

                        <button class="button is-small is-warning"
                                @click="showEditModal=true">
                            <span class="icon is-small"><i class="fa fa-pencil"></i></span>
                            <span>{{ $t('words.edit') }}</span>
                        </button>

                        <div class="button is-small" @click="showScanningModal=true">
                            <span class="icon is-small"><i class="fa fa-sticky-note-o"></i></span>
                            <span>Scan</span>
                        </div>

                        <view-all-files-button
                                resource-name="purchase-orders"
                                :resource-id.number="purchaseOrder.id">
                        </view-all-files-button>

                        <template v-if="isDraft">
                            <!--@if(count($purchase_order->items) || count($purchase_order->service_items))-->
                            <button class="button is-success is-small"
                                    :class="{'is-loading': isCommitting}"
                                    @click="confirmCommitModal = true">
                                <span class="icon is-small"><i class="fa fa-check"></i></span>
                                <span>{{ $t('words.approve') }}</span>
                            </button>
                            <!--@endif-->
                        </template>

                        <button v-if="isCommitted"
                                class="button is-small is-danger"
                                :class="{'is-loading': isVoiding}"
                                @click="confirmVoidModal = true">
                            <span class="icon is-small"><i class="fa fa-ban"></i></span>
                            <span>{{ $t('words.void') }}</span>
                        </button>
                    </div>
                </div>
                <hr>
            </div>
        </div>


        <div class="columns">

            <div class="column is-6">
                <table class="table is-size-7 is-fullwidth">
                    <tbody>
                        <tr>
                            <td><strong>{{ $t('words.status') }}</strong></td>
                            <td>
                                <span  class="tag is-capitalized" style="height:unset;">
                                    {{ purchaseOrder.status_human }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>{{ $t('words.main-order-number') }}</strong></td>
                            <td>{{ purchaseOrder.main_order_number }}</td>
                        </tr>
                        <tr>
                            <td><strong>{{ $t('words.purchase-order-date') }}</strong></td>
                            <td>{{ purchaseOrder.date_human }}</td>
                        </tr>
                        <tr>
                            <td><strong>{{ $t('words.employee') }}</strong></td>
                            <td><span v-if="purchaseOrder.employee">{{ purchaseOrder.employee.code }} - {{ purchaseOrder.employee.name }}</span></td>
                        </tr>
                        <tr>
                            <td><strong>{{ $t('words.department') }}</strong></td>
                            <td><span v-if="purchaseOrder.department">{{ purchaseOrder.department.code }} - {{ purchaseOrder.department.description }}</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="column is-6">
                <table class="table is-size-7 is-fullwidth">
                    <colgroup>
                        <col style="width:30%;">
                    </colgroup>
                    <tbody>
                        <tr>
                            <td><strong>{{ $t('words.supplier') }}</strong></td>
                            <td>{{ purchaseOrder.vendor.id }} - {{ purchaseOrder.vendor.name }}</td>
                        </tr>
                        <tr>
                            <td><strong>{{ $t('words.supplier-delivery-number') }}</strong></td>
                            <td>{{ purchaseOrder.delivery_number }}</td>
                        </tr>
                        <tr>
                            <td><strong>{{ $t('words.delivery-date') }}</strong></td>
                            <td>{{ purchaseOrder.delivery_date_human }}</td>
                        </tr>
                        <!--
                        <tr>
                            <td><strong>Attachments</strong></td>
                            <td>
                                <attachments :files="purchaseOrder.files" :resource-name="'purchase-orders'"></attachments>
                            </td>
                        </tr>
                        -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            purchaseOrder: {
                type: Object,
                required: true,
            },
            canCreate: {
                type: Number,
                required: false,
                default: 0,
            },
        },
        computed: {
            purchaseOrderNumber() {
                return this.purchaseOrder.number;
            },
            purchaseOrderStatus() {
                return this.purchaseOrder.status;
            },
            isDraft() {
                // See \App\Models\PurchaseOrder
                return Boolean(this.purchaseOrderStatus == 0);
            },
            isCommitted() {
                // See \App\Models\PurchaseOrder
                return Boolean(this.purchaseOrderStatus == 1);
            },
            newSubPoCreateLink() {
                return window.location + '/sub-po/create';
            }
        },
        data() {
            return {
                confirmCommitModal: false,
                isCommitting: false,

                showScanningModal: false,
                showEditModal: false,

                confirmVoidModal: false,
                isVoiding: false,
            }
        },
        mounted() {
            //
        },
        methods: {
            commitPo() {
                this.confirmCommitModal = false;
                this.isCommitting = true;

                axios.post(this.apiUrl() + '/purchase-orders/commit', {
                    id: this.purchaseOrder.id
                }).then(response => {
                    this.isCommitting = false;
                    this.$emit('refresh');
                }).catch(response => {
                    this.isCommitting = false;
                    alert('Error occurred in committing');
                    this.$emit('refresh');
                })
            },
            voidPo() {
                this.confirmVoidModal = false;
                this.isVoiding = true;

                axios.post(this.apiUrl() + '/purchase-orders/void', {
                    id: this.purchaseOrder.id
                }).then(response => {
                    this.isVoiding = false;
                    this.$emit('refresh');
                }).catch(response => {
                    this.isVoiding = false;
                    alert('Error occurred in voiding');
                    this.$emit('refresh');
                })
            },
        }
    }
</script>
