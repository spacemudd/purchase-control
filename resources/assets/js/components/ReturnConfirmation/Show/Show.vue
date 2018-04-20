<template>

    <div v-if="isLoading">
        <loading-screen size="is-large"></loading-screen>
    </div>
    <div id="container" v-else>
        <div class="box">
        <div class="columns">
                <div class="column is-6">
                    <h1 class="title">{{ returnConfirmationNumber }}</h1>
                    <p class="subtitle is-6">{{ $t('words.return-confirmation-number') }}</p>
                </div>
                <div class="column is-6">
                    <button class="button is-success is-small is-pulled-right"
                            :class="{'is-loading': isDownloading}"
                            @click="downloadConfirmation()">
                                <span class="icon is-small">
                                    <i class="fa fa-print"></i>
                                </span>
                        <span>{{ $t('words.print') }}</span>
                    </button>
                </div>
            </div>
        </div>

        <div class="columns is-vcentered">
            <template v-if="attachments.length > 0">

                    <div class="column is-6">
                            <div class="signature-block has-text-centered">
                                <div class="svg-block">
                                    <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                 viewBox="0 0 72 48" style="enable-background:new 0 0 72 48;" xml:space="preserve">
                            <polyline style="fill:none;stroke:#000000;stroke-width:0.8944;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;" points="
                                47.5,28.8 47.5,45.5 18.5,45.5 18.5,3.5 47.5,3.5 47.5,25.5 "/>
                            <line style="fill:#FFFFFF;stroke:#000000;stroke-width:0.8944;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;" x1="21.5" y1="10.5" x2="29.5" y2="10.5"/>
                            <line style="fill:#FFFFFF;stroke:#000000;stroke-width:0.8944;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;" x1="41.5" y1="45.5" x2="21.5" y2="45.5"/>
                            <line style="fill:#FFFFFF;stroke:#000000;stroke-width:0.8944;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;" x1="41.5" y1="27" x2="21.5" y2="27"/>
                            <line style="fill:#FFFFFF;stroke:#000000;stroke-width:0.8944;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;" x1="21.5" y1="24" x2="44.5" y2="24"/>
                            <line style="fill:#FFFFFF;stroke:#000000;stroke-width:0.8944;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;" x1="21.5" y1="21" x2="44.5" y2="21"/>
                            <line style="fill:#FFFFFF;stroke:#000000;stroke-width:0.8944;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;" x1="21.5" y1="18" x2="44.5" y2="18"/>
                            <line style="fill:#FFFFFF;stroke:#000000;stroke-width:0.8944;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;" x1="21.5" y1="15" x2="44.5" y2="15"/>
                            <line style="fill:#FFFFFF;stroke:#000000;stroke-width:0.8944;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;" x1="21.5" y1="33.1" x2="31.5" y2="33.1"/>
                            <path style="fill:none;stroke:#006cff;stroke-width:0.8944;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;" d="
                                M21.6,41.2c0,0-0.5-3.8,2.2-5.6c2.7-1.8,2.7,2.7,2.7,2.7s-0.5,3.7-1.8,4.1c-1.3,0.5-1.1-1.9-0.9-2.9s0.8-2.4,1.6-2.8
                                c0.8-0.5,1.3-0.5,1.6-0.2c0.3,0.3,0.8,2.2,0.8,2.2s0.3-0.9,0.3-1.7c0-0.5,0.8,1.7,0.8,1.7s0.2-1.5,0.6-1.4c0.4,0,0.5,1.4,0.5,1.4
                                s0.2-1.4,0.6-1.4c0.3,0,0.3,0.7,0.5,1c0.2,0.2,0.5,0.7,0.8,0.8"/>
                            <polyline style="fill:none;stroke:#000000;stroke-width:0.8944;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;" points="
                                51.2,21.7 40.3,33.7 41.8,35 52.6,23.1 "/>
                            <polygon style="fill:none;stroke:#000000;stroke-width:0.8944;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;" points="
                                40.3,33.7 38.4,37.3 41.8,35 "/>
                            <path style="fill:none;stroke:#000000;stroke-width:0.8944;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;" d="
                                M53.1,23l4.2-4.6c0,0,0.8-0.9-0.1-1.8s-1.8,0.1-1.8,0.1l-4.2,4.6L53.1,23z"/>
                            <polyline style="fill:none;stroke:#000000;stroke-width:0.8944;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;" points="
                                56.7,19 57.9,20.1 52.6,25.9 "/>
                            <rect style="fill:none;" width="72" height="48"/>
                            </svg>
                                </div>
                            </div>
                    </div>

                    <div class="column is-6 has-text-centered">
                        <a v-for="file in attachments"
                           class="button is-primary"
                           :class="{'is-loading': isDownloadingAttachment}"
                           @click="downloadAttachment(file.id)"
                        >
                            Download Attachment
                        </a>
                    </div>

            </template>
            <template v-else>
                <div class="column is-12">
                        <upload-modal v-if="showUploadModal" :confirmation-id.number="confirmationId" @completed="completedUploading" @close="closeUploadModal"></upload-modal>
                        <div class="signature-block has-text-centered">
                            <div class="svg-block">
                                <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                 viewBox="0 0 72 48" style="enable-background:new 0 0 72 48;" xml:space="preserve">
                            <polyline style="fill:none;stroke:#000000;stroke-width:0.8944;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;" points="
                                47.5,28.8 47.5,45.5 18.5,45.5 18.5,3.5 47.5,3.5 47.5,25.5 "/>
                            <line style="fill:#FFFFFF;stroke:#000000;stroke-width:0.8944;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;" x1="21.5" y1="10.5" x2="29.5" y2="10.5"/>
                            <line style="fill:#FFFFFF;stroke:#000000;stroke-width:0.8944;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;" x1="41.5" y1="45.5" x2="21.5" y2="45.5"/>
                            <line style="fill:#FFFFFF;stroke:#000000;stroke-width:0.8944;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;" x1="41.5" y1="27" x2="21.5" y2="27"/>
                            <line style="fill:#FFFFFF;stroke:#000000;stroke-width:0.8944;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;" x1="21.5" y1="24" x2="44.5" y2="24"/>
                            <line style="fill:#FFFFFF;stroke:#000000;stroke-width:0.8944;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;" x1="21.5" y1="21" x2="44.5" y2="21"/>
                            <line style="fill:#FFFFFF;stroke:#000000;stroke-width:0.8944;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;" x1="21.5" y1="18" x2="44.5" y2="18"/>
                            <line style="fill:#FFFFFF;stroke:#000000;stroke-width:0.8944;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;" x1="21.5" y1="15" x2="44.5" y2="15"/>
                            <line style="fill:#FFFFFF;stroke:#000000;stroke-width:0.8944;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;" x1="21.5" y1="33.1" x2="31.5" y2="33.1"/>
                            <path style="fill:none;stroke:#006cff;stroke-width:0.8944;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;" d="
                                M21.6,41.2c0,0-0.5-3.8,2.2-5.6c2.7-1.8,2.7,2.7,2.7,2.7s-0.5,3.7-1.8,4.1c-1.3,0.5-1.1-1.9-0.9-2.9s0.8-2.4,1.6-2.8
                                c0.8-0.5,1.3-0.5,1.6-0.2c0.3,0.3,0.8,2.2,0.8,2.2s0.3-0.9,0.3-1.7c0-0.5,0.8,1.7,0.8,1.7s0.2-1.5,0.6-1.4c0.4,0,0.5,1.4,0.5,1.4
                                s0.2-1.4,0.6-1.4c0.3,0,0.3,0.7,0.5,1c0.2,0.2,0.5,0.7,0.8,0.8"/>
                            <polyline style="fill:none;stroke:#000000;stroke-width:0.8944;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;" points="
                                51.2,21.7 40.3,33.7 41.8,35 52.6,23.1 "/>
                            <polygon style="fill:none;stroke:#000000;stroke-width:0.8944;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;" points="
                                40.3,33.7 38.4,37.3 41.8,35 "/>
                            <path style="fill:none;stroke:#000000;stroke-width:0.8944;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;" d="
                                M53.1,23l4.2-4.6c0,0,0.8-0.9-0.1-1.8s-1.8,0.1-1.8,0.1l-4.2,4.6L53.1,23z"/>
                            <polyline style="fill:none;stroke:#000000;stroke-width:0.8944;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;" points="
                                56.7,19 57.9,20.1 52.6,25.9 "/>
                            <rect style="fill:none;" width="72" height="48"/>
                            </svg>
                            </div>
                            <div class="block">
                                <button class="button is-primary" @click="openUploadModal">
                                    {{ $t('words.upload') }}
                                </button>
                            </div>
                        </div>
                </div>
            </template>
        </div>
    </div>
</template>

<script>
    import * as fileUtil from '../../../utils/file.js'
    import UploadModal from './UploadAttachment.vue';

    export default {
        components: {
            UploadModal,
        },
        props: {
            confirmationId: {
                require: true,
            }
        },
        mounted() {
            this.loadConfirmation();
        },
        computed: {
            returnConfirmationNumber() {
                return this.confirmation.id_human;
            },
            attachments() {
                return this.confirmation.attachments;
            }
        },
        data() {
            return {
                isLoading: true,
                isDownloading: false,
                confirmation: null,
                showUploadModal: false,
                isDownloadingAttachment: false,
            }
        },
        methods: {
            loadConfirmation() {
                axios.get(this.apiUrl() + '/return-confirmations/' + this.confirmationId).then(response => {
                    this.confirmation = response.data;
                    this.isLoading = false;
                });
            },
            openUploadModal() {
                this.showUploadModal = true;
            },
            closeUploadModal() {
                this.showUploadModal = false;
            },
            completedUploading() {
                this.closeUploadModal();
                this.loadConfirmation();
            },
            downloadConfirmation() {
                this.isDownloading = true;

                let confirmationID = this.confirmationId;

                axios.post(this.apiUrl() + '/procedures/print-asset-return-confirm/print', {
                    type: 'pdf',
                    confirmation_id: confirmationID,
                }, {responseType: 'arraybuffer'})
                    .then(response => {

                        this.isDownloading = false;

                        let blob = new Blob([response.data],  {type: 'application/pdf'});

                        fileUtil.downloadBlob(blob, this.confirmation.id_human + '.pdf')
                    })
                    .catch(error => {
                        console.log(error);
                        this.isDownload = false;
                        alert('Error downloading your requested resource');
                    })
            },
            downloadAttachment(fileId) {

                this.isDownloadingAttachment = true;
                axios.post(this.apiUrl() + '/asset-return-confirms/attachments/file', {id: fileId}, {responseType: 'arraybuffer'})
                    .then(response => {
                    this.isDownloadingAttachment = false;
                    let blob = new Blob([response.data], {type: response.headers['Content-Type']});
                    let filename = (response.headers['content-disposition'] || '').split('filename=')[1];
                    filename = filename.replace('"', '');
                    filename = filename.replace('"', '');
                    fileUtil.downloadBlob(blob, filename);
                }).catch(error => {
                    alert('Error occurred while downloading the attachment.');
                    this.isDownloadingAttachment = false;
                })
            }
        }
    }
</script>

<style lang="sass">
    .signature-block
        width: 200px
        margin: auto
        .svg-block
            padding-left: 15px
    html
        overflow-x: hidden

    .upload-draggable
        width: 100%
</style>
