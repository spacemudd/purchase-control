<template>
    <div class="modal is-active" @keyup.esc="closeModal">
        <div class="modal-background" @click="closeModal"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">
                    Resolve: {{ workOrder.id }}
                </p>
                <button class="delete" aria-label="close" @click="closeModal"></button>
            </header>
            <section class="modal-card-body">

                <transition enter-active-class="animated fadeIn" leave-active-class="animated fadeOut" mode="out-in">
                    <div v-if="isLoading" key="loading" style="height:250px;">
                        <loading-screen size="is-small"></loading-screen>
                    </div>
                    <div v-if="!isLoading" key="form">
                        <div class="columns is-multiline">
                            <div class="column is-12">
                                <div class="field">
                                    <label class="label">{{ $t('words.purchase-order') }} <span class="has-text-danger">*</span></label>
                                    <div class="control">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </transition>
            </section>
            <transition leave-active-class="animated fadeOut">
                <footer class="modal-card-foot">
                    <button class="button is-success"
                            :class="{'is-loading': isSending}"
                            @click.prevent="sendResolveRequest">{{ $t('words.save') }}</button>
                    <button class="button" @click="closeModal">{{ $t('words.cancel') }}</button>
                </footer>
            </transition>
        </div>
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
                purchaseOrder: null,
                isSending: false,
                isLoading: false,
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
                axios.post(this.apiUrl() + '/assets/work-orders/resolve', {
                    id: this.workOrder.id,
                    purchase_order_id: this.purchaseOrder.id,
                }).then(response => {
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
