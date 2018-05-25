<template>
    <div>
        <p class="title is-6">Remarks</p>
        <p class="transparent-highlighter-bg" @click="edit">
            {{ form.remarks }}
            <span v-if="!form.remarks"><i>[Remarks of request]</i></span>
        </p>
        <div v-if="is_editing" style="margin-top:20px">
            <div class="field">
                <textarea  class="textarea is-small"
                           rows="4"
                           :class="{'is-loading': $isLoading('SAVING_REMARKS')}"
                           type="text"
                           v-model="form.remarks"
                           @keyup.enter="save"
                           @keyup.esc="rollback">
                </textarea>
            </div>
            <div class="field">
                <div class="control has-text-right">
                    <button class="button is-small is-text" @click="rollback">{{ $t('words.cancel') }}</button>
                    <button class="button is-small is-primary"
                            :class="{'is-loading': $isLoading('SAVING_REMARKS')}"
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
             * Remarks text.
             */
            remarksText: {
                type: String,
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
                type: Number,
                required: false,
                default: 0,
            }
        },
        data() {
            return {
                is_editing: false,

                old_value: this.remarksText,

                form: {
                    remarks: '',

                    errors: [],
                },
            }
        },
        mounted() {
            this.form.remarks = this.remarksText;
        },
        methods: {
            edit() {
                if(this.canEdit) {
                    this.old_value = this.form.remarks;
                    this.is_editing = true;
                }
            },
            save() {
                this.$startLoading('SAVING_REMARKS');

                this.form.errors = [];

                axios.put(this.url, this.form)
                    .then(response => {
                        this.$endLoading('SAVING_REMARKS');
                        this.is_editing = false;
                        this.form.remarks = response.data.itam_remarks;

                        this.$toast.open({
                            message: 'Saved',
                            type: 'is-success',
                        });
                    })
                    .catch(error => {
                        this.$endLoading('SAVING_REMARKS')

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
                this.form.remarks = this.old_value;
                this.is_editing = false;
            },
        }
    }
</script>
