<template>
    <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">{{ $t('words.edit') }}</p>
            </header>
        <form @submit.prevent="submit">
            <section class="modal-card-body">
                <loading-screen v-if="isLoading"></loading-screen>
                <div class="columns is-multiline" v-else>
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
                            <label class="label">{{ $t('words.departments') }} <span class="has-text-danger">*</span></label>
                            <div class="control">
                                <input v-if="department" type="text" class="input" :value="departmentName" readonly
                                       @click="department = null" required>
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
                                   @click="vendor = null" required>
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

                                <p class="help is-danger" v-if="errors.has('purchaseOrderDeliveryDate')">
                                    {{ $t('validation.required', {attribute: $t('words.delivery-date')}) }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <footer class="modal-card-foot">
                <button class="button is-success"
                        :class="{'is-loading': isLoading}">
                    {{ $t('words.save') }}
                </button>
                <button class="button" @click="$parent.close">{{ $t('words.cancel') }}</button>
            </footer>
        </form>
    </div>
</template>

<script>
    import moment from "moment";

    export default {
        props: {
            purchaseOrderId: {
                type: Number,
                required: true,
            }
        },
        data() {
            return {
                isLoading: true,

                mainOrderNumber: null,
                purchaseOrderNumber: null,
                purchaseOrderDate: null,
                purchaseOrderDeliveryDate: null,
                vendorDeliveryNumber: null,
                vendor: null,
                department: null,
                employee: null,
            }
        },
        mounted() {
            this.getPurchaseOrder()
        },
        computed: {
            vendorName() {
                return this.vendor.code + ' - ' + this.vendor.description;
            },
            departmentName() {
                return this.department.code + ' - ' + this.department.description;
            },
            employeeName() {
                return this.employee.code + ' - ' + this.employee.name;
            },
        },
        methods: {
            getPurchaseOrder() {
                axios.post(this.apiUrl() + '/purchase-orders/show', {
                    id: this.purchaseOrderId,
                }).then(response => {

                    this.vendor = response.data.vendor;
                    this.department = response.data.department;
                    this.employee = response.data.employee;
                    this.purchaseOrderNumber = response.data.order_number;
                    this.vendorDeliveryNumber = response.data.delivery_number;
                    this.mainOrderNumber = response.data.main_order_number;
                    this.purchaseOrderDate = response.data.date ? moment(response.data.date, 'YYYY-MM-DD').toDate() : null,
                    this.purchaseOrderDeliveryDate = response.data.delivery_date ? moment(response.data.delivery_date, 'YYYY-MM-DD').toDate() : null,

                    this.isLoading = false;
                }).catch(error => {
                    alert(error.response.data.message);
                    this.isLoading = false;
                })
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
            submit() {
                let formData = {
                    vendor_id: this.vendor.id,
                    department_id: this.department.id,
                    employee_id: this.employee.id,

                    order_number: this.purchaseOrderNumber,
                    delivery_number: this.vendorDeliveryNumber,
                    main_order_number: this.mainOrderNumber,

                    date: this.purchaseOrderDate ? moment(this.purchaseOrderDate).format('DD/MM/YYYY') : '',
                    delivery_date: this.purchaseOrderDeliveryDate ? moment(this.purchaseOrderDeliveryDate).format('DD/MM/YYYY') : '',
                };

                this.isLoading = true;

                axios.put(this.apiUrl() + '/purchase-orders/' + this.purchaseOrderId + '/update', formData)
                    .then(() => {
                        window.location.reload();
                    }).catch(error => {
                        alert(error.response.data.message);
                });
            }
        },
    }
</script>

<style lang="sass" scoped>
    .modal-card-body
        min-height: 476px
</style>
