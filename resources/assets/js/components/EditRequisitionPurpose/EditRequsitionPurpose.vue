<template>
    <div>
        <p class="title is-6">Purpose</p>
        <p class="transparent-highlighter-bg" @click="edit">
            {{ form.purpose }}
            <span v-if="!form.purpose"><i>[Purpose of request]</i></span>
        </p>
        <div v-if="is_editing" style="margin-top:20px">
            <div class="field">
                <textarea  class="textarea is-small"
                           rows="4"
                           :class="{'is-loading': $isLoading('SAVING_PURPOSE')}"
                           type="text"
                           v-model="form.purpose"
                           @keyup.enter="save"
                           @keyup.esc="rollback">
                </textarea>
            </div>
            <div class="field">
                <div class="control">
                    <button class="button is-pulled-right is-small is-primary"
                            :class="{'is-loading': $isLoading('SAVING_PURPOSE')}"
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
             * Purpose text.
             */
            purposeText: {
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

                old_value: this.purposeText,

                form: {
                    purpose: '',

                    errors: [],
                },
            }
        },
        mounted() {
            this.form.purpose = this.purposeText;
        },
        methods: {
            edit() {
                if(this.canEdit) {
                    this.old_value = this.form.purpose;
                    this.is_editing = true;
                }
            },
            save() {
                this.$startLoading('SAVING_PURPOSE');

                this.form.errors = [];

                axios.put(this.url, this.form)
                    .then(response => {
                        this.$endLoading('SAVING_PURPOSE');
                        this.is_editing = false;
                        this.form.purpose = response.data.purpose;

                        this.$toast.open({
                            message: 'Saved',
                            type: 'is-success',
                        });
                    })
                    .catch(error => {
                        this.$endLoading('SAVING_PURPOSE')

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
                this.purposeText = this.old_value;
                this.is_editing = false;
            },
        }
    }
</script>
