<template>
    <div class="modal is-active" v-if="open" @keyup.esc="closeModal">
        <div class="modal-background" @click="closeModal"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">
                    {{ $t('words.new-purchase-order') }}
                </p>
                <button class="delete" aria-label="close" @click="closeModal"></button>
            </header>
            <section class="modal-card-body">

                <transition enter-active-class="animated fadeIn" leave-active-class="animated fadeOut" mode="out-in">
                        <div v-if="$isLoading('CREATING_PURCHASE_ORDER')" key="loading" style="height:450px;">
                            <loading-screen size="is-small"></loading-screen>
                        </div>
                        <div key="form" v-else>
                            <div key="form-view" v-if="currentView == 'form'" class="columns is-multiline">
                                <div class="column is-6">
                                    <div class="field">
                                        <label class="label">{{ $t('words.purchase-order-date') }} <span class="has-text-danger">*</span></label>
                                        <div class="control">
                                            <template>
                                                <b-field>
                                                    <b-datepicker
                                                            placeholder="Type or select a date..."
                                                            icon-pack="fa"
                                                            icon="calendar"
                                                            v-model="purchaseOrderDate"
                                                            :readonly="false">
                                                    </b-datepicker>
                                                </b-field>
                                            </template>

                                            <p class="help is-danger" v-if="errors.has('purchaseOrderDate')">
                                                {{ $t('validation.required', {attribute: $t('words.purchase-order-date')}) }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="column is-6">
                                    <div class="field">
                                        <label class="label">{{ $t('words.department') }} <span class="has-text-danger">*</span></label>
                                        <div class="control">
                                            <input v-if="department" type="text" class="input" :value="departmentName" readonly
                                                   @click="department = null">
                                            <simple-search
                                                    v-else
                                                    :size="'is-small'"
                                                    search-endpoint="departments"
                                                    @selectedRecord="updateDepartment"
                                            ></simple-search>
                                        </div>
                                    </div>
                                </div>

                                <div class="column is-6">
                                    <div class="field">
                                        <label class="label">{{ $t('words.employee') }} <span class="has-text-danger">*</span></label>
                                        <input v-if="employee" type="text" class="input" :value="employeeName" readonly
                                               @click="employee = null">
                                        <simple-search
                                                v-else
                                                :size="'is-small'"
                                                search-endpoint="employees"
                                                @selectedRecord="updateEmployee"
                                        ></simple-search>
                                    </div>
                                </div>

                                <div class="column is-6">
                                    <div class="field">
                                        <label class="label">{{ $t('words.vendor') }} <span class="has-text-danger">*</span></label>
                                        <input v-if="vendor" type="text" class="input" :value="vendorName" readonly
                                               @click="vendor = null">
                                        <simple-search
                                                v-else
                                                :size="'is-small'"
                                                search-endpoint="vendors"
                                                @selectedRecord="updateVendor"
                                        ></simple-search>
                                    </div>
                                </div>

                                <div class="column is-6">
                                    <div class="field">
                                        <label class="label">{{ $t('words.vendor-delivery-number') }}</label>
                                        <div class="control">
                                            <input class="input" type="text" name="vendorDeliveryNumber" v-model="vendorDeliveryNumber">
                                        </div>
                                    </div>
                                </div>

                                <div class="column is-6">
                                    <div class="field">
                                        <label class="label">{{ $t('words.delivery-date') }}</label>
                                        <div class="control">
                                            <template>
                                                <b-field>
                                                    <b-datepicker
                                                            placeholder="Type or select a date..."
                                                            icon-pack="fa"
                                                            icon="calendar"
                                                            v-model="purchaseOrderDeliveryDate"
                                                            :readonly="false">
                                                    </b-datepicker>
                                                </b-field>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <new-purchase-order-view
                                    key="completed-view"
                                    v-if="currentView == 'completed'"
                                    :purchase-order="newlyCreatedPurchaseOrder"
                                    @close="closeModal"
                                    >

                            </new-purchase-order-view>
                        </div>
                </transition>
            </section>
            <transition leave-active-class="animated fadeOut">
                <footer class="modal-card-foot" v-if="!(currentView === 'completed')">
                    <button class="button is-success"
                            :class="{'is-loading': $isLoading('CREATING_PURCHASE_ORDER')}"
                            @click.prevent="submitForm">{{ $t('words.save') }}</button>
                    <button class="button" @click="closeModal">{{ $t('words.cancel') }}</button>
                </footer>
            </transition>
        </div>
    </div>
</template>

<script>
    export default {
        components: {

        },
        data() {
            return {
            }
        },
        mounted() {

        },
        computed: {
            open() {
                return this.$store.getters['PurchaseOrder/showNewModal'];
            },
            purchaseOrderNumber: {
                get() {
                    return this.$store.getters['PurchaseOrder/purchaseOrderNumber'];
                },
                set(value) {
                    this.$store.commit('PurchaseOrder/updatePurchaseOrderNumber', value);
                }
            },
            purchaseOrderDate: {
                get() {
                    return this.$store.getters['PurchaseOrder/purchaseOrderDate'];
                },
                set(value) {
                    this.$store.commit('PurchaseOrder/updatePurchaseOrderDate', value);
                }
            },
            vendor: {
                get() {
                    return this.$store.getters['PurchaseOrder/vendor'];
                },
                set(value) {
                    this.$store.commit('PurchaseOrder/updateVendor', value);
                }
            },
            vendorName() {
                let vendor = this.$store.getters['PurchaseOrder/vendor'];

                return vendor.id + ' - ' + vendor.name;
            },
            department: {
                get() {
                    return this.$store.getters['PurchaseOrder/department'];
                },
                set(value) {
                    this.$store.commit('PurchaseOrder/updateDepartment', value);
                }
            },
            departmentName() {
                let department = this.$store.getters['PurchaseOrder/department'];

                return department.code + ' - ' + department.description;
            },
            employee: {
                get() {
                    return this.$store.getters['PurchaseOrder/employee'];
                },
                set(value) {
                    this.$store.commit('PurchaseOrder/updateEmployee', value);
                }
            },
            employeeName() {
                let employee = this.$store.getters['PurchaseOrder/employee'];
                return employee.code + ' - ' + employee.name;
            },
            purchaseOrderDeliveryDate: {
                get() {
                    return this.$store.getters['PurchaseOrder/purchaseOrderDeliveryDate'];
                },
                set(value) {
                    this.$store.commit('PurchaseOrder/updatePurchaseOrderDeliveryDate', value);
                }
            },
            vendorDeliveryNumber: {
                get() {
                    return this.$store.getters['PurchaseOrder/vendorDeliveryNumber'];
                },
                set(value) {
                    this.$store.commit('PurchaseOrder/updateVendorDeliveryNumber', value);
                }
            },
            newlyCreatedPurchaseOrder: {
                get() {
                    return this.$store.getters['PurchaseOrder/newlyCreatedPurchaseOrder'];
                }
            },
            currentView() {
                return this.$store.getters['PurchaseOrder/currentView'];
            }
        },
        mounted() {
            this.$store.commit('PurchaseOrder/updatePurchaseOrderDate', new Date());
        },
        methods: {
            closeModal() {
                this.$store.commit('PurchaseOrder/setModalActive', false);
            },
            updatePurchaseOrderNumber(e) {
                this.$store.commit('PurchaseOrder/updatePurchaseOrderNumber', purchaseOrderNumber);
            },
            updatePurchaseOrderDate(e) {
                this.$store.commit('PurchaseOrder/updatePurchaseOrderDate', purchaseOrderDate);
            },
            updateVendor(vendor) {
                this.vendor = vendor;
            },
            updateEmployee(employee) {
                this.employee = employee;
            },
            updateDepartment(department) {
                this.department = department;
            },
            submitForm() {
                this.$store.dispatch('PurchaseOrder/submitPo');
            }
        },
    }
</script>

<style lang="sass" scoped>
    .control.has-icons-right .input, .control.has-icons-right .select select
        padding-right: 4.25em

    .modal-card-body
        min-height: 400px
</style>
