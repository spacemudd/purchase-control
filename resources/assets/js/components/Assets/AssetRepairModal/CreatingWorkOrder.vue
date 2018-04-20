<template>
    <div>
        <section class="modal-card-body">
            <loading-screen v-if="$isLoading('SENDING_WORK_ORDER')"></loading-screen>
            <div key="form" v-else>
                <div class="columns is-multiline">
                    <div class="column is-12">
                        <div class="field">
                            <label class="label">{{ $t('words.vendor') }} <span class="has-text-danger">*</span></label>
                            <div class="control">

                                <input v-if="vendor"
                                       :value="vendorDisplayName"
                                       type="text"
                                       class="input"
                                       @click="vendor = null"
                                       readonly>
                                <simple-search
                                        v-else
                                        :hyper-linked-results="false"
                                        :search-endpoint="'vendors'"
                                        :size="'is-small'"
                                        @selectedRecord="setVendor">
                                </simple-search>

                                <p class="help">
                                    Select the vendor that will repair the asset.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="column is-6">
                        <div class="field">
                            <label class="label">{{ $t('words.submission-date') }} <span class="has-text-danger">*</span></label>
                            <div class="control">
                                <template>
                                    <b-field>
                                        <b-datepicker
                                                placeholder="Type or select a date..."
                                                icon-pack="fa"
                                                icon="calendar"
                                                v-model="submissionDate"
                                                :readonly="false">
                                        </b-datepicker>
                                    </b-field>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <transition leave-active-class="animated fadeOut">
            <footer class="modal-card-foot">
                <button class="button is-success"
                        :class="{'is-loading': $isLoading('SENDING_WORK_ORDER')}"
                        @click.prevent="submitNewWorkOrder">{{ $t('words.save') }}</button>
                <button class="button" @click="closeModal">{{ $t('words.cancel') }}</button>
            </footer>
        </transition>
    </div>
</template>

<script>
    export default {
        props: {
            assetId: {
                require: true,
            }
        },
        data() {
            return {
                submissionDate: null,
            }
        },
        computed: {
            vendor: {
                get() {
                    return this.$store.getters['AssetRepairFacility/vendor'];
                },
                set(value) {
                    this.$store.commit('AssetRepairFacility/setVendor', value);
                }
            },
            vendorDisplayName() {
                if(this.vendor) {
                    return this.vendor.code + ' - ' + this.vendor.description;
                }
            },
        },
        mounted() {
            //
        },
        methods: {
            closeModal() {
                this.submissionDate = null;
                this.$emit('closeModal');
            },
            setVendor(vendor) {
                this.vendor = vendor;
            },
            submitNewWorkOrder() {
                if(!this.vendor) {
                    alert('Please select a vendor.');
                    return false;
                }

                if(!this.submissionDate) {
                    alert('Please select a submission date.');
                    return false;
                }

                this.$store.dispatch('AssetRepairFacility/submitNewWorkOrder', this.submissionDate).then(response => {
                    this.closeModal();
                    this.$store.dispatch('AssetRepairFacility/getFullDetails', this.assetId);
                });
            }
        }
    }
</script>

<style lang="sass">
    //
</style>
