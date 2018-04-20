<template>
    <div v-if="$isLoading('GETTING_FULL_DETAILS')">
        <loading-screen size='is-large'></loading-screen>
    </div>
    <div v-else>

        <div v-if="asset">

            <!-- Modals -->
            <b-modal :active.sync="showChangeStatusModal">
                <asset-change-status-modal></asset-change-status-modal>
            </b-modal>
            <asset-repair-modal v-if="showAssetRepairModal"
                                :assetId.number="assetId"
                                @closeModal="setRepairModalStatus(false)">
            </asset-repair-modal>
            <asset-change-location-modal v-if="showChangeLocationModal"
                                         :assetId.number="assetId"
                                         @closeModal="setChangeLocationModal(false)"
                                         @updateAsset="updateAsset">
            </asset-change-location-modal>

            <div class="box">
                <div class="columns">

                    <div class="column is-6">
                        <h1 class="title">{{ asset.asset_template.code }}</h1>
                        <p class="subtitle is-6">{{ $t('words.asset-code') }}</p>
                    </div>
                    <div class="column is-6 has-text-right">

                        <a class="button is-small" :href="asset.edit_link">
                            Edit
                        </a>

                        <button class="button is-small"
                                :disabled="!asset.can_return"
                                @click="confirmReturnToStock">
                            Return to Stock
                        </button>

                        <button class="button is-small"
                                :disabled="asset.can_return"
                                @click="setRepairModalStatus(true)">
                            Repair
                        </button>

                        <button class="button is-small" @click="setChangeLocationModal(true)">
                            Change Location
                        </button>

                        <!-- TODO: Because we moved 'Status' column, this doesnt work anymore. -->
                        <!-- TODO 2: Attempting to bring this back on. -->
                        <button class="button is-small is-warning" @click="openChangeStatusModal">
                            Change Status
                        </button>
                    </div>
                </div>
                <hr>
                <div class="columns">
                    <div class="column is-6">
                        <table class="table is-size-7 is-fullwidth">
                            <tbody>
                            <tr>
                                <td><strong>{{ $t('words.status') }}</strong></td>
                                <td>
                                    {{ asset.status.name }}
                                </td>
                            </tr>
                            <tr>
                                <td><strong>{{ $t('words.condition') }}</strong></td>
                                <td>{{ asset.condition }}</td>
                            </tr>
                            <tr>
                                <td><strong>{{ $t('words.description') }}</strong></td>
                                <td>{{ asset.asset_template.description }}</td>
                            </tr>
                            <tr>
                                <td><strong>{{ $t('words.location') }}</strong></td>
                                <td>{{ locationDisplayName }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="column is-6">
                        <table class="table is-size-7 is-fullwidth">
                            <tbody>
                                <tr>
                                    <td><strong>{{ $t('words.manufacturer-serial-number') }}</strong></td>
                                    <td>
                                        {{ asset.manufacturer_serial_number }}
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>{{ $t('words.system-tag-number') }}</strong></td>
                                    <td>{{ asset.system_tag_number }}</td>
                                </tr>
                                <tr>
                                    <td><strong>{{ $t('words.finance-tag-number') }}</strong></td>
                                    <td>{{ asset.finance_tag_number }}</td>
                                </tr>
                                <tr>
                                    <td><strong>{{ $t('words.purchase-order') }}</strong></td>
                                    <td>
                                        <a v-if="asset.purchase_order" :href="asset.purchase_order.link">
                                            {{ asset.purchase_order.order_number }} - PO Date: {{ asset.purchase_order.date_human }}
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Tabs -->
            <b-tabs v-model="activeTab">
                <!--
                <b-tab-item label="Work Requests">
                    <div class="section">
                        <asset-work-requests :assetId="assetId"></asset-work-requests>
                    </div>
                </b-tab-item>
                -->

                <b-tab-item label="Issuance Activity">
                    <issuance-activity></issuance-activity>
                </b-tab-item>

                <b-tab-item label="Work Orders">
                    <asset-work-orders :assetId="assetId"></asset-work-orders>
                </b-tab-item>

                <!--<b-tab-item label="Return Confirmations">
                    <div class="section">
                        <return-confirmations-index></return-confirmations-index>
                    </div>
                </b-tab-item>-->
                <b-tab-item label="Configuration" v-if="asset.asset_template.configurations.length > 0">
                    <asset-configurations :configurations="asset.asset_template.configurations">
                    </asset-configurations>
                </b-tab-item>
                <b-tab-item label="Log">
                    <audit-trail resource-type="assets"
                                 :resource-id.number="assetId">
                    </audit-trail>
                </b-tab-item>
            </b-tabs>

         </div>

    </div>
</template>

<script>
    import AssetChangeStatusModal from './../ChangeStatusModal/Modal.vue';
    import IssuanceActivity from './IssuanceActivity/Index.vue';
    import ReturnConfirmationsIndex from './ReturnConfirmationsIndex/Index.vue';
    import AssetRepairModal from './../AssetRepairModal/Modal.vue';
    import AssetWorkOrders from './AssetWorkOrders/WorkOrder.vue';
    import AssetWorkRequests from './AssetWorkRequests/WorkOrder.vue';
    import AssetChangeLocationModal from './../ChangeLocationModal/Modal.vue';
    import AssetConfigurations from './Configurations/Configurations.vue';

    export default {
        components: {
            AssetChangeStatusModal,
            IssuanceActivity,
            ReturnConfirmationsIndex,
            AssetRepairModal,
            AssetWorkOrders,
            AssetWorkRequests,
            AssetChangeLocationModal,
            AssetConfigurations,
        },
        props: {
            assetId: {
                required: true
            }
        },
        computed: {
            asset() {
                return this.$store.getters['Asset/asset'];
            },
            locationDisplayName() {
                if(this.asset) {
                    if(this.asset.location) {
                        return this.asset.location.code;
                    }
                }

                return null;
            },
            showChangeStatusModal: {
                get() {
                    return this.$store.getters['Asset/showChangeStatusModal'];
                },
                set(value) {
                    this.$store.commit('Asset/setChangedStatusModal', value);
                }
            }
        },
        data() {
            return {
                activeTab: 0,
                showAssetRepairModal: false,
                showChangeLocationModal: false,
            }
        },
        mounted() {
            this.$store.dispatch('Asset/getFullDetails', this.assetId);
        },
        methods: {
            openChangeStatusModal() {
                this.$store.commit('Asset/setChangedStatusModal', true);
            },
            setChangeLocationModal(state) {
                 this.showChangeLocationModal = state;
            },
            setRepairModalStatus(showState) {
                this.showAssetRepairModal = showState;
            },
            updateAsset() {
                this.$store.dispatch('Asset/getFullDetails', this.assetId);
            },
            confirmReturnToStock() {
                this.$dialog.confirm({
                    title: 'Return To Stock',
                    message: 'Are you sure to make a <b>new asset issuance return form</b> with this asset?',
                    confirmText: 'Return to Stock',
                    type: 'is-info',
                    hasIcon: true,
                    onConfirm: () => this.returnToStockRequest(),
                });
            },
            returnToStockRequest() {
                this.$startLoading('GETTING_FULL_DETAILS');

                axios.post(this.apiUrl() + '/assets/return-to-stock', {
                    id: this.assetId,
                }).then(response => {
                    window.location.href = response.data.link;
                }).catch(error => {
                    console.log(error.response.data);
                    alert('Error occurred while returning the asset. ' + error.response.data.message);
                    this.$endLoading('GETTING_FULL_DETAILS');
                })
            },
        }
    }
</script>

<style lang="sass">
    //
</style>
