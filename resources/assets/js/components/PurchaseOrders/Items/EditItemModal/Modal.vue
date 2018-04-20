<!--
    @author Shafiq al-Shaar (shafiqshaar@gmail.com)
-->
<template>
    <div class="modal is-active" v-if="open" @keyup.esc="closeModal">
        <div class="modal-background" @click="closeModal"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">
                    {{ $t('words.edit-receipt-item') }}
                </p>
                <button class="delete" aria-label="close" @click="closeModal"></button>
            </header>
            <section class="modal-card-body">

                <transition enter-active-class="animated fadeIn" leave-active-class="animated fadeOut" mode="out-in">
                        <div v-if="$anyLoading()" key="loading" style="height:210px;">
                            <loading-screen size="is-small"></loading-screen>
                        </div>


                        <div v-if="!$anyLoading()" key="form">
                            <div key="form-view" class="columns is-multiline">
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

                                <!--
                                TODO: Enable calculating back the warranty/type.
                                <div class="column is-6">
                                    <div class="field">
                                        <label class="label">{{ $t('words.warranty') }} <span class="has-text-danger">*</span></label>
                                        <div class="control">
                                            <input name="warrantyPeriod"
                                                   class="input"
                                                   type="number"
                                                   step="1"
                                                   v-validate="'required'"
                                                   v-model="warranty">

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
                                                <select v-model="warrantyType" class="form-control">
                                                    <option value="year">{{ $t('words.year') }}</option>
                                                    <option value="month">{{ $t('words.month') }}</option>
                                                    <option value="week">{{ $t('words.week') }}</option>
                                                    <option value="day">{{ $t('words.day') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                -->


                            </div>
                        </div>
                </transition>
            </section>
            <footer class="modal-card-foot">
                <button class="button is-success"
                        :class="{'is-loading': $anyLoading()}"
                        @click.prevent="submitForm">{{ $t('words.save') }}</button>
                <button class="button" @click="closeModal">{{ $t('words.cancel') }}</button>
            </footer>
        </div>
    </div>
</template>

<script>
    import SimpleSearch from './../../../SimpleSearch/ReturnIdRevamp/ReturnIdRevamp.vue';

    export default {
        components: {
            SimpleSearch,
        },
        props: {
        },
        data() {
            return {

            }
        },
        mounted() {
            //
        },
        computed: {
            open() {
                return this.$store.getters['PurchaseOrder/showEditReceiptItemModal'];
            },
            currentView() {
                return this.$store.getters['PurchaseOrder/currentView'];
            },
            manufacturerSerialNumber: {
                get() {
                    return this.$store.getters['PurchaseOrder/editingManufacturerSerialNumber'];
                },
                set(value) {
                    return this.$store.commit('PurchaseOrder/setEditingManufacturerSerialNumber', value);
                }
            },
            systemTagNumber: {
                get() {
                    return this.$store.getters['PurchaseOrder/editingSystemTagNumber'];
                },
                set(value) {
                    return this.$store.commit('PurchaseOrder/setEditingSystemTagNumber', value);
                }
            },
            financeTagNumber: {
                get() {
                    return this.$store.getters['PurchaseOrder/editingFinanceTagNumber'];
                },
                set(value) {
                    return this.$store.commit('PurchaseOrder/setEditingFinanceTagNumber', value);
                }
            },
            unitPrice: {
                get() {
                    return this.$store.getters['PurchaseOrder/editingUnitPrice'];
                },
                set(value) {
                    return this.$store.commit('PurchaseOrder/setEditingUnitPrice', value);
                }
            },
            warranty: {
                get() {
                    return this.$store.getters['PurchaseOrder/editingWarranty'];
                },
                set(value) {
                    return this.$store.commit('PurchaseOrder/setEditingWarranty', value);
                }
            },
            warrantyType: {
                get() {
                    return this.$store.getters['PurchaseOrder/editingWarrantyType'];
                },
                set(value) {
                    return this.$store.commit('PurchaseOrder/setEditingWarrantyType', value);
                }
            },
        },
        mounted() {

        },
        methods: {
            closeModal() {
                //
                this.$store.commit('PurchaseOrder/viewEditReceiptModal', false);
            },
            submitForm() {
                this.$store.dispatch('PurchaseOrder/submitPartialEditReceiptItem');
            }
        },
    }
</script>

<style lang="sass">
    .control.has-icons-right .input, .control.has-icons-right .select select
        padding-right: 4.25em

    .modal-card-body
        min-height: 210px
</style>
