<template>
    <div>
        <p class="is-size-7" :class="{'transparent-highlighter-bg': canEdit}" @click="edit">
            {{ old_value }}
            <span v-if="!cost_center_id"><i>[Department]</i></span>
        </p>
        <div v-if="is_editing" style="margin-top:20px">
            <div class="field">
                <select-department :url="apiUrl() + '/search/departments'"
                                 @selected="departmentSelected"
                ></select-department>
                <p class="help">Search by code or name</p>
            </div>
            <div class="field">
                <div class="control has-text-right">
                    <button class="button is-small is-text" @click="clearDepartment">Clear</button>
                    <button class="button is-small is-text" @click="rollback">{{ $t('words.cancel') }}</button>
                    <button class="button is-small is-primary"
                            :class="{'is-loading': $isLoading('SAVING_COST_CENTER_PO')}"
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
             * Department name.
             */
            value: {
                type: String,
                required: false,
            },
            propDepartmentId: {
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
                old_value: this.value,
                cost_center_id: null,
            }
        },
        mounted() {
            this.cost_center_id = this.propDepartmentId;
        },
        methods: {
            edit() {
                if(this.canEdit) {
                    // this.old_value = this.form.requested_for_cost_center_id;
                    this.is_editing = true;
                }
            },
            departmentSelected(department) {
              console.log(department);
              this.cost_center_id = department.id;
              console.log(this.cost_center_id);
            },
            save() {
                this.$startLoading('SAVING_COST_CENTER_PO');

                  axios.put(this.apiUrl() + '/purchase-orders/' + this.id + '/tokens', {
                    'name': this.name,
                    'value': this.cost_center_id,
                  })
                    .then(response => {
                        this.$endLoading('SAVING_COST_CENTER_PO');
                        this.is_editing = false;

                          this.cost_center_id = response.data.cost_center_id;
                          this.old_value = response.data.cost_center ? response.data.cost_center.display_name : '';

                        this.$toast.open({
                            message: 'Saved',
                            type: 'is-success',
                        });
                    })
                    .catch(error => {
                        this.$endLoading('SAVING_COST_CENTER_PO')

                        this.$dialog.alert({
                            message: this.form.errors,
                            type: 'is-danger',
                        });

                        throw error;
                    });
            },
            rollback() {
                this.is_editing = false;
            },
            clearDepartment() {
                this.cost_center_id = null;
                this.old_value = false;
                this.save();
            },
        }
    }
</script>
