<template>
    <div>
        <div class="modal-card" style='height: auto'>
            <header class="modal-card-head">
                <p class="modal-card-title">Scan</p>
            </header>
        </div>

        <help-dialog v-if="scanners.length<1"></help-dialog>
        <section class="modal-card-body" v-else>
            <progress v-if="isUploading"
                      style="margin-top: 50px;"
                      class="progress is-primary"
                      :value="progressValue"
                      max="100">{{ progressValue }}%</progress>
            <div class="columns is-multiline" v-else>
                <div class="column is-8">
                    <div class="columns is-multiline">
                        <div class="column is-12">
                            <div class="box-styled yellow-box" style="padding:10px;min-height:255px">
                                <div class="columns is-multiline">
                                    <template v-for="(image, key) in images">
                                        <div class="column is-3">
                                            <img :src="'data:image/jpeg;base64,' + image" @click="selectImage(image)">
                                            <p class="has-text-centered">
                                                <button class="button is-text is-small"
                                                        @click.prevent="removeImage(image, key)">
                                                    X
                                                </button>
                                            </p>
                                        </div>
                                        <!--
                                        <template v-if="twainSelectOdd">
                                            <div class="column is-3" v-if="key % 2">
                                                <img :src="'data:image/jpeg;base64,' + image" @click="selectImage(image)">
                                            </div>
                                        </template>
                                        <template v-else>
                                            <div class="column is-3" v-if="!(key % 2)">
                                                <img :src="'data:image/jpeg;base64,' + image"  @click="selectImage(image)">
                                            </div>
                                        </template>
                                        -->
                                    </template>
                                </div>
                            </div>
                        </div>

                        <div class="column is-12 has-text-centered">
                            {{ images.length }} images
                        </div>
                    </div>
                </div>

                <div class="column is-4">
                    <b-field label="Scanner">
                        <b-select placeholder="Select a scanner" size="is-small" v-model="selectedScanner" expanded>
                            <option v-for="scanner in scanners" :value="scanner">{{ scanner }}</option>
                        </b-select>
                    </b-field>
                    <b-field label="Resolution">
                        <b-select v-model="twainResolution" size="is-small" expanded>
                            <option :value="100">100</option>
                            <option :value="150">150</option>
                            <option :value="200">200</option>
                            <option :value="300">300</option>
                        </b-select>
                    </b-field>
                    <div class="field">
                        <b-checkbox v-model="twainDuplex" size="is-small">Duplex</b-checkbox>
                    </div>
                    <div class="block">
                        <b-radio v-model="twainPixelType"
                                 size="is-small"
                                 native-value="bw">
                            B&W
                        </b-radio>
                        <b-radio v-model="twainPixelType"
                                 size="is-small"
                                 native-value="gray">
                            Gray
                        </b-radio>
                        <b-radio v-model="twainPixelType"
                                 size="is-small"
                                 native-value="color">
                            Color
                        </b-radio>
                    </div>
                    <button @click.prevent="beginMultiScanning()"
                            :class="{'is-loading': isScanning}"
                            class="button is-warning is-small">
                        Scan
                    </button>
                </div>

                <b-modal :active.sync="isImageModalActive">
                    <p class="image">
                        <img :src="'data:image/jpeg;base64,' + selectedImage">
                    </p>
                </b-modal>

                <input type="hidden" name="images" :value="JSON.stringify(images)">
            </div>
        </section>

        <footer class="modal-card-foot">
            <button class="button" type="button" @click="$parent.close()">Close</button>
            <button class="button is-success" @click="beginUploading()" :disabled="images.length<1">Save</button>
        </footer>
    </div>
</template>

<script>
    import HelpDialog from './HelpDialog.vue';

    export default {
        components: {
            HelpDialog,
        },
        props: {

            /**
             * Model database ID.
             */
            modelId: {
                type: Number,
                required: true,
            },

            /**
             * Model polymorphism.
             */
            modelType: {
                type: String,
                required: true,
            },

        },
        watch: {
            scanners() {
                let selectedScannerCookie = this.$cookie.get('selected-scanner');
                if(selectedScannerCookie) {
                    this.selectedScanner = selectedScannerCookie;
                }
            }
        },
        data() {
            return {
                corsCall: null,
                scanners: [],
                selectedScanner: null,

                twainResolution: 300,
                twainDuplex: false,
                twainPixelType: 'color',
                showHelpDialog: false,
                isScanning: false,
                images: [],

                isImageModalActive: false,
                selectedImage: null,

                isUploading: false,
                progressValue: null,
            }
        },
        mounted() {
            this.corsCall = axios.create();
            this.corsCall.defaults.headers.common = {};

            this.getAvailableScanners();
        },
        methods: {
            getAvailableScanners() {
                this.corsCall.get(this.scannerUrl() + '/get-scanners').then(response => {
                    this.scanners = response.data;
                });
            },
            /**
             * Sends scan request to the web agent.
             *
             */
            beginScan()
            {
                this.images = [];
                this.isScanning = true;
                this.corsCall.get(this.scannerUrl() + '/scan?scanner=' + this.selectedScanner)
                    .then((result) => {
                        this.saveSelectedScannerCookie();
                        this.isScanning = false;

                        this.images.push(result.data);
                    })
//                    .catch(() => {
//                        this.isScanning = false;
//                        this.$dialog.alert({
//                            title: 'Error',
//                            message: "Couldn't communicate with the scanner.",
//                            type: 'is-danger',
//                            hasIcon: true,
//                            icon: 'ban',
//                            iconPack: 'fa',
//                            confirmText: 'Ok',
//                        })
//                    });
            },

            beginMultiScanning()
            {
                if( ! this.selectedScanner) {
                    this.$dialog.alert({
                        title: 'Notice',
                        message: 'Please select a scanner to begin',
                        type: 'is-info',
                        confirmText: 'Ok',
                    });

                    return false;
                }

                this.saveSelectedScannerCookie();
                this.isScanning = true;
                this.corsCall.get(this.scannerUrl() + '/multi-scan?scanner=' + this.selectedScanner
                    + "&resolution=" + this.twainResolution
                    + "&pixel-type=" + this.twainPixelType
                    + "&duplex=" + this.twainDuplex)
                    .then((result) => {
                        this.isScanning = false;

                        let scannedImages = result.data.images;
                        if(scannedImages) {
                            if(scannedImages.length) {
                                this.images.push(scannedImages);
                            }
                        }
                    })
                    .catch(() => {
                        this.isScanning = false;
                        this.$dialog.alert({
                            title: 'Error',
                            message: "Couldn't communicate with the scanner.",
                            type: 'is-danger',
                            hasIcon: true,
                            icon: 'ban',
                            iconPack: 'fa',
                            confirmText: 'Ok',
                        })
                    });
            },

            saveSelectedScannerCookie() {
                this.$cookie.set('selected-scanner', this.selectedScanner);
            },

            /**
             *
             * @param image String base64 encoded jpg
             */
            selectImage(image) {
                this.selectedImage = image;
                this.isImageModalActive = true;
            },

            onProgress(percent) {
                this.progressValue = percent;
            },
            beginUploading(onProgress) {
                let fileToUpload = new FormData();

                let images = this.images.map((image) => {
                    atob(image, Math.random() +'.jpg');
                });

                fileToUpload.append('model_name', this.modelType);
                fileToUpload.append('model_id', Number(this.modelId));

                fileToUpload.append('images', images);

                this.isUploading = true;

                var config = {
                    onUploadProgress: function(progressEvent) {
                        var percentCompleted = Math.round( (progressEvent.loaded * 100) / progressEvent.total );
                        console.log(percentCompleted);
                        if(onProgress) onProgress(percentCompleted);
                        return percentCompleted;
                    },
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    },
                };

//                axios.post(this.apiUrl() + '/upload/store-images-to-pdf', fileToUpload, config)
//                    .then(response => {
//                        this.$emit('completed');
//                        this.isUploading = false;
//                        this.files = [];
//                    })
            },

            removeImage(image, key)
            {
                this.images.splice(key, 1);
            }
        }
    }
</script>

<style>
    .yellow-box {
        border: 2px solid #ffdd57;
    }
</style>
