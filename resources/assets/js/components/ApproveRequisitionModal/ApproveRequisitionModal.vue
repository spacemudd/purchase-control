<template>
    <form @submit.prevent="save">
        <div class="modal-card" style="width: auto;height:500px">
            <header class="modal-card-head">
                <p class="modal-card-title">Approved by</p>
            </header>
            <section class="modal-card-body">

                <!-- Form Errors -->
                <div class="notification is-danger" v-if="form.errors.length > 0">
                    <strong>An error occurred.</strong>
                    <br>
                    <ul>
                        <li v-for="error in form.errors">
                            {{ error }}
                        </li>
                    </ul>
                </div>

                <b-field>
                    <input v-if="selectedEmployee"
                           class="input"
                           :value="selectedEmployee.code + ' - ' + selectedEmployee.name"
                           @click="selectedEmployee=null"
                           readonly>
                    <select-employee v-else
                                     :url="searchApproversUrl"
                                     @selected="employeeIsSelected"
                    ></select-employee>
                </b-field>

            </section>
            <footer class="modal-card-foot">
                <button class="button" type="button" @click="$parent.close()">Close</button>
                <button class="button is-success" :class="{'is-loading': $isLoading('APPROVING_REQUISITION')}">Approve</button>
            </footer>
        </div>
    </form>
</template>

<script>
    export default {
        props: {
            /**
             * Where the endpoint request is saved.
             */
            url: {
                type: String,
                required: true,
            },
            /**
             * Where to search for the list of approvers.
             */
            searchApproversUrl: {
                type: String,
                required: true,
            },
        },
        data() {
            return {
                selectedEmployee: null,
                form: {
                    approved_by_employee_id: null,
                    errors: [],
                }
            }
        },
        mounted() {
            //
        },
        methods: {
            employeeIsSelected(employee) {
                this.selectedEmployee = employee;
                this.form.approved_by_employee_id = employee.id;
            },
            save() {
                this.$startLoading('APPROVING_REQUISITION');
                this.form.errors = [];

                axios.post(this.url, this.form)
                    .then(response => {
                        this.form.errors = [];
                        window.location.reload();
                    })
                    .catch(error => {
                        this.$endLoading('APPROVING_REQUISITION')

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
