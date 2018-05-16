CREATING_APPROVER<template>
    <div class="columns">
        <div class="column is-4 is-offset-4">
            <!-- Form Errors -->
            <div class="notification is-danger" v-if="form.errors.length > 0">
                <strong>Something went wrong.</strong>
                <br>
                <ul>
                    <li v-for="error in form.errors">
                        {{ error }}
                    </li>
                </ul>
            </div>

            <form @submit.prevent="save()">
                <b-field label="Code">
                    <input v-if="selectedEmployee"
                           type="text"
                           class="input"
                           :value="selectedEmployee.code + ' - ' + selectedEmployee.name"
                           @click="clearSelectedEmployee"
                           required
                           readonly>
                    <!-- When searching -->
                    <b-autocomplete
                            v-else
                            @mousedown="clearSelectedEmployee"
                            v-model="searchEmployee"
                            :data="employees"
                            :loading="$isLoading('LOADING_EMPLOYEES')"
                            field="code"
                            @input="getEmployeesData"
                            @select="selectEmployee">
                        <template slot="empty" v-if="(!$isLoading('LOADING_EMPLOYEES') && employees.length === 0)">No results found</template>
                        <template slot-scope="props">
                            <a class="dropdown-item">
                                {{ props.option.code }} - {{ props.option.name }}
                            </a>
                        </template>
                    </b-autocomplete>
                </b-field>
                <b-field label="Financial Authority">
                    <b-input type="number" min="0" v-model="form.financial_auth" required></b-input>
                </b-field>
                <b-field label="Designation">
                    <b-input type="text" v-model="form.designation"></b-input>
                </b-field>
                <b-field>
                    <div class="has-text-right">
                        <a href="#" class="button is-text">Cancel</a>
                        <button type="submit"
                                class="button is-primary"
                                :class="{'is-loading': $isLoading('CREATING_APPROVER')}"
                        >Save</button>
                    </div>
                </b-field>
            </form>
        </div>
    </div>
</template>

<script>
    import debounce from "lodash/debounce";

    export default {
        data() {
            return {
                selectedEmployee: null,
                searchEmployee: null,
                employees: [],

                form: {
                    employee_id: null,
                    financial_auth: null,
                    designation: '',

                    errors: [],
                }
            }
        },
        computed: {
            searchEmployeesEndpoint() {
                return this.apiUrl() + '/search/employees';
            }
        },
        mounted() {
            //
        },
        methods: {
            getEmployeesData: debounce(function () {
                this.employees = []
                this.$startLoading('LOADING_EMPLOYEES')
                axios.get(this.apiUrl() + '/search/approvers/create?q=' + this.searchEmployee)
                    .then(response => {
                        response.data.data.forEach((item) => this.employees.push(item))
                        this.$endLoading('LOADING_EMPLOYEES')
                    })
                    .catch(error => {
                        this.$endLoading('LOADING_EMPLOYEES')

                        throw error;
                    });
            }, 500),
            selectEmployee(employee) {
                this.selectedEmployee = employee;
                this.form.employee_id = employee.id;
                this.$emit('selected', employee);
            },
            clearSelectedEmployee() {
                this.selectedEmployee = null;
                this.searchEmployee = null;
                this.form.employee_id = null;
            },
            save() {
                this.$startLoading('CREATING_APPROVER')
                this.form.errors = [];

                axios.post(this.apiUrl() + '/approvers', this.form)
                    .then(response => {
                        this.form.errors = [];

                        window.location = response.data.approver_link;
                    })
                    .catch(error => {
                        this.$endLoading('CREATING_APPROVER')

                        if (typeof error.response.data === 'object') {
                            this.form.errors = _.flatten(_.toArray(error.response.data.errors));
                        } else {
                            this.form.errors = ['Something went wrong. Please try again.'];
                        }

                        throw error;
                    });
            }
        }
    }
</script>

<style lang="sass">
    //
</style>
