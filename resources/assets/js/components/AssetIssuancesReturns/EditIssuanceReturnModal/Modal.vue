<template>
    <div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">
                    {{ $t('words.edit') }}
                </p>
                <button class="delete" aria-label="close" @click="closeModal"></button>
            </header>
            <section class="modal-card-body">
                <loading-screen size="is-small" v-if="$isLoading('UPDATING_ISSUANCE_RETURN')"></loading-screen>
                <div v-else>
                    <div class="columns is-multiline">

                        <div class="column is-12">
                            <div class="field">
                                <label class="label">{{ $t('words.remarks') }}</label>
                                <div class="control">
                                    <b-input type="textarea" :has-counter="true" :maxlength="255" v-model="editedRemarks"></b-input>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </section>
            <footer class="modal-card-foot">
                <button class="button is-success"
                        :class="{'is-loading': $isLoading('UPDATING_ISSUANCE_RETURN')}"
                        @click.prevent="submitForm">{{ $t('words.save') }}</button>
                <button class="button" @click="closeModal">{{ $t('words.cancel') }}</button>
            </footer>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                editedRemarks: null,
            }
        },
        mounted() {
            this.editedRemarks = this.remarks;
        },
        computed: {
            loadedIssuanceReturnSystemId() {
                return this.$store.getters['AssetIssuanceReturn/loadedIssuanceReturnSystemId'];
            },
            remarks() {
                return this.$store.getters['AssetIssuanceReturn/loadedRemarks'];
            },
        },
        methods: {
            closeModal() {
                this.$emit('close');
                this.$store.commit('AssetIssuanceReturn/setEditModal', false);
            },
            submitForm() {

                this.$startLoading('UPDATING_ISSUANCE_RETURN');

                let form = {
                    'remarks': this.editedRemarks,
                };

                axios.put(this.apiUrl() + '/asset-issuances-returns/' + this.loadedIssuanceReturnSystemId, form)
                    .then(() => {
                        window.location.reload();
                    }).catch(error => {
                        alert(error.response.data);
                        this.$endLoading('UPDATING_ISSUANCE_RETURN');
                })
            }
        },
    }
</script>
