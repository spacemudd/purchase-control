<template>
    <div>
        <p class="is-size-7" :class="{'transparent-highlighter-bg': canEdit}" @click="edit">
            {{ old_value }}
            <span v-if="!old_value"><i>[Quote Reference Number]</i></span>
        </p>
        <div v-if="is_editing" style="margin-top:20px">
            <div class="field">
                <b-input v-model="quote_reference_number" @keyup.enter="save"></b-input>
            </div>
            <div class="field">
                <div class="control has-text-right">
                    <button class="button is-small is-text" @click="clearValue">Clear</button>
                    <button class="button is-small is-text" @click="rollback">{{ $t('words.cancel') }}</button>
                    <button class="button is-small is-primary"
                            :class="{'is-loading': $isLoading('SAVING_QUOTE_REF_TOKEN')}"
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
            value: {
                type: String,
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
                quote_reference_number: '',
            }
        },
        mounted() {
          this.quote_reference_number = this.value;
        },
        methods: {
            edit() {
                if(this.canEdit) {
                    this.is_editing = true;
                } else {
                  this.$toast.open({
                    type: 'is-warning',
                    message: 'PO cant be edited after saving',
                  })
                }
            },
            save() {
                this.$startLoading('SAVING_QUOTE_REF_TOKEN');

                  axios.put(this.apiUrl() + '/purchase-orders/' + this.id + '/tokens', {
                    'name': this.name,
                    'value': this.quote_reference_number,
                  })
                    .then(response => {
                        this.$endLoading('SAVING_QUOTE_REF_TOKEN');
                        this.is_editing = false;

                        this.old_value = response.data.quote_reference_number ? response.data.quote_reference_number : '';

                        this.$toast.open({
                            message: 'Saved',
                            type: 'is-success',
                        });
                    })
                    // .catch(error => {
                    //     this.$endLoading('SAVING_QUOTE_REF_TOKEN')
                    //
                    //     if (typeof error.response.data === 'object') {
                    //         this.form.errors = _.flatten(_.toArray(error.response.data.errors));
                    //     } else {
                    //         this.form.errors = ['Something went wrong. Please try again.'];
                    //     }
                    //
                    //     this.$dialog.alert({
                    //         message: this.form.errors,
                    //         type: 'is-danger',
                    //     });
                    //
                    //     throw error;
                    // });
            },
            rollback() {
                this.value = this.old_value;
                this.is_editing = false;
            },
            clearValue() {
                this.old_value = '';
                this.quote_reference_number = '';
                this.save();
            },
        }
    }
</script>
