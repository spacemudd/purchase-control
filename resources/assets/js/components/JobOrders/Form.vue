e<template>
    <section>
        <form class="form" method="post" style="margin-top:2rem" @submit.prevent="submitOrder">
                <div class="columns">
                    <div class="column">
                        <b-field label="Select a date">
                            <b-datepicker
                                v-model="date"
                                placeholder="Click to select...">
                            </b-datepicker>
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

                        <b-field label="Ext">
                            <b-input v-model="ext"></b-input>
                        </b-field>

                       <b-field label="Job description">
                            <b-input v-model="job_description" maxlength="200" type="textarea"></b-input>
                        </b-field>

                        <b-field label="Remark">
                            <b-input v-model="remark" maxlength="200" type="textarea"></b-input>
                        </b-field>

                        <b-field label="Requested Through">
                            <div class="block">
                                <b-radio v-model="status"
                                    native-value="completed">
                                    Completed
                                </b-radio>
                                <b-radio v-model="status"
                                    native-value="pending">
                                    Pending
                                </b-radio>    
                            </div>
                        </b-field>

                    </div>

                    <div class="column">
                        <b-field label="Employee">
                            <!-- If selected. -->
                            <b-autocomplete v-if="!selectedEmployee"
                                            v-model="employeeSearchCode"
                                            field="code"
                                            :data="filteredEmployees"
                                            @select="option => selectedEmployee = option"
                                            :loading="$isLoading('FETCHING_EMPLOYEES')">
                                <template slot="empty">No results found</template>
                            </b-autocomplete>
                            <!-- When selected -->
                            <input v-else
                                   type="text"
                                   class="input"
                                   :value="selectedEmployee.code + ' - ' + selectedEmployee.name"
                                   @click="emptyEmployee"
                                   readonly>
                        </b-field>

                        <b-field label="Location">
                            <b-autocomplete
                                v-model="location"
                                :data="filteredLocations"
                                placeholder="Location"
                                @select="option => selectedLocation = option">
                                <template slot="empty">No results found</template>
                            </b-autocomplete>
                        </b-field>


                        <b-field label="Requested Through">
                            <div class="block">
                                <b-radio v-model="requested_through"
                                    native-value="email">
                                    Email
                                </b-radio>
                                <b-radio v-model="requested_through"
                                    native-value="phone_call">
                                    Phone Call
                                </b-radio>
                                <b-radio v-model="requested_through"
                                    native-value="breakdown">
                                    Breakdown
                                </b-radio>
                                <b-radio v-model="requested_through"
                                    native-value="ppm">
                                    PPM
                                </b-radio>
                            </div>
                        </b-field>

                         <b-field label="Job duration">
                            <div class="columns">
                                <div class="column">
                                    <b-timepicker
                                        v-model="time_start"
                                        placeholder="Select start time"
                                        hour-format="24">
                                    </b-timepicker>
                                </div>
                                <div class="column">
                                    <b-timepicker
                                        v-model="time_end"
                                        placeholder="Select end time"
                                        hour-format="24">
                                    </b-timepicker>
                                </div>
                            </div>
                        </b-field>

                        <b-field label="Materials used">
                            <b-input v-model="material_used" maxlength="200" type="textarea"></b-input>
                        </b-field>

                        <b-field label="Technicians">
                            <b-table bordered :data="technicians" :columns="columns"></b-table>
                        </b-field>

                    </div>
                </div>

                <div class="columns is-centered">
                    <div class="column">
                        <button type="submit" class="button is-primary">Create job order</button>
                    </div>
                </div>
            </form>
    </section>
</template>

<script>
    export default {
        data() {
            return {
                date:  new Date(),
                data: [
                    'Angular',
                    'Angular 2',
                    'Aurelia',
                    'Backbone',
                    'Ember',
                    'jQuery',
                    'Meteor',
                    'Node.js',
                    'Polymer',
                    'React',
                    'RxJS',
                    'Vue.js'
                ],
                technicians: [
                    { 'id': 1, 'name': 'Jesse'}
                ],
                columns: [
                    {
                        field: 'id',
                        label: 'ID',
                        width: '40',
                        numeric: true
                    },
                    {
                        field: 'name',
                        label: 'Name',
                    }
                ],
                name: '',
                ext: '',
                location: '',
                requested_through: 'email',
                job_description: '',
                time_start: new Date(),
                time_end: new Date(),
                material_used: '',
                remark: '',
                status: 'pending',
                selectedEmployee: null,

                costCenters: [],
                costCenterSearchCode: '',
                selectedCostCenter: null,

                employees: [],
                employeeSearchCode: '',
                selectedEmployee: null,

                locations: [],
                locationSearchCode: '',
                selectedLocation: null,
            }
        },
         computed: {
             filteredLocations() {
                return this.locations.filter((option) => {
                    return option.name
                        .toString()
                        .toLowerCase()
                        .indexOf(this.name.toLowerCase()) >= 0
                })
            },
             filteredEmployees() {
                 return this.employees.filter((option) => {
                     return option.code
                         .toString()
                         .toLowerCase()
                         .indexOf(this.employeeSearchCode.toLowerCase()) >= 0
                 })
             },
             filteredCostCenters() {
                 return this.costCenters.filter((option) => {
                     return option.code
                         .toString()
                         .toLowerCase()
                         .indexOf(this.costCenterSearchCode.toLowerCase()) >= 0
                 })q
             },
         },
        mounted() {
            this.loadCostCenters();
            this.loadEmployees();
            this.loadLocations();
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
            loadLocations() {
                this.$startLoading('FETCHING_LOCATIONS');
                axios.get(this.apiUrl() + '/locations').then(response => {
                    this.locations = response.data;
                    this.$endLoading('FETCHING_LOCATIONS');
                })
            },
            emptyCostCenter() {
                this.selectedCostCenter = null;
                this.costCenterSearchCode = '';
            },
            emptyEmployee() {
                this.selectedEmployee = null;
                this.employeeSearchCode = '';
            },
             submitOrder() {
                console.log(this.$data)
            }
        }
    }
</script>
