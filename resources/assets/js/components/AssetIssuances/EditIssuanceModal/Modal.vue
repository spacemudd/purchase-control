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
                <loading-screen size="is-small" v-if="$isLoading('UPDATING_ISSUANCE')"></loading-screen>
                <div v-else>
                    <div class="columns is-multiline">

                        <!--
                        <div class="column is-6">
                            <div class="field">
                                <label class="label">{{ $t('words.departments') }} <span class="has-text-danger">*</span></label>
                                <div class="control">
                                    <input v-if="department" type="text" class="input" :value="departmentName" readonly>
                                    <simple-search
                                            v-else
                                            :size="'is-small'"
                                            search-endpoint="departments"
                                            @selectedRecord="updateDepartment"
                                    ></simple-search>
                                </div>
                            </div>
                        </div>

                        <div class="column is-6">
                            <div class="field">
                                <label class="label">{{ $t('words.employee') }} <span class="has-text-danger">*</span></label>

                                <input v-if="employee" type="text" class="input" :value="employeeName" readonly>
                                <simple-search
                                        v-else
                                        :size="'is-small'"
                                        search-endpoint="employees"
                                        @selectedRecord="updateEmployee"
                                ></simple-search>
                            </div>
                        </div>
                        -->

                        <div class="column is-6">
                            <div class="field">
                                <label class="label">{{ $t('words.reference-number-type') }} <span class="has-text-danger">*</span></label>
                                <div class="control">
                                    <div class="select is-fullwidth" v-if="selectedReferenceOptionId">
                                        <select v-model="selectedReferenceOptionId" class="form-control">
                                            <option v-for="refNum in referenceNumberOptions"
                                                    :value="refNum.id">
                                                {{ refNum.name }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="column is-6">
                            <div class="field">
                                <label class="label">{{ $t('words.reference-number') }} <span class="has-text-danger">*</span></label>
                                <div class="control">
                                    <input class="input" type="text" name="referenceNumber" v-model="editedReferenceNumber" v-validate="'required'">

                                    <p class="help is-danger" v-if="errors.has('referenceNumber')">
                                        {{ $t('validation.required', {attribute: $t('words.reference-number')}) }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="column is-12">
                            <div class="field">
                                <label class="label">{{ $t('words.details') }}</label>
                                <div class="control">
                                    <b-input type="textarea" :has-counter="true" :maxlength="1000" v-model="editedDetails"></b-input>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </section>
            <footer class="modal-card-foot">
                <button class="button is-success"
                        :class="{'is-loading': $isLoading('UPDATING_ISSUANCE')}"
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
                selectedReferenceOptionId: false,

                editedReferenceNumber: null,
                editedDetails: null,
            }
        },
        mounted() {
            this.$store.dispatch('AssetIssuance/getReferenceNumberOptions').then(() => {
                this.selectedReferenceOptionId = this.referenceNumberType.id;
            });

            this.editedReferenceNumber = this.referenceNumber;
            this.editedDetails = this.details;
        },
        computed: {
            loadedIssuanceSystemId() {
                return this.$store.getters['AssetIssuance/loadedIssuanceSystemId'];
            },
            referenceNumberOptions() {
                return this.$store.getters['AssetIssuance/referenceNumberOptions'];
            },
            referenceNumberType() {
                return this.$store.getters['AssetIssuance/loadedReferenceType'];
            },
            referenceNumber() {
                return this.$store.getters['AssetIssuance/loadedReferenceNumber'];
            },
            details() {
                return this.$store.getters['AssetIssuance/loadedDetails'];
            },
        },
        methods: {
            closeModal() {
                this.$emit('close');
                this.$store.commit('AssetIssuance/setEditIssuanceModalActive', false);
            },
            submitForm() {
                if(!this.editedReferenceNumber || !this.selectedReferenceOptionId) {
                    alert('Please fill the reference number');
                    return false;
                }

                this.$startLoading('UPDATING_ISSUANCE');

                let form = {
                    'reference_type_id': this.selectedReferenceOptionId,
                    'reference_number': this.editedReferenceNumber,
                    'details': this.editedDetails,
                };

                axios.put(this.apiUrl() + '/asset-issuances/' + this.loadedIssuanceSystemId, form)
                    .then(() => {
                        window.location.reload();
                    }).catch(error => {
                        alert(error.response.data);
                        this.$endLoading('UPDATING_ISSUANCE');
                })
            }
        },
    }
</script>
