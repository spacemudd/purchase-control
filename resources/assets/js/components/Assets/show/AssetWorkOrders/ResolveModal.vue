<template>
    <div class="modal is-active" @keyup.esc="closeModal">
        <div class="modal-background" @click="closeModal"></div>
        <form @submit.prevent="sendResolveRequest">
            <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">
                    Resolve: {{ workOrder.id_human }}
                </p>
                <button class="delete" aria-label="close" @click="closeModal"></button>
            </header>
            <section class="modal-card-body">

                <transition enter-active-class="animated fadeIn" leave-active-class="animated fadeOut" mode="out-in">
                    <loading-screen size="is-small" v-if="isLoading"></loading-screen>
                    <div class="columns is-multiline" v-else>
                            <!--<div class="column is-12">-->
                                <!--<div class="field">-->
                                    <!--<label class="label">{{ $t('words.purchase-order') }}</label>-->
                                    <!--<div class="control">-->

                                        <!--<template v-if="!purchaseOrder">-->
                                            <!--<simple-search-->
                                                    <!--search-endpoint="purchase-orders"-->
                                                    <!--@selectedPurchaseOrder="setPurchaseOrder"-->
                                            <!--&gt;</simple-search>-->
                                        <!--</template>-->
                                        <!--<template v-else>-->
                                            <!--<input type="text" class="input is-fullwidth" :value="purchaseOrderDisplayName" readonly>-->
                                        <!--</template>-->

                                        <!--<p class="help">-->
                                            <!--(Optional) Select the purchase order responsible for resolving this work order.-->
                                        <!--</p>-->
                                    <!--</div>-->
                                <!--</div>-->
                            <!--</div>-->

                            <div class="column is-12">
                                <div class="field">
                                    <b-switch v-model="replaced">Asset is replaced</b-switch>
                                </div>
                                <b-field label="New Manufacturer Serial Number" v-if="replaced">
                                    <b-input type="text" autofocus="on" v-model="newManufacturerSerialNumber" required></b-input>
                                </b-field>
                            </div>

                            <div class="column is-12">
                                <div class="field">
                                    <label class="label">{{ $t('words.comment') }} <span class="has-text-danger">*</span></label>
                                    <div class="control">
                                        <textarea type="text" class="textarea" v-model="comment" rows="5"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                </transition>
            </section>
            <transition leave-active-class="animated fadeOut">
                <footer class="modal-card-foot">
                    <button class="button is-success"
                            :class="{'is-loading': isSending}">{{ $t('words.save') }}</button>
                    <button class="button" @click="closeModal">{{ $t('words.cancel') }}</button>
                </footer>
            </transition>
        </div>
        </form>
    </div>
</template>

<script>
    export default {
        props: {
            workOrder: {
                require: true,
            }
        },
        data() {
            return {
                isSending: false,
                isLoading: false,
                purchaseOrder: null,

                replaced: false,
                newManufacturerSerialNumber: '',
                comment: 'resolved',
            }
        },
        mounted() {

        },
        computed: {
            purchaseOrderDisplayName() {
                if(this.purchaseOrder) {
                    return this.purchaseOrder.order_number + ' - ' + this.purchaseOrder.status;
                }
            }
        },
        methods: {
            closeModal() {
                this.$emit('closeModal');
            },
            setPurchaseOrder(purchaseOrder) {
                this.purchaseOrder = purchaseOrder;
            },
            sendResolveRequest() {
                this.isSending = true;

                if(this.purchaseOrder) {
                    var purchaseOrder = this.purchaseOrder.id;
                } else {
                    var purchaseOrder = null;
                }

                if(!this.comment) {
                    this.$toast.open({
                        type: 'is-warning',
                        message: 'Please fill the comment',
                    })
                    return false;
                }

                if(this.replaced) {
                    if(!this.newManufacturerSerialNumber) {
                        this.$toast.open({
                            type: 'is-warning',
                            message: 'Please fill the new manufacturer serial number',
                        });
                        return false;
                    }
                }

                if(this.replaced) {
                    var requestData = {
                        id: this.workOrder.id,
                        comment: this.comment,
                        purchase_order_id: purchaseOrder,
                        manufacturer_serial_number: this.newManufacturerSerialNumber,
                    }
                } else {
                    var requestData = {
                        id: this.workOrder.id,
                        comment: this.comment,
                        purchase_order_id: purchaseOrder,
                    }
                }

                axios.post(this.apiUrl() + '/assets/work-orders/resolve', requestData).then(response => {
                    this.isSending = false;
                    this.$emit('successCreated');
                    this.closeModal();
                })
            }
        },
    }
</script>

<style lang="sass" scoped>
    .control.has-icons-right .input, .control.has-icons-right .select select
        padding-right: 4.25em

    .modal-card-body
        min-height: 310px

    .dropdown-content
        position: fixed

</style>
