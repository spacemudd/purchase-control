<template>
    <div v-if="open">
        <b-modal :active="open" @close="close">
            <div class="modal-card">
                <div class="modal-card-head">
                    <p class="modal-card-title">
                        {{ $t('words.new-manufacturer') }}
                    </p>
                </div>
                <form @submit.prevent="save()">
                    <section class="modal-card-body">
                            <div class="columns is-multiline">
                                <div class="column is-6">
                                    <div class="field">
                                        <label class="label">{{ $t('words.name') }} <span class="has-text-danger">*</span></label>

                                        <p class="control">
                                            <input v-model="name" class="input" required>
                                        </p>
                                    </div>
                                </div>

                                <div class="column is-6">
                                    <div class="field">
                                        <label class="label">{{ $t('words.website') }}</label>
                                        <div class="control">
                                            <input type="text" class="input" v-model="website">
                                        </div>
                                    </div>
                                </div>

                                <div class="column is-6">
                                    <div class="field">
                                        <label class="label">{{ $t('words.support-url') }}</label>
                                        <div class="control">
                                            <input type="text" class="input" v-model="support_url">
                                        </div>
                                    </div>
                                </div>

                                <div class="column is-6">
                                    <div class="field">
                                        <label class="label">{{ $t('words.support-phone') }}</label>
                                        <div class="control">
                                            <input type="text" class="input" v-model="support_phone">
                                        </div>
                                    </div>
                                </div>

                                <div class="column is-6">
                                    <div class="field">
                                        <label class="label">{{ $t('words.support-email') }}</label>
                                        <div class="control">
                                            <input type="email" class="input" v-model="support_email">
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </section>
                    <footer class="modal-card-foot">
                        <button class="button" type="button" @click="close">{{ $t('words.cancel') }}</button>
                        <button type="submit"
                                :class="{'is-loading': $isLoading('SAVING_MANUFACTURER')}"
                                class="button is-success">
                            {{ $t('words.save') }}
                        </button>
                    </footer>
                </form>
            </div>
        </b-modal>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                name: null,
                website: null,
                support_url: null,
                support_phone: null,
                support_email: null,
            }
        },
        computed: {
            open: {
                get() {
                    return this.$store.getters['Manufacturer/showNewModal'];
                }
            },
        },
        methods: {
            close() {
                this.name = null;
                this.website = null;
                this.support_url = null;
                this.support_email = null;
                this.$store.commit('Manufacturer/setNewModal', false);
            },
            save() {
                this.$startLoading('SAVING_MANUFACTURER');

                axios.post(this.apiUrl() + '/manufacturers/store', {
                    name: this.name,
                    website: this.website,
                    support_url: this.support_url,
                    support_phone: this.support_phone,
                    support_email: this.support_email,
                }).then(response => {
                    window.location = response.data.link;
                    // this.$endLoading('SAVING_MANUFACTURER');
                }).catch(error => {
                    alert(error.response.data.message);
                    this.$endLoading('SAVING_MANUFACTURER');
                });
            },
        }
    }
</script>
