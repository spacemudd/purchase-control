<template>
    <div v-if="open">
        <b-modal :active="open" @close="close">
            <form @submit.prevent="save()">
                <div class="modal-card">
                <div class="modal-card-head">
                    <p class="modal-card-title">
                        {{ $t('words.new-vendor') }}
                    </p>
                </div>
                <section class="modal-card-body">
                    <div class="columns is-multiline">
                        <div class="column is-6">
                            <div class="field">
                                <label class="label">{{ $t('words.name') }} <span class="has-text-danger">*</span></label>
                                <div class="control">
                                    <input type="text" class="input" v-model="name" rquired>
                                </div>
                            </div>
                        </div>

                        <div class="column is-6">
                            <div class="field">
                                <label class="label">{{ $t('words.established-at') }}</label>
                                <div class="control">
                                    <b-field>
                                        <b-datepicker
                                                icon-pack="fa"
                                                icon="calendar"
                                                v-model="established_at"
                                                :readonly="false">
                                        </b-datepicker>
                                    </b-field>
                                </div>
                            </div>
                        </div>

                        <div class="column is-6">
                            <div class="field">
                                <label class="label">{{ $t('words.address') }}</label>
                                <div class="control">
                                    <input type="text" class="input" v-model="address">
                                </div>
                            </div>
                        </div>

                        <div class="column is-6">
                            <div class="field">
                                <label class="label">{{ $t('words.telephone-number') }}</label>
                                <div class="control">
                                    <input type="text" class="input" v-model="telephoneNumber">
                                </div>
                            </div>
                        </div>

                        <div class="column is-6">
                            <div class="field">
                                <label class="label">{{ $t('words.fax-number') }}</label>
                                <div class="control">
                                    <input type="text" class="input" v-model="faxNumber">
                                </div>
                            </div>
                        </div>

                        <div class="column is-6">
                            <div class="field">
                                <label class="label">{{ $t('auth.email') }}</label>
                                <div class="control">
                                    <input type="email" class="input" v-model="email">
                                </div>
                            </div>
                        </div>

                        <div class="column is-12">
                            <div class="field">
                                <label class="label">{{ $t('words.website') }}</label>
                                <div class="control">
                                    <input type="text" class="input" v-model="website">
                                </div>
                            </div>
                        </div>

                    </div>
                </section>
                <footer class="modal-card-foot">
                    <button :class="{'is-loading': $isLoading('SAVING_VENDOR')}"
                            class="button is-success"
                            type="submit"
                    >{{ $t('words.save') }}
                    </button>
                    <button class="button" type="button" @click="close">{{ $t('words.cancel') }}</button>
                </footer>
            </div>
            </form>
        </b-modal>
    </div>
</template>

<script>
    import moment from 'moment';

    export default {
        data() {
            return {
                name: null,
                established_at: null,
                address: null,
                telephoneNumber: null,
                faxNumber: null,
                email: null,
                website: null,
            }
        },
        computed: {
            open: {
                get() {
                    return this.$store.getters['Vendor/showNewModal'];
                }
            },
        },
        mounted() {
            //
        },
        methods: {
            close() {
                this.$store.commit('Vendor/setNewModal', false);
            },
            save() {
                this.$startLoading('SAVING_VENDOR');

                axios.post(this.apiUrl() + '/vendors/store', {
                    name: this.name,
                    established_at: this.established_at ? moment(this.established_at).format('YYYY-MM-DD') : null,
                    address: this.address,
                    telephone_number: this.telephoneNumber,
                    fax_number: this.faxNumber,
                    email: this.email,
                    website: this.website,
                }).then(response => {
                    window.location.href = response.data.link;
                    // this.$endLoading('SAVING_VENDOR');
                }).catch(error => {
                    alert(error.response.data.message);
                    this.$endLoading('SAVING_VENDOR');
                });
            },
        }
    }
</script>
