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

                        <b-field label="Status">
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
                            <!-- If selected. -->
                            <b-autocomplete v-if="!selectedLocation"
                                            v-model="locationSearchCode"
                                            field="name"
                                            :data="filteredLocations"
                                            @select="option => selectedLocation = option"
                                            :loading="$isLoading('FETCHING_LOCATIONS')">
                                <template slot="empty">No results found</template>
                            </b-autocomplete>
                            <!-- When selected -->
                            <input v-else
                                   type="text"
                                   class="input"
                                   :value="selectedLocation.name"
                                   @click="emptyLocation"
                                   readonly>
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
                            <table class="table is-narrow is-size-7 is-fullwidth">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Employee</th>
                                        <th>Time start</th>
                                        <th>Time end</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(tech, index) in technicians">
                                    <td>{{ ++index }}</td>
                                    <td>{{ tech.employee.code+' '+tech.employee.name }}</td>
                                    <td>{{ tech.time_start }}</td>
                                    <td>{{ tech.time_end }}</td>
                                </tr>
                                <tr v-if="isAddingTechnician">
                                    <td></td>
                                    <td @keyup.enter="addTechnician">
                                        <input v-if="technicianForm.employee"
                                               type="text"
                                               class="input is-small"
                                               :value="technicianForm.employee.code + ' - ' + technicianForm.employee.name"
                                               @click="emptyEmployee"
                                               readonly>
                                        <b-autocomplete v-else
                                                        v-model="technicianFormSearchCode"
                                                        field="code"
                                                        :data="filteredEmployees"
                                                        @select="option => technicianForm.employee = option"
                                                        size="is-small"
                                                        :loading="$isLoading('FETCHING_EMPLOYEES')">
                                            <template slot="empty">No results found</template>
                                        </b-autocomplete>
                                    </td>
                                    <td>
                                        <b-timepicker
                                                v-model="technicianForm.time_start"
                                                placeholder="Select start time"
                                                hour-format="24"
                                                size="is-small">
                                        </b-timepicker>
                                    </td>
                                    <td>
                                        <b-timepicker
                                                v-model="technicianForm.time_end"
                                                placeholder="Select end time"
                                                hour-format="24"
                                                size="is-small">
                                        </b-timepicker>
                                    </td>
                                    <td class="has-text-centered">
                                        <button class="button is-primary is-small"
                                                :class="{'is-loading': $isLoading('SAVING_TECHNICIAN')}"
                                                @click.prevent="addTechnician">
                                            Add
                                        </button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
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
                technicians: [],
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

                isAddingTechnician: true,
                technicianFormSearchCode: '',
                technicianForm: {
                    employee: '',
                    employee_id: '',
                    time_start: null,
                    time_end: null,
                }
            }
        },
         computed: {
             filteredLocations() {
                return this.locations.filter((option) => {
                    return option.name
                        .toString()
                        .toLowerCase()
                        .indexOf(this.locationSearchCode.toLowerCase()) >= 0
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
                 })
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
            emptyLocation() {
                this.selectedLocation = null;
                this.locationSearchCode = '';
            },
             submitOrder() {
                console.log(this.$data)
            },
            addTechnician() {
                if (!this.technicianForm.employee) {
                    alert('Please select an employee');
                    return false;
                }

                this.technicians.push(this.technicianForm);

                setTimeout(() => {
                    this.clearTechnicianForm();
                }, 200);
            },
            clearTechnicianForm() {
                this.technicianFormSearchCode = '';
                let technicianForm = {
                    employee: '',
                    employee_id: '',
                    time_start: null,
                    time_end: null,
                }
                this.technicianForm = technicianForm;
            }
        }
    }
</script>
