<template>
    <div>
        <div class="resources-sidebar">
            <h3 class="title is-4">{{ $t('words.files') }}</h3>
            <div>
                <button class="button is-small is-warning" @click="newUploadModal=true">{{ $t('words.new-file') }}</button>
            </div>
        </div>

        <!-- New upload modal -->
        <b-modal :active.sync="newUploadModal">
            <new-upload-modal :url="url" :resource-id="resourceId" @completed="addFilesToList"></new-upload-modal>
        </b-modal>

         <!-- Where the uploads are displayed -->
        <div class="uploads-container">
            <transition-group name="uploads-container" enter-active-class="animated fadeInDown" leave-active-class="animated fadeOut" mode="in-out" v-if="uploads.length">
                <div v-bind:key="upload.id" class="media-content" v-for="upload in uploads.slice().reverse()">
                    <div class="content">
                        <p v-if="upload.purpose" class="tag"><strong>{{ upload.purpose }}</strong></p>
                        <p>
                            {{ upload.file_name }}
                            <br>
                            <br>
                            <small>
                                <strong :title="upload.user.username">
                                    {{ upload.user.name }}
                                </strong>
                                &#8226;
                                <time :title="upload.created_rss"
                                      :datetime="upload.created_w3c"
                                      class="has-text-grey-light">
                                    {{ upload.created_at_human }}
                                </time>
                                &#8226;
                                <button class="button is-small is-text has-text-link" :class="{'is-loading': $isLoading('DOWNLOADING_FILE_' + upload.id)}" @click="downloadFile(upload.id)">Download</button>
                                <button class="button is-small is-danger is-pulled-right has-icon" @click="confirmDelete(upload.id)">
                                    <span class="icon"><i class="fa fa-trash"></i></span>
                                </button>
                            </small>
                        </p>
                        <hr>
                    </div>
                </div>
            </transition-group>
            <div style="min-height: 100px;" class="is-flex columns is-vcentered" v-if="uploads.length === 0">
                <p class="column has-text-centered"><i>No files</i></p>
            </div>
        </div>
    </div>
</template>

<style scoped>
    .uploads-container-move {
        transition: transform 1s;
    }
</style>

<script>
    import * as fileUtil from '../../utils/file.js'
    export default {
        props: {
            /**
             * The ID of the mediable resource.
             */
            resourceId: {
                type: {
                    type: Number,
                    required: false,
                }
            },
            /**
             * The same GET/POST routes to save files for a given resource.
             */
            url: {
                type: String,
                required: true,
            },
        },
        data() {
            return {
                newUploadModal: false,

                uploads: [],


            }
        },
        mounted() {
            this.getUploads();
        },
        methods: {
            /**
             * Get all of the uploads for resource.
             */
            getUploads() {
                axios.get(this.url)
                    .then(response => {
                        this.uploads = response.data;
                    });
            },
            /**
             * Adds new files to the list.
             * array files
             */
            addFilesToList(files) {
                files.forEach(file => {
                    this.uploads.push(file)
                })

            },
            downloadFile(id) {
                let params = {id: id}
                let options = {responseType: 'arraybuffer'}

                this.$startLoading('DOWNLOADING_FILE_' + id);

                axios.post(this.apiUrl() + '/media/download', params, options)
                    .then(response => {
                        let blob = new Blob([response.data], {type: response.headers['Content-Type']});
                        let filename = (response.headers['content-disposition'] || '').split('filename=')[1];
                        filename = filename.replace('"', '');
                        filename = filename.replace('"', '');
                        fileUtil.downloadBlob(blob, filename);

                        this.$endLoading('DOWNLOADING_FILE_' + id);
                    })
                    .catch(error => {
                        alert('Something went wrong. Please try again.');
                    })
            },
            confirmDelete(id) {
                this.$dialog.confirm({
                    type: 'is-danger',
                    message: 'This is irreversible. Are you sure you want to delete the file?',
                    confirmText: 'Delete File',
                    onConfirm: () => {
                        axios.delete(this.apiUrl() + '/media/' + id)
                            .then(response => {
                                this.getUploads();
                            })
                    }
                })
            }
        }
    }
</script>
