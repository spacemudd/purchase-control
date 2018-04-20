<!--
  - Copyright (c) 2018 - Clarastars, LLC - All Rights Reserved.
  -
  - Unauthorized copying of this file via any medium is strictly prohibited.
  - This file is a proprietary of Clarastars LLC and is confidential.
  -
  - https://clarastars.com - info@clarastars.com
  -
  -->

<template>
    <div>
        <form @submit.prevent="sendFormRequest()">
            <div class="columns">
                <div class="column is-6">
                    <p class="title is-6">Requested By</p>
                    <div class="notification">
                        <b-field label="Employee ID">
                            <!-- If selected. -->
                            <b-autocomplete v-if="!selectedEmployeeBy"
                                            v-model="employeeBySearchCode"
                                            field="code"
                                            :data="filteredDataObj"
                                            @select="option => selectedEmployeeBy = option"
                                            :loading="$isLoading('FETCHING_EMPLOYEES')"
                                            required>
                                <template slot="empty">No results found</template>
                            </b-autocomplete>
                            <!-- When selected -->
                            <input v-else
                                   type="text"
                                   class="input"
                                   :value="selectedEmployeeBy.code + ' - ' + selectedEmployeeBy.name"
                                   @click="emptyEmployeeBy"
                                   required
                                   readonly>
                        </b-field>

                        <b-field label="Cost Center">
                            <!-- If selected. -->
                            <b-autocomplete v-if="!selectedCostCenter"
                                            v-model="costCenterSearchCode"
                                            field="code"
                                            :data="filteredCostCenters"
                                            @select="option => selectedCostCenter = option"
                                            :loading="$isLoading('FETCHING_COST_CENTERS')"
                                            required>
                                <template slot="empty">No results found</template>
                            </b-autocomplete>
                            <!-- When selected -->
                            <input v-else
                                   type="text"
                                   class="input"
                                   :value="selectedCostCenter.code + ' - ' + selectedCostCenter.description"
                                   @click="emptyCostCenter"
                                   required
                                   readonly>
                        </b-field>
                    </div>
                </div>
                <div class="column is-6">
                    <p class="title is-6">Requested For</p>
                    <div class="notification">
                        <b-field label="Employee ID">
                            <!-- If selected. -->
                            <b-autocomplete v-if="!selectedEmployeeFor"
                                            v-model="employeeForSearchCode"
                                            field="code"
                                            :data="filteredForDataObj"
                                            @select="option => selectedEmployeeFor = option"
                                            :loading="$isLoading('FETCHING_EMPLOYEES')">
                                <template slot="empty">No results found</template>
                            </b-autocomplete>
                            <!-- When selected -->
                            <input v-else
                                   type="text"
                                   class="input"
                                   :value="selectedEmployeeFor.code + ' - ' + selectedEmployeeFor.name"
                                   @click="emptyEmployeeFor"
                                   readonly>
                        </b-field>

                        <b-field label="Cost Center">
                            <!-- If selected. -->
                            <b-autocomplete v-if="!selectedCostCenterFor"
                                            v-model="costCenterForSearchCode"
                                            field="code"
                                            :data="filteredCostCentersFor"
                                            @select="option => selectedCostCenterFor = option"
                                            :loading="$isLoading('FETCHING_COST_CENTERS')">
                                <template slot="empty">No results found</template>
                            </b-autocomplete>
                            <!-- When selected -->
                            <input v-else
                                   type="text"
                                   class="input"
                                   :value="selectedCostCenterFor.code + ' - ' + selectedCostCenterFor.description"
                                   @click="emptyCostCenterFor"
                                   readonly>
                        </b-field>
                    </div>
                </div>
            </div>

            <div class="block has-text-right">
                <slot name="cancelButton"></slot>
                <button class="button is-primary" :class="{'is-loading': $isLoading('NEW_REQUEST')}" type="submit">
                    {{ $t('words.save') }}
                </button>
            </div>
        </form>
    </div>
</template>

<script>
    export default {
        props: {
            /**
             * The 'post' route to send the request to.
             */
            endpoint: {
                type: String,
                required: true,
            }
        },
        data() {
            return {
                employeeBySearchCode: '',
                employees: [],
                selectedEmployeeBy: null,

                employeeForSearchCode: '',
                selectedEmployeeFor: null,

                costCenters: [],
                costCenterSearchCode: '',
                selectedCostCenter: null,

                costCenterForSearchCode: '',
                selectedCostCenterFor: null,
            }
        },
        computed: {
            filteredDataObj() {
                return this.employees.filter((option) => {
                    return option.code
                        .toString()
                        .toLowerCase()
                        .indexOf(this.employeeBySearchCode.toLowerCase()) >= 0
                })
            },
            filteredForDataObj() {
                return this.employees.filter((option) => {
                    return option.code
                        .toString()
                        .toLowerCase()
                        .indexOf(this.employeeForSearchCode.toLowerCase()) >= 0
                })
            },
            filteredCostCenters() {
                return this.costCenters.filter((option) => {
                    return option.code
                        .toString()
                        .toLowerCase()
                        .indexOf(this.costCenterSearchCode.toLowerCase()) >= 0
                })
            },
            filteredCostCentersFor() {
                return this.costCenters.filter((option) => {
                    return option.code
                        .toString()
                        .toLowerCase()
                        .indexOf(this.costCenterForSearchCode.toLowerCase()) >= 0
                })
            },
        },
        mounted() {
            this.loadEmployees();
            this.loadCostCenters();
        },
        methods: {
            loadEmployees() {
                this.$startLoading('FETCHING_EMPLOYEES');
                axios.get(this.apiUrl() + '/employees').then(response => {
                    this.employees = response.data;
                    this.$endLoading('FETCHING_EMPLOYEES');
                })
            },
            loadCostCenters() {
                this.$startLoading('FETCHING_COST_CENTRES');
                axios.get(this.apiUrl() + '/departments').then(response => {
                    this.costCenters = response.data;
                    this.$endLoading('FETCHING_COST_CENTRES');
                })
            },
            sendFormRequest() {
                this.$startLoading('NEW_REQUEST');

                axios.post(this.endpoint, {
                    requested_by_id:    this.selectedEmployeeBy.id,
                    cost_center_by_id: this.selectedCostCenter.id,
                    requested_for_id: (this.selectedEmployeeFor) ? this.selectedEmployeeFor.id : null,
                    cost_center_for_id: (this.selectedCostCenterFor) ? this.selectedCostCenterFor.id : null,
                }).then(response => {
                    window.location = response.data.link;
                }).catch(error => {
                    alert(error.response.data.message);
                    this.$endLoading('NEW_REQUEST');
                });
            },
            emptyEmployeeBy() {
                this.selectedEmployeeBy = null;
                this.employeeBySearchCode = '';
            },
            emptyEmployeeFor() {
                this.selectedEmployeeFor = null;
                this.employeeForSearchCode = '';
            },
            emptyCostCenter() {
                this.selectedCostCenter = null;
                this.costCenterSearchCode = '';
            },
            emptyCostCenterFor() {
                this.selectedCostCenterFor = null;
                this.costCenterForSearchCode = '';
            },
        }
    }
</script>
