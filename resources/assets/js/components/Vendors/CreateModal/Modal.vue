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
                                <label class="label">{{ $t('words.code') }} <span class="has-text-danger">*</span></label>

                                <p class="control">
                                    <input v-model="code" class="input" required>
                                </p>
                            </div>
                        </div>

                        <div class="column is-6">
                            <div class="field">
                                <label class="label">{{ $t('words.description') }}</label>
                                <div class="control">
                                    <input type="text" class="input" v-model="description">
                                </div>
                            </div>
                        </div>

                        <div class="column is-6">
                            <div class="field">
                                <label class="label">{{ $t('words.contact-person') }}</label>
                                <div class="control">
                                    <input type="text" class="input" v-model="contact_person">
                                </div>
                            </div>
                        </div>

                        <div class="column is-6">
                            <div class="field">
                                <label class="label">{{ $t('words.contact-details') }}</label>
                                <div class="control">
                                    <input type="text" class="input" v-model="contact_details">
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
    export default {
        data() {
            return {
                code: null,
                description: null,
                contact_person: null,
                contact_details: null,
                address: null,
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
                    code: this.code,
                    description: this.description,
                    contact_person: this.contact_person,
                    contact_details: this.contact_details,
                    address: this.address,
                    email: this.email,
                    website: this.website,
                }).then(response => {
                    window.location.href = response.data.link;
                    this.$endLoading('SAVING_VENDOR');
                }).catch(error => {
                    alert(error.response.data.message);
                    this.$endLoading('SAVING_VENDOR');
                });
            },
        }
    }
</script>
