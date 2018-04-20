<template>
    <form action="">
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">Attachments</p>
            </header>
            <section class="modal-card-body">

                <loading-screen v-if="$isLoading('GETTING_ATTACHMENTS')" size="is-small"></loading-screen>
                <table class="table is-fullwidth" v-else>
                <thead>
                <tr>
                	<th>File</th>
                    <th>Size</th>
                    <th></th>
                </tr>
                </thead>
                	<tbody>
                			<tr v-for="attachment in attachments">
                				<td>{{ attachment.file_name }}</td>
                                <td>{{ roundUp(attachment.size / 1024, 2) }} KB</td>
                                <td class="has-text-right">
                                    <button @click.prevent="downloadMedia(attachment)" class="button is-small">
                                        Download
                                    </button>
                                </td>
                			</tr>
                	</tbody>
                </table>

                <hr> <!-- Uploading section. -->
                <progress v-if="isUploading"
                          style="margin-top: 50px;"
                          class="progress is-primary"
                          :value="progressValue"
                          max="100">{{ progressValue }}%</progress>
                <div class="has-text-centered" v-else>
                    <div class="columns">
                        <div class="column is-12">
                            <b-field>
                                <b-upload v-model="files">
                                    <a class="button is-primary">
                                        <b-icon icon="upload"></b-icon>
                                        <span>Click to upload</span>
                                    </a>
                                </b-upload>
                                <div v-if="files && files.length">
                            <span class="file-name">
                                {{ files[0].name }}
                            </span>
                                </div>
                            </b-field>

                            <b-field v-if="files && files.length">
                                <button class="button is-primary"
                                        @click.prevent="startUploadAttachment">
                                    Save
                                </button>
                            </b-field>
                        </div>
                    </div>
                </div>
            </section>
            <footer class="modal-card-foot">
                <button class="button" type="button" @click="$parent.close()">Close</button>
            </footer>
        </div>
    </form>
</template>

<script>
    import BField from "../../../../../node_modules/buefy/src/components/field/Field.vue";
    import BTable from "../../../../../node_modules/buefy/src/components/table/Table.vue";
    import * as fileUtil from '../../utils/file.js';

    export default {
        components: {
            BTable,
            BField},
        data() {
            return {
                files: [],
                progressValue: null,
                isUploading: false,
                isLoading: false,

                attachments: [],
                isEmpty: false,
            }
        },
        mounted() {
            this.getAttachments();
        },
        computed: {
            resourceName() {
                return this.$store.getters['FileUpload/resourceName'];
            },
            resourceId() {
                return this.$store.getters['FileUpload/resourceId'];
            },
        },
        methods: {
            getAttachments() {
                this.$startLoading('GETTING_ATTACHMENTS');
                axios.post(this.apiUrl() + '/' + this.resourceName + '/attachments', {
                    id: this.resourceId,
                }).then(response => {
                    this.attachments = response.data;
                    this.$endLoading('GETTING_ATTACHMENTS');
                }).catch(error => {
                    alert(error.response.data.message);
                    this.$endLoading('GETTING_ATTACHMENTS');
                });
            },
            startUploadAttachment() {
                this.uploadAttachment(this.onProgress);
            },
            onProgress(percent) {
                this.progressValue = percent;
            },
            uploadAttachment(onProgress) {
                let fileToUpload = new FormData();
                fileToUpload.append('attachment', this.files[0]);
                fileToUpload.append('resource_name', this.resourceName);
                fileToUpload.append('resource_id', Number(this.resourceId));

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

                axios.post(this.apiUrl() + '/upload/store', fileToUpload, config)
                    .then(response => {
                        this.$emit('completed');
                        this.isUploading = false;
                        this.files = [];
                        this.getAttachments();
                    }).catch(error => {
                    this.isUploading = false;
                    alert('Error occurred while uploading the attachment file.');
                })
            },

            downloadMedia(attachment)
            {
                this.$startLoading('DOWNLOAD_ATTACHMENT');
                axios.post(this.apiUrl() + '/' + this.resourceName + '/download-attachment', {
                    id: attachment.id,
                }, {responseType: 'arraybuffer'})
                    .then(response => {
                    let blob = new Blob([response.data], {type: response.headers['Content-Type']});
                    let filename = (response.headers['content-disposition'] || '').split('filename=')[1];
                    filename = filename.replace('"', '');
                    filename = filename.replace('"', '');
                    fileUtil.downloadBlob(blob, filename);
                    this.$endLoading('DOWNLOAD_ATTACHMENT');
                })
            },

            roundUp(num, precision) {
                precision = Math.pow(10, precision);
                return Math.ceil(num * precision) / precision;
            }
        }
    }
</script>
