<template>
    <div>
        <p class="transparent-highlighter-bg is-size-7" @click="edit">
            {{ old_value }}
            <span v-if="!form.requested_by_employee_id"><i>[Employee]</i></span>
        </p>
        <div v-if="is_editing" style="margin-top:20px">
            <div class="field">
                <select-employee :url="apiUrl() + '/search/employees'"
                                 @selected="employeeIsSelected"
                ></select-employee>
                <p class="help">Search by code or name</p>
            </div>
            <div class="field">
                <div class="control has-text-right">
                    <button class="button is-small is-text" @click="clearEmployee">Clear</button>
                    <button class="button is-small is-text" @click="rollback">{{ $t('words.cancel') }}</button>
                    <button class="button is-small is-primary"
                            :class="{'is-loading': $isLoading('SAVING_REQUESTED_BY_TOKEN')}"
                            @click="save">Save</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
          id: {
            type: Number,
            required: true,
          },
            /**
             * Employee name.
             */
            employeeName: {
                type: String,
                required: false,
            },
            requestedByEmployeeId: {
                required: false,
            },
          /**
           * The name of the token/field/column-name of the resource.
           */
          name: {
            type: String,
            required: true,
          },
            /**
             * Saving endpoint.
             */
            url: {
                type: String,
                required: true,
            },
            canEdit: {
                type: Boolean,
                default: false,
            }
        },
        data() {
            return {
                is_editing: false,

                old_value: this.employeeName,

                form: {
                    requested_by_employee_id: '',

                    errors: [],
                },
            }
        },
        mounted() {
            this.form.requested_by_employee_id = this.requestedByEmployeeId;
        },
        methods: {
            edit() {
                if(this.canEdit) {
                    // this.old_value = this.form.requested_for_employee_id;
                    this.is_editing = true;
                }
            },
            employeeIsSelected(employee) {
                this.form.requested_by_employee_id = employee.id;
            },
            save() {
                this.$startLoading('SAVING_REQUESTED_BY_TOKEN');

                this.form.errors = [];
                  axios.put(this.apiUrl() + '/purchase-orders/' + this.id + '/tokens', {
                    'name': this.name,
                    'value': this.form.requested_by_employee_id,
                  })
                    .then(response => {
                        this.$endLoading('SAVING_REQUESTED_BY_TOKEN');
                        this.is_editing = false;
                        this.form.requested_by_employee_id = response.data.requested_by_employee_id;
                        console.log(response.data.requested_by_employee);
                        this.old_value = response.data.requested_by_employee ? response.data.requested_by_employee.display_name : '';
                        console.log(this.old_value);

                        this.$toast.open({
                            message: 'Saved',
                            type: 'is-success',
                        });
                    })
                    .catch(error => {
                        this.$endLoading('SAVING_REQUESTED_BY_TOKEN')

                        if (typeof error.response.data === 'object') {
                            this.form.errors = _.flatten(_.toArray(error.response.data.errors));
                        } else {
                            this.form.errors = ['Something went wrong. Please try again.'];
                        }

                        this.$dialog.alert({
                            message: this.form.errors,
                            type: 'is-danger',
                        });

                        throw error;
                    });
            },
            rollback() {
                // this.form.requested_for_employee_id = this.old_value;
                this.is_editing = false;
            },
            clearEmployee() {
                this.form.requested_by_employee_id = null;
                this.old_value = false;
                this.save();
            },
        }
    }
</script>
