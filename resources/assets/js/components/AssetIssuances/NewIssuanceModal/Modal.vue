<template>
    <div class="modal is-active" v-if="open" @keyup.esc="closeModal">
        <div class="modal-background" @click="closeModal"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">
                    {{ $t('words.new-issuance') }}
                </p>
                <button class="delete" aria-label="close" @click="closeModal"></button>
            </header>
            <section class="modal-card-body">

                <transition enter-active-class="animated fadeIn" leave-active-class="animated fadeOut" mode="out-in">
                        <div v-if="$isLoading('SUBMITTING_NEW_ISSUANCE')" key="loading" style="height:250px;">
                            <loading-screen size="is-small"></loading-screen>
                        </div>
                        <div key="form" v-else>
                            <div key="form-view" v-if="currentView == 'form'" class="columns is-multiline">

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

                                <div class="column is-6">
                                    <div class="field">
                                        <label class="label">{{ $t('words.reference-number-type') }} <span class="has-text-danger">*</span></label>
                                        <div class="control">
                                            <div class="select is-fullwidth">
                                                <select v-model="referenceNumberType" class="form-control">
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
                                            <input class="input" type="text" name="referenceNumber" v-model="referenceNumber" v-validate="'required'">

                                            <p class="help is-danger" v-if="errors.has('referenceNumber')">
                                                {{ $t('validation.required', {attribute: $t('words.reference-number')}) }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="column is-12">
                                    <div class="field">
                                        <label class="label">{{ $t('words.issuance-date') }} <span class="has-text-danger">*</span></label>
                                        <div class="control">
                                            <input class="input" type="text" readonly="true" :value="issuanceDateParsed">
                                        </div>
                                    </div>
                                </div>

                                <div class="column is-12">
                                    <div class="field">
                                        <label class="label">{{ $t('words.details') }}</label>
                                        <div class="control">
                                            <b-input type="textarea" :has-counter="true" :maxlength="1000" v-model="details"></b-input>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <new-issuance-view
                                    key="completed-view"
                                    v-if="currentView == 'completed'"
                                    :issuance="newlyCreatedIssuance"
                                    @close="closeModal"
                                    >
                            </new-issuance-view>
                        </div>
                </transition>
            </section>
            <transition leave-active-class="animated fadeOut">
                <footer class="modal-card-foot" v-if="!(currentView === 'completed')">
                    <button class="button is-success"
                            :class="{'is-loading': $isLoading('SUBMITTING_NEW_ISSUANCE')}"
                            @click.prevent="submitForm">{{ $t('words.save') }}</button>
                    <button class="button" @click="closeModal">{{ $t('words.cancel') }}</button>
                </footer>
            </transition>
        </div>
    </div>
</template>

<script>
    import NewIssuanceView from './CreatedSuccess';
    import moment from 'moment';

    export default {
        components: {
            NewIssuanceView,
        },
        data() {
            return {
            }
        },
        mounted() {
            console.log('[AssetIssuance/NewIssuanceModal] Mounted');
            this.$store.commit('AssetIssuance/updateIssuanceDate', new Date());
            this.$store.dispatch('AssetIssuance/getReferenceNumberOptions');
        },
        computed: {
            open() {
                return this.$store.getters['AssetIssuance/showNewIssuanceModal'];
            },
            department: {
                get() {
                    return this.$store.getters['AssetIssuance/department'];
                },
                set(value) {
                    this.$store.commit('AssetIssuance/updateDepartment', value);
                }
            },
            departmentName() {
                let department = this.$store.getters['AssetIssuance/department'];

                return department.code + ' - ' + department.description;
            },
            employee() {
                return this.$store.getters['AssetIssuance/employee'];
            },
            employeeName() {
                let employee = this.$store.getters['AssetIssuance/employee'];
                return employee.code + ' - ' + employee.name;
            },
            referenceNumberOptions() {
                return this.$store.getters['AssetIssuance/referenceNumberOptions'];
            },
            referenceNumberType: {
                get() {
                    return this.$store.getters['AssetIssuance/referenceNumberType'];
                },
                set(value) {
                    this.$store.commit('AssetIssuance/updateReferenceNumberType', value);
                }
            },
            referenceNumber: {
                get() {
                    return this.$store.getters['AssetIssuance/referenceNumber'];
                },
                set(value) {
                    this.$store.commit('AssetIssuance/updateReferenceNumber', value);
                }
            },
            issuanceDate: {
                get() {
                    return this.$store.getters['AssetIssuance/issuanceDate'];
                },
                set(value) {
                    this.$store.commit('AssetIssuance/updateIssuanceDate', value);
                }
            },
            returnDate: {
                get() {
                    return this.$store.getters['AssetIssuance/returnDate'];
                },
                set(value) {
                    this.$store.commit('AssetIssuance/updateReturnDate', value);
                }
            },
            details: {
                get() {
                    return this.$store.getters['AssetIssuance/details'];
                },
                set(value) {
                    this.$store.commit('AssetIssuance/updateDetails', value);
                }
            },
            newlyCreatedIssuance: {
                get() {
                    return this.$store.getters['AssetIssuance/newlyCreatedIssuance'];
                }
            },
            currentView() {
                return this.$store.getters['AssetIssuance/currentView'];
            },
            issuanceDateParsed() {
                return moment(this.issuanceDate).format('DD-MM-YYYY');
            }
        },
        methods: {
            closeModal() {
                this.$store.commit('AssetIssuance/setNewIssuanceModalActive', false);
            },
            updateEmployee(employee) {
                this.$store.commit('AssetIssuance/updateEmployee', employee);
            },
            updateDepartment(department) {
                this.$store.commit('AssetIssuance/updateDepartment', department);
            },
            submitForm() {
                this.$store.dispatch('AssetIssuance/submitNewIssuance');
            }
        },
    }
</script>

<style lang="sass" scoped>
    .control.has-icons-right .input, .control.has-icons-right .select select
        padding-right: 4.25em

    .modal-card-body
        min-height: 310px
</style>
