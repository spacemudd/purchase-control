<!--
    @author Shafiq al-Shaar (shafiqshaar@gmail.com)
-->
<template>
    <div class="modal is-active" v-if="open" @keyup.esc="closeModal">
        <div class="modal-background" @click="closeModal"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">
                    {{ $t('words.new-receipt-item') }}
                </p>
                <button class="delete" aria-label="close" @click="closeModal"></button>
            </header>
            <section class="modal-card-body">

                <transition enter-active-class="animated fadeIn" leave-active-class="animated fadeOut" mode="out-in">
                        <div v-if="loading" key="loading" style="height:350px;">
                            <loading-screen size="is-small"></loading-screen>
                        </div>

                        <div v-if="!loading" key="form">

                            <error-bag v-if="errorbag" :error-bag="errorbag"></error-bag>

                            <div key="form-view" class="columns is-multiline">
                                <div class="column is-12">
                                    <div class="field">
                                        <label class="label">{{ $t('words.asset-template') }} <span class="has-text-danger">*</span></label>
                                        <div class="control">

                                            <input v-if="assetTemplate"
                                                   type="text" class="input" :value="assetTemplateDisplayName" readonly>
                                            <simple-search
                                                    v-else
                                                    :hyper-linked-results="false"
                                                    :search-endpoint="'asset-templates'"
                                                    size="is-small"
                                                    @selectedRecord="updateAssetTemplate"
                                            ></simple-search>

                                        </div>
                                    </div>
                                </div>

                                <div class="column is-12">
                                    <div class="field">
                                        <label class="label">{{ $t('words.location-code') }}</label>
                                        <div class="control">

                                            <input v-if="location" type="text" class="input" :value="locationDisplayName" readonly>
                                            <simple-search
                                                    v-else
                                                    search-endpoint="locations"
                                                    size="is-small"
                                                    @selectedRecord="updateLocation"
                                            ></simple-search>

                                            <p class="help">
                                                {{ $t('messages.asset-location-help') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="column is-6">
                                    <div class="field">
                                        <label class="label">{{ $t('words.manufacturer-serial-number') }} <span class="has-text-danger">*</span></label>
                                        <div class="control">
                                            <input name="manufacturerSerialNumber"
                                                   class="input"
                                                   :class="{'is-danger': errors.has('manufacturerSerialNumber')}"
                                                   type="text"
                                                   v-model="manufacturerSerialNumber"
                                                   v-validate="'required'">

                                            <p class="help is-danger" v-if="errors.has('manufacturerSerialNumber')">
                                                {{ $t('validation.required', {attribute: $t('words.manufacturer-serial-number')}) }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="column is-6">
                                    <div class="field">
                                        <label class="label">{{ $t('words.system-tag-number') }}</label>
                                        <div class="control">
                                            <input name="systemTagNumber"
                                                   class="input"
                                                   type="text"
                                                   v-model="systemTagNumber">
                                        </div>
                                    </div>
                                </div>

                                <div class="column is-6">
                                    <div class="field">
                                        <label class="label">{{ $t('words.finance-tag-number') }}</label>
                                        <div class="control">
                                            <input name="financeTagNumber"
                                                   class="input"
                                                   type="text"
                                                   v-model="financeTagNumber">
                                        </div>
                                    </div>
                                </div>

                                <div class="column is-6">
                                    <div class="field">
                                        <label class="label">{{ $t('words.unit-price') }} <span class="has-text-danger">*</span></label>
                                        <div class="control">
                                            <input name="unitPrice"
                                                   class="input"
                                                   type="number"
                                                   step="0.01"
                                                   :class="{'is-danger': errors.has('unitPrice')}"
                                                   v-validate="'required'"
                                                   v-model="unitPrice">

                                            <p class="help is-danger" v-if="errors.has('unitPrice')">
                                                {{ $t('validation.required', {attribute: $t('words.unit-price')}) }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="column is-6">
                                    <div class="field">
                                        <label class="label">{{ $t('words.warranty') }} <span class="has-text-danger">*</span></label>
                                        <div class="control">
                                            <input name="warrantyPeriod"
                                                   class="input"
                                                   type="number"
                                                   step="1"
                                                   v-validate="'required'"
                                                   v-model="warrantyPeriod">

                                            <p class="help is-danger" v-if="errors.has('warrantyPeriod')">
                                                {{ $t('validation.required', {attribute: $t('words.warranty')}) }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="column is-6">
                                    <div class="field">
                                        <label class="label">{{ $t('words.warranty-type') }} <span class="has-text-danger">*</span></label>
                                        <div class="control">
                                            <div class="select is-fullwidth">
                                                <select v-model="warrantyPeriodType" class="form-control">
                                                    <option value="year">{{ $t('words.year') }}</option>
                                                    <option value="month">{{ $t('words.month') }}</option>
                                                    <option value="week">{{ $t('words.week') }}</option>
                                                    <option value="day">{{ $t('words.day') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                </transition>
            </section>
            <footer class="modal-card-foot">
                <button class="button is-success"
                        :class="{'is-loading': loading}"
                        @click.prevent="submitForm">{{ $t('words.save') }}</button>
                <button class="button" @click="closeModal">{{ $t('words.cancel') }}</button>
            </footer>
        </div>
    </div>
</template>

<script>
    import ErrorBag from './../../../ErrorBag/Bag.vue';

    export default {
        components: {
            ErrorBag,
        },
        props: {
            purchaseOrderId: {
                type: Number,
                required: true,
            }
        },
        data() {
            return {
                loading: false,

                errorbag: null,

                assetTemplate: null,
                manufacturerSerialNumber: null,
                systemTagNumber: null,
                financeTagNumber: null,
                unitPrice: null,
                warrantyPeriod: 2,
                warrantyPeriodType: 'year',
                location: null,
            }
        },
        mounted() {
            //
        },
        computed: {
            open() {
                return this.$store.getters['PurchaseOrder/showNewReceiptItemModal'];
            },
            assetTemplateDisplayName() {
                if(this.assetTemplate) {
                    return this.assetTemplate.code + ' - ' + this.assetTemplate.description;
                }
            },
            locationDisplayName() {
                if(this.location) {
                    return this.location.code + ' - (' + this.getTrans('words.department') + ': '
                        + this.location.department.code  +  ' - ' + this.location.department.description + ')';
                }
            },
            currentView() {
                return this.$store.getters['PurchaseOrder/currentView'];
            }
        },
        mounted() {

        },
        methods: {
            closeModal() {
                this.assetTemplate = null;
                this.manufacturerSerialNumber = null;
                this.systemTagNumber = null;
                this.financeTagNumber = null;
                this.unitPrice = null;
                this.warrantyPeriod = 2;
                this.warrantyPeriodType = 'year';
                this.location = null;
                this.errorbag = null;
                this.$store.commit('PurchaseOrder/viewNewReceiptModal', false);
            },
            updateAssetTemplate(payload) {
                this.assetTemplate = payload;
                this.unitPrice = payload.unit_price;
            },
            updateLocation(payload) {
                console.log(payload);
                this.location = payload;
            },
            submitForm() {

                if(!this.assetTemplate) {
                    return alert('Please select asset.');
                }
                if(!this.purchaseOrderId) {
                    return alert('Please fill the purchase order number.');
                }
                if(!this.manufacturerSerialNumber) {
                    return alert('Please fill the manufacturer serial number.');
                }
                if(!this.warrantyPeriod) {
                    return alert('Please select a warranty perio.');
                }
                if(!this.warrantyPeriodType) {
                    return alert('Please select a warranty type.');
                }

                this.loading = true;

                let newAsset = {
                    asset_template_id: this.assetTemplate.id,
                    purchase_order_id: this.purchaseOrderId,
                    manufacturer_serial_number: this.manufacturerSerialNumber,
                    system_tag_number: this.systemTagNumber,
                    finance_tag_number: this.financeTagNumber,
                    unit_price: this.unitPrice,
                    warranty: this.warrantyPeriod,
                    warranty_type: this.warrantyPeriodType,
                    location_id: this.location ? this.location.id : null,
                };

                axios.post(this.apiUrl() + '/purchase-orders/' + this.purchaseOrderId + '/items/store', newAsset)
                .then(response => {
                    this.loading = false;
                    this.$emit('successNewItem');
                    this.closeModal();
                }).catch(error => {
                    this.loading = false;
                    this.errorbag = error.response.data;
                });

            }
        },
    }
</script>

<style lang="sass">
    .control.has-icons-right .input, .control.has-icons-right .select select
        padding-right: 4.25em

    .modal-card-body
        min-height: 350px
</style>
