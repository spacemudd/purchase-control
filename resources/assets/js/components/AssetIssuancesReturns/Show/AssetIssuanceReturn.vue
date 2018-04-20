<template>
    <div class="box">
        <!-- Modals -->
        <b-modal :active.sync="showEditModal">
            <edit-modal @close="showEditModal=false"></edit-modal>
        </b-modal>

        <b-modal :active.sync="confirmDeleteModal">
            <confirm-modal @confirmed="deleteIssuanceReturn"
                           message="This action cannot be done. The items will return to stock. Are you sure?"
                           button-text="Yes"
                           card-title="Delete Confirm">
            </confirm-modal>
        </b-modal>

        <b-modal :active.sync="confirmCommitModal">
            <confirm-modal @confirmed="commitIssuance"
                           :message="$t('messages.commit-confirmation')"
                           :button-text="$t('words.commit')"
                           :card-title="$t('words.commit')">
            </confirm-modal>
        </b-modal>
        <!-- // Modals -->

        <div class="columns">
            <div class="column is-12">
                <div class="columns">
                    <div class="column is-6">
                        <h1 class="title">{{ issuanceReturnCode }}</h1>
                        <p class="subtitle is-6">{{ $t('words.issuance-return-number') }}</p>
                    </div>
                    <div class="column is-6 has-text-right">

                        <button v-if="isDraft" type="button" class="button is-small is-warning" @click="showEditModal=true">
                            <span class="icon is-small"><i class="fa fa-pencil"></i></span>
                            <span>{{ $t('words.edit') }}</span>
                        </button>

                        <button v-if="isDraft"
                                class="button is-small"
                                :class="{'is-loading': $isLoading('DELETING_ASSET_ISSUANCE')}"
                                @click="confirmDeleteModal = true">
                            <span class="icon is-small"><i class="fa fa-trash"></i></span>
                            <span>{{ $t('words.delete') }}</span>
                        </button>

                        <view-all-files-button
                                v-if="issuanceReturnId"
                                resource-name="asset-issuances-returns"
                                :resource-id.number="issuanceReturnId">
                        </view-all-files-button>

                        <b-tooltip v-if="isDraft"
                                   label="Commit to print">
                            <button :disabled="isDraft"
                                    class="button is-small">
                                <span class="icon is-small"><i class="fa fa-print"></i></span>
                                <span>{{ $t('words.print') }}</span>
                            </button>
                        </b-tooltip>
                        <button class="button is-small"
                                :class="{'is-loading': isDownloading}"
                                @click="downloadIssuanceReturn()" v-else>
                            <span class="icon is-small"><i class="fa fa-print"></i></span>
                            <span>{{ $t('words.print') }}</span>
                        </button>

                        <button v-if="isDraft"
                                class="button is-small is-success"
                                :class="{'is-loading': isCommitting}"
                                style="margin-left:20px;"
                                @click="confirmCommitModal = true">
                            <span class="icon is-small"><i class="fa fa-check"></i></span>
                            <span>{{ $t('words.commit') }}</span>
                        </button>
                    </div>
                </div>
                <hr>
            </div>
        </div>

        <div class="columns">
            <div class="column is-6">
                <table class="table is-size-7 is-fullwidth">
                    <tbody>
                        <tr>
                            <td><strong>{{ $t('words.status') }}</strong></td>
                            <td>
                                <span :class="'tag tag-' + issuanceReturnStatus" class="is-capitalized" style="height:unset;">
                                    {{ issuanceReturnStatus }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>{{ $t('words.return-date') }}</strong></td>
                            <td>{{ issuanceReturnDate }}</td>
                        </tr>
                        <tr v-if="isCommitted">
                            <td><strong>Engineer's Signature</strong></td>
                            <td>
                                <template v-if="issuanceReturn">
                                    <template v-if="issuanceReturn.eng_sig_id">
                                        <download-media-button :media-id="issuanceReturn.eng_sig_id">
                                            Download
                                        </download-media-button>
                                    </template>
                                    <signature-upload-file v-else
                                                           @completed="refreshPage()"
                                                           :end-point="'/asset-issuances-returns/signatures/store'"
                                                           :resource-id="issuanceReturnId"
                                                           signature-type="engineer">
                                    </signature-upload-file>
                                </template>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="column is-6">
                <table class="table is-size-7 is-fullwidth">
                    <tbody>
                        <tr>
                            <td><strong>{{ $t('words.department') }}</strong></td>
                            <td>{{ departmentDisplayName }}</td>
                        </tr>
                        <tr>
                            <td><strong>{{ $t('words.employee') }}</strong></td>
                            <td v-if="employee">
                                <a :href="baseUrl() + '/employees/' + employee.id">
                                    {{ employeeDisplayName }}
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>{{ $t('words.remarks') }}</strong></td>
                            <td>{{ remarks }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    import * as fileUtil from '../../../utils/file.js'
    import EditModal from '../EditIssuanceReturnModal/Modal.vue';

    export default {
        components: {
            EditModal,
        },
        data() {
            return {
                isDownloading: false,

                confirmCommitModal: false,
                isCommitting: false,

                confirmDeleteModal: false,

                confirmVoidModal: false,
            }
        },
        computed: {
            issuanceReturn() {
                return this.$store.getters['AssetIssuanceReturn/loadedIssuanceReturn'];
            },
            issuanceReturnCode() {
                return this.$store.getters['AssetIssuanceReturn/loadedIssuanceReturnCode'];
            },
            isDraft() {
                return Boolean(this.$store.getters['AssetIssuanceReturn/loadedStatus'] === 'draft');
            },
            isCommitted() {
                return Boolean(this.$store.getters['AssetIssuanceReturn/loadedStatus'] === 'committed');
            },
            issuanceReturnStatus() {
                return this.$store.getters['AssetIssuanceReturn/loadedStatus'];
            },
            departmentDisplayName() {
                return this.$store.getters['AssetIssuanceReturn/loadedDepartmentDisplayName'];
            },
            employeeDisplayName() {
                return this.$store.getters['AssetIssuanceReturn/loadedEmployeeDisplayName'];
            },
            employee() {
                return this.$store.getters['AssetIssuanceReturn/loadedEmployee'];
            },
            issuanceReturnDate() {
                return this.$store.getters['AssetIssuanceReturn/loadedIssuanceReturnDate'];
            },
            issuanceReturnId() {
                return this.$store.getters['AssetIssuanceReturn/loadedIssuanceReturnSystemId'];
            },
            remarks() {
               return this.$store.getters['AssetIssuanceReturn/loadedRemarks'];
            },
            showEditModal: {
                get() {
                    return this.$store.getters['AssetIssuanceReturn/showEditModal'];
                },
                set(payload) {
                    return this.$store.commit('AssetIssuanceReturn/setEditModal', payload);
                },
            },
        },
        mounted() {
            //
        },
        methods: {
            commitIssuance() {

                if( ! this.$store.getters['AssetIssuanceReturn/loadedItems'].length) {
                    this.$dialog.alert({
                        title: 'Error',
                        message: 'No assets were <b>attached</b>.',
                        type: 'is-info',
                        hasIcon: true,
                        icon: 'info-circle',
                        iconPack: 'fa'
                    });

                    return false;
                }

                    let issuanceReturnId = this.issuanceReturnId;
                    this.confirmCommitModal = false;
                    this.isCommitting = true;

                    axios.post(this.apiUrl() + '/asset-issuances-return/commit', {
                        id: issuanceReturnId,
                    }).then(response => {
                        this.$store.dispatch('AssetIssuanceReturn/getFullAssetIssuanceReturn', issuanceReturnId);
                        this.isCommitting = false;
                    }).catch(error => {
                        alert(error.response.data.message);
                        this.isCommitting = false;
                    })

            },

            deleteIssuanceReturn() {

                this.$startLoading('DELETING_ISSUANCE_RETURN');

                axios.delete(this.apiUrl() + '/asset-issuance-returns/' + this.issuanceReturnId + '/delete')
                    .then(response => {
                        window.location.replace(this.baseUrl() + '/issuance-returns');
                    }).catch(error => {
                    this.$dialog.alert({
                        'title': 'Error',
                        'message': 'Error occurred while deleting. ' + error.response.data,
                        'type': 'is-danger',
                    });
                    this.$endLoading('DELETING_ISSUANCE_RETURN');
                })

            },

            downloadIssuanceReturn() {

                this.isDownloading = true;

                let params = {id: this.issuanceReturnId};
                let options = {responseType: 'arraybuffer'};

                axios.post(this.apiUrl() + '/asset-issuances-returns/print', params, options)
                    .then(response => {

                        this.isDownloading = false;

                        let blob = new Blob([response.data],  {type: 'application/pdf'});

                        fileUtil.downloadBlob(blob, this.issuanceReturnCode + '.pdf')

                    })
                    .catch(error => {

                        console.log(error.response.data.message);

                        alert('Error downloading asset issuance - ' + this.issuanceReferenceNumber + '.pdf');

                        this.isDownloading = false;

                    })
            },

            refreshPage() {
                window.location.reload();
            },
        }
    }
</script>

<style lang="sass">
    //
</style>
