<template>
    <div>
        <p class="transparent-highlighter-bg is-size-7" @click="edit">
            {{ old_value }}
            <span v-if="!form.head_of_itam_id"><i>[Approved by]</i></span>
        </p>
        <div v-if="is_editing" style="margin-top:20px">
            <div class="field">
                <select-employee :url="apiUrl() + '/search/approvers'"
                                 @selected="employeeIsSelected"
                ></select-employee>
                <p class="help">Search by code or name</p>
            </div>
            <div class="field">
                <div class="control has-text-right">
                    <button class="button is-small is-text" @click="clearApprovedBy">Clear</button>
                    <button class="button is-small is-text" @click="rollback">{{ $t('words.cancel') }}</button>
                    <button class="button is-small is-primary"
                            :class="{'is-loading': $isLoading('SAVING_RECOMMENDED_BY')}"
                            @click="save">Save</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            personName: {
                type: String,
                required: false,
            },
            oldPersonId: {
                required: false,
            },
            /**
             * Update endpoint.
             */
            url: {
                type: String,
                required: true,
            },
            canEdit: {
                type: Boolean,
                default: false,
            },
            tokenName: {
                type: String,
                required: true,
            },
        },
        data() {
            return {
                is_editing: false,

                old_value: this.employeeName,

                form: {
                    errors: [],
                },
            }
        },
        mounted() {
            this.push.form
            let something = {
                tokenName: 'okay',
            };

            this.form.head_of_itam_id = this.headOfItamId;
        },
        methods: {
            edit() {
                if(this.canEdit) {
                    // this.old_value = this.form.head_of_itam_id;
                    this.is_editing = true;
                }
            },
            employeeIsSelected(employee) {
                this.form.head_of_itam_id = employee.id;
            },
            save() {
                this.$startLoading('SAVING_RECOMMENDED_BY');

                this.form.errors = [];
                let data = {head_of_itam_id: this.form.head_of_itam_id};
                axios.put(this.url, data)
                    .then(response => {
                        this.$endLoading('SAVING_RECOMMENDED_BY');
                        this.is_editing = false;
                        this.form.head_of_itam_id = response.data.head_of_itam_id;
                        this.old_value = response.data.approved_by ? response.data.approved_by.display_name : '';

                        this.$toast.open({
                            message: 'Saved',
                            type: 'is-success',
                        });
                    })
                    .catch(error => {
                        this.$endLoading('SAVING_RECOMMENDED_BY')

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
                // this.form.head_of_itam_id = this.old_value;
                this.is_editing = false;
            },
            clearApprovedBy() {
                this.form.head_of_itam_id = null;
                this.save();
            },
        }
    }
</script>
