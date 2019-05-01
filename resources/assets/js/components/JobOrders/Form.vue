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
                            <b-autocomplete v-if="!cost_center"
                                            v-model="costCenterSearchCode"
                                            field="code"
                                            :data="filteredCostCenters"
                                            @select="option => cost_center = option"
                                            :loading="$isLoading('FETCHING_COST_CENTERS')"
                                            required>
                                <template slot="empty">No results found</template>
                            </b-autocomplete>
                            <!-- When selected -->
                            <input v-else
                                   type="text"
                                   class="input"
                                   :value="cost_center.code + ' - ' + cost_center.description"
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
                            <b-autocomplete v-if="!employee"
                                            v-model="employeeSearchCode"
                                            field="code"
                                            :data="filteredEmployees"
                                            @select="option => employee = option"
                                            :loading="$isLoading('FETCHING_EMPLOYEES')">
                                <template slot="empty">No results found</template>
                            </b-autocomplete>
                            <!-- When selected -->
                            <input v-else
                                   type="text"
                                   class="input"
                                   :value="employee.code + ' - ' + employee.name"
                                   @click="emptyEmployee"
                                   readonly>
                        </b-field>


                        <b-field label="Location">
                            <!-- If selected. -->
                            <b-autocomplete v-if="!location"
                                            v-model="locationSearchCode"
                                            field="name"
                                            :data="filteredLocations"
                                            @select="option => location = option"
                                            :loading="$isLoading('FETCHING_LOCATIONS')">
                                <template slot="empty">No results found</template>
                            </b-autocomplete>
                            <!-- When selected -->
                            <input v-else
                                   type="text"
                                   class="input"
                                   :value="location.name"
                                   @click="emptyLocation"
                                   readonly>
                        </b-field>

                        <b-field label="Requested Through">
                            <div class="block">
                                <b-radio v-model="requested_through_type"
                                    native-value="email">
                                    Email
                                </b-radio>
                                <b-radio v-model="requested_through_type"
                                    native-value="phone_call">
                                    Phone Call
                                </b-radio>
                                <b-radio v-model="requested_through_type"
                                    native-value="breakdown">
                                    Breakdown
                                </b-radio>
                                <b-radio v-model="requested_through_type"
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
                        <button type="submit"
                                :class="{'is-loading': $isLoading('SAVING_JOB_ORDER')}"
                                class="button is-primary">Create job order</button>
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
                cost_center: '',
                requested_through_type: 'email',
                job_description: '',
                time_start: new Date(),
                time_end: new Date(),
                material_used: '',
                remark: '',
                status: 'pending',
                employee: null,

                costCenters: [],
                costCenterSearchCode: '',

                employees: [],
                employeeSearchCode: '',

                locations: [],
                locationSearchCode: '',

                isAddingTechnician: true,
                technicianFormSearchCode: '',
                technicianForm: {
                    employee: '',
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
                this.cost_center = null;
                this.costCenterSearchCode = '';
            },
            emptyEmployee() {
                this.employee = null;
                this.employeeSearchCode = '';
            },
            emptyLocation() {
                this.location = null;
                this.locationSearchCode = '';
            },
            submitOrder() {
                this.$startLoading('SAVING_JOB_ORDER');
                let data = this.$data;
                data.location_id = this.location.id;
                data.employee_id = this.employee.id;
                data.cost_center_id = this.cost_center.id;

                axios.post(this.baseUrl()+'/job-orders', data)
                    .then(response => {
                        this.$toast.open({
                            message: 'Success!',
                        });
                        window.location.href = this.baseUrl()+'/job-orders';
                    })
                    .catch(e => {
                        throw e;
                    }).finally(() => {
                    this.$endLoading('SAVING_JOB_ORDER');
                })
            },
            addTechnician() {
                if (!this.technicianForm.employee) {
                    alert('Please select an employee');
                    return false;
                }
                let technician = this.technicianForm;
                technician.technician_id = technician.employee.id;
                this.technicians.push(technician);

                setTimeout(() => {
                    this.clearTechnicianForm();
                }, 200);
            },
            clearTechnicianForm() {
                this.technicianFormSearchCode = '';
                let technicianForm = {
                    employee: '',
                    technician_id: '',
                    time_start: null,
                    time_end: null,
                }
                this.technicianForm = technicianForm;
            }
        }
    }
</script>
