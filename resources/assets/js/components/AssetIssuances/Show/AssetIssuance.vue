<template>
    <div class="box">
        <!-- Modals -->
        <b-modal :active.sync="showEditIssuanceModal">
            <edit-modal @close="showEditIssuanceModal=false"></edit-modal>
        </b-modal>

        <b-modal :active.sync="confirmDeleteModal">
            <confirm-modal @confirmed="deleteIssuance"
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

        <b-modal :active.sync="confirmVoidModal">
            <confirm-modal @confirmed="voidIssuance"
                           :message="$t('messages.void-confirmation')"
                           :button-text="$t('words.void')"
                           :card-title="$t('words.void')">
            </confirm-modal>
        </b-modal>
        <!-- // Modals -->
        <div class="columns">
            <div class="column is-12">
                <div class="columns">
                    <div class="column is-6">
                        <h1 class="title">{{ issuanceReferenceNumber }}</h1>
                        <p class="subtitle is-6">{{ $t('words.issuance-number') }}</p>
                    </div>
                    <div class="column is-6 has-text-right">

                        <button v-if="isDraft"
                                @click="editIssuance()"
                                class="button is-small is-warning">
                            <span class="icon is-small">
                                <i class="fa fa-pencil"></i>
                            </span>
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
                                v-if="issuanceId"
                                resource-name="asset-issuances"
                                :resource-id.number="issuanceId">
                        </view-all-files-button>

                        <b-tooltip v-if="isDraft"
                                label="Commit to print">
                            <button :disabled="isDraft"
                                    class="button is-small">
                                <span class="icon is-small">
                                    <i class="fa fa-print"></i>
                                </span>
                                <span>{{ $t('words.print') }}</span>
                            </button>
                        </b-tooltip>
                        <button class="button is-small"
                                :class="{'is-loading': isDownloading}"
                                @click="downloadIssuance()" v-else>
                            <span class="icon is-small">
                                <i class="fa fa-print"></i>
                            </span>
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

                        <button v-if="canVoid"
                                class="button is-small is-danger"
                                :class="{'is-loading': isVoiding}"
                                style="margin-left:20px;"
                                @click="confirmVoidModal = true">
                            <span class="icon is-small"><i class="fa fa-ban"></i></span>
                            <span>{{ $t('words.void') }}</span>
                        </button>
                    </div>
                </div>
                <hr>
            </div>
        </div>

        <div class="columns">
            <div class="column is-12">
                <div class="columns is-multiline">
                    <div class="column is-6">
                        <table class="table is-size-7 is-fullwidth">
                            <colgroup>
                                <col width=150>
                            </colgroup>
                            <tbody>
                                <tr>
                                    <td><strong>{{ $t('words.status') }}</strong></td>
                                    <td>
                                        <span :class="'tag tag-' + issuanceStatus" class="is-capitalized" style="height:unset;">
                                            {{ issuanceStatus }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>{{ $t('words.issuance-date') }}</strong></td>
                                    <td>{{ issuanceDate }}</td>
                                </tr>
                                <tr>
                                    <td><strong>{{ $t('words.return-date') }}</strong></td>
                                    <td>{{ issuanceReturnDate }}</td>
                                </tr>
                                <tr v-if="isCommitted">
                                    <td><strong>Engineer's Signature</strong></td>
                                    <td>
                                        <template v-if="issuance">
                                            <template v-if="issuance.eng_sig_id">
                                                <download-media-button :media-id="issuance.eng_sig_id">
                                                    Download
                                                </download-media-button>
                                            </template>
                                            <signature-upload-file v-else
                                                                   @completed="refreshPage()"
                                                                   :end-point="'/asset-issuances/signatures/store'"
                                                                   :resource-id="issuanceId"
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
                        <colgroup>
                            <col width=150>
                        </colgroup>
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
                                <td><strong>{{ $t('words.reference-number') }}</strong></td>
                                <td>{{ referenceNumber }} - {{ referenceNumberType }}</td>
                            </tr>
                            <tr>
                                <td><strong>{{ $t('words.details') }}</strong></td>
                                <td>{{ issuanceDetails }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import * as fileUtil from '../../../utils/file.js'
    import EditModal from '../EditIssuanceModal/Modal.vue';

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
                isVoiding: false,

                engineerSignatureFile: null,
            }
        },
        computed: {
            issuance() {
                return this.$store.getters['AssetIssuance/loadedIssuance'];
            },
            issuanceDetails() {
                return this.$store.getters['AssetIssuance/loadedDetails'];
            },
            issuanceReferenceNumber() {
                return this.$store.getters['AssetIssuance/loadedIssuanceReferenceNumber'];
            },
            isDraft() {
                return Boolean(this.$store.getters['AssetIssuance/loadedStatus'] === 'draft');
            },
            isCommitted() {
                return Boolean(this.$store.getters['AssetIssuance/loadedStatus'] === 'committed');
            },
            canVoid() {
                return Boolean(this.$store.getters['AssetIssuance/loadedStatus'] === 'committed');
            },
            issuanceStatus() {
                return this.$store.getters['AssetIssuance/loadedStatus'];
            },
            issuanceDate() {
                return this.$store.getters['AssetIssuance/loadedIssuanceDate'];
            },
            departmentDisplayName() {
                return this.$store.getters['AssetIssuance/loadedDepartmentDisplayName'];
            },
            employeeDisplayName() {
                return this.$store.getters['AssetIssuance/loadedEmployeeDisplayName'];
            },
            employee() {
                return this.$store.getters['AssetIssuance/loadedEmployee'];
            },
            referenceNumberType() {
                let type = this.$store.getters['AssetIssuance/loadedReferenceType'];

                if(type) {
                    return type.name;
                }
            },
            referenceNumber() {
                return this.$store.getters['AssetIssuance/loadedReferenceNumber'];
            },
            issuanceReturnDate() {
                return this.$store.getters['AssetIssuance/loadedReturnDate'];
            },
            issuanceId() {
                return this.$store.getters['AssetIssuance/loadedIssuanceSystemId'];
            },
            showEditIssuanceModal: {
                get() {
                    return this.$store.getters['AssetIssuance/showEditIssuanceModal'];
                },
                set(payload) {
                    return this.$store.commit('AssetIssuance/setEditIssuanceModalActive', payload);
                },
            },
        },
        mounted() {
            //
        },
        methods: {
            editIssuance() {
                this.$store.commit('AssetIssuance/setEditIssuanceModalActive', true);
            },
            commitIssuance() {

                    if( ! this.$store.getters['AssetIssuance/loadedItems'].length) {
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

                    let issuanceId = this.issuanceId;
                    this.confirmCommitModal = false;
                    this.isCommitting = true;

                    axios.post(this.apiUrl() + '/asset-issuances/commit', {
                        id: issuanceId,
                    }).then(response => {
                        this.$store.dispatch('AssetIssuance/getFullAssetIssuance', issuanceId);
                        this.isCommitting = false;
                    }).catch(error => {
                        alert(error.response.data.message);
                        this.isCommitting = false;
                    })

            },

            deleteIssuance() {

                let issuanceId = this.issuanceId;
                this.$startLoading('DELETING_ISSUANCE');

                axios.delete(this.apiUrl() + '/asset-issuances/' + issuanceId + '/delete')
                    .then(response => {
                    window.location.replace(this.baseUrl() + '/procedures/asset-issuances');
                }).catch(error => {
                    this.$dialog.alert({
                        'title': 'Error',
                        'message': 'Error occurred while deleting. ' + error.response.data,
                        'type': 'is-danger',
                    });
                    this.$endLoading('DELETING_ISSUANCE');
                })

            },

            voidIssuance() {
                let issuanceId = this.issuanceId;

                this.confirmVoidModal = false;
                this.isVoiding = true;

                axios.post(this.apiUrl() + '/asset-issuances/void', {
                    id: issuanceId,
                }).then(response => {
                    this.$store.dispatch('AssetIssuance/getFullAssetIssuance', issuanceId);

                    // this.isVoiding = false // we aren't doing this because the ^ dispatch is going to re-load the entire page.
                }).catch(error => {
                    alert(error.response.data.message);
                    this.isVoiding = false;
                })

            },

            downloadIssuance() {

                this.isDownloading = true;

                let params = {id: this.issuanceId}
                let options = {responseType: 'arraybuffer'}

                axios.post(this.apiUrl() + '/asset-issuances/print', params, options)
                    .then(response => {
                        this.isDownloading = false;

                        let blob = new Blob([response.data],  {type: 'application/pdf'});

                        fileUtil.downloadBlob(blob, this.issuanceReferenceNumber + '.pdf')
                    })
                    .catch(error => {

                        this.$dialog.alert({
                            'title': 'Error',
                            'message': 'Error downloading asset issuance - ' + this.issuanceReferenceNumber + '.pdf',
                            'type': 'is-danger',
                        });

                        console.log(error.response.data);

                        this.isDownloading = false;
                    })
            },

            refreshPage() {
                window.location.reload();
            },
        }
    }
</script>
