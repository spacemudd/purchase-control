<template>
    <div class="modal is-active" v-if="open" @keyup.esc="closeModal">
        <div class="modal-background" @click="closeModal"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">
                    {{ $t('words.new-issuance-return') }}
                </p>
                <button class="delete" aria-label="close" @click="closeModal"></button>
            </header>
            <section class="modal-card-body">
                <transition enter-active-class="animated fadeIn" leave-active-class="animated fadeOut" mode="out-in">
                        <div v-if="$isLoading('SUBMITTING_NEW_ISSUANCE_RETURN')" key="loading" style="height:250px;">
                            <loading-screen size="is-small"></loading-screen>
                        </div>
                        <div key="form" v-else>
                            <div key="form-view" v-if="currentView == 'form'" class="columns is-multiline">

                                <div class="column is-6">
                                    <div class="field">
                                        <label class="label">{{ $t('words.departments') }} <span class="has-text-danger">*</span></label>
                                        <div class="control">
                                            <input v-if="department"
                                                   @click="department = null"
                                                   type="text"
                                                   class="input"
                                                   :value="departmentName"
                                                   readonly>
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

                                        <input v-if="employee"
                                               @click="employee = null"
                                               type="text"
                                               class="input"
                                               :value="employeeName"
                                               readonly>
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
                                        <label class="label">{{ $t('words.date') }}</label>
                                        <div class="control">
                                            <b-input :value="returnDateParsed"
                                                     type="text"
                                                     icon-pack="fa"
                                                     :readonly="true"
                                                     icon="calendar">
                                            </b-input>
                                        </div>
                                    </div>
                                </div>

                                <div class="column is-12">
                                    <div class="field">
                                        <label class="label">{{ $t('words.remarks') }}</label>
                                        <div class="control">
                                            <b-input type="textarea" :has-counter="true" :maxlength="255" v-model="remarks"></b-input>
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
    import moment from 'moment';

    export default {
        mounted() {
            console.log('[AssetIssuanceReturn/NewIssuanceReturnModal] Mounted');

        },
        // TODO: Remove this to create().
        created() {

        },
        computed: {
            open() {
                return this.$store.getters['AssetIssuanceReturn/showNewIssuanceReturnModal'];
            },
            department: {
                get() {
                    return this.$store.getters['AssetIssuanceReturn/department'];
                },
                set(value) {
                    this.$store.commit('AssetIssuanceReturn/updateDepartment', value);
                }
            },
            departmentName() {
                let department = this.$store.getters['AssetIssuanceReturn/department'];

                return department.code + ' - ' + department.description;
            },
            employee() {
                return this.$store.getters['AssetIssuanceReturn/employee'];
            },
            employeeName() {
                let employee = this.$store.getters['AssetIssuanceReturn/employee'];
                return employee.code + ' - ' + employee.name;
            },
            returnDateParsed() {
                return moment(this.retunDate).format('DD/MM/YYYY');
            },
            returnDate: {
                get() {
                    return this.$store.getters['AssetIssuanceReturn/returnDate'];
                },
                set(value) {
                    this.$store.commit('AssetIssuanceReturn/updateReturnDate', value);
                }
            },
            remarks: {
                get() {
                    return this.$store.getters['AssetIssuanceReturn/remarks'];
                },
                set(value) {
                    this.$store.commit('AssetIssuanceReturn/updateRemarks', value);
                }
            },
            currentView() {
                return this.$store.getters['AssetIssuanceReturn/currentView'];
            }
        },
        mounted() {

        },
        methods: {
            closeModal() {
                this.$store.commit('AssetIssuanceReturn/setNewIssuanceReturnModalActive', false);
            },
            updateEmployee(employee) {
                this.$store.commit('AssetIssuanceReturn/updateEmployee', employee);
            },
            updateDepartment(department) {
                this.$store.commit('AssetIssuanceReturn/updateDepartment', department);
            },
            submitForm() {
                // The store redirects the user.
                this.$store.dispatch('AssetIssuanceReturn/submitNewIssuanceReturn');
            }
        },
    }
</script>
