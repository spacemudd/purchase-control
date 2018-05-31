<template>
    <div>
        <p class="transparent-highlighter-bg is-size-7" @click="edit">
            {{ old_value }}
            <span v-if="!form.checked_by_id"><i>[Checked and registered by]</i></span>
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
                    <button class="button is-small is-text" @click="clearApprovedBy">Clear</button>
                    <button class="button is-small is-text" @click="rollback">{{ $t('words.cancel') }}</button>
                    <button class="button is-small is-primary"
                            :class="{'is-loading': $isLoading('SAVING_CHECKED_BY')}"
                            @click="save">Save</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            /**
             * Employee name.
             */
            employeeName: {
                type: String,
                required: false,
            },
            checkedById: {
                required: false,
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
                    checked_by_id: '',

                    errors: [],
                },
            }
        },
        mounted() {
            this.form.checked_by_id = this.checkedById;
        },
        methods: {
            edit() {
                if(this.canEdit) {
                    // this.old_value = this.form.checked_by_id;
                    this.is_editing = true;
                }
            },
            employeeIsSelected(employee) {
                this.form.checked_by_id = employee.id;
            },
            save() {
                this.$startLoading('SAVING_CHECKED_BY');

                this.form.errors = [];
                let data = {checked_by_id: this.form.checked_by_id};
                axios.put(this.url, data)
                    .then(response => {
                        this.$endLoading('SAVING_CHECKED_BY');
                        this.is_editing = false;
                        this.form.checked_by_id = response.data.checked_by_id;
                        this.old_value = response.data.checked_by ? response.data.checked_by.display_name : '';

                        this.$toast.open({
                            message: 'Saved',
                            type: 'is-success',
                        });
                    })
                    .catch(error => {
                        this.$endLoading('SAVING_CHECKED_BY')

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
                // this.form.checked_by_id = this.old_value;
                this.is_editing = false;
            },
            clearApprovedBy() {
                this.form.checked_by_id = null;
                this.save();
            },
        }
    }
</script>
