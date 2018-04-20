<template>
    <div>
        <p v-if="completed">
            Completed
        </p>
        <template v-else>
            <progress v-if="$isLoading('UPLOADING_SIGNATURE')"
                      class="progress is-primary"
                      :value="progressValue"
                      max="100">
                {{ progressValue }}%
            </progress>
            <template v-else>
                <b-field v-if="!droppedFile">
                    <b-upload v-model="droppedFile"
                              drag-drop>
                        <section class="section">
                            <div class="content has-text-centered">
                                <p>
                                    <b-icon
                                            icon="upload"
                                            size="is-large">
                                    </b-icon>
                                </p>
                                <p class="tag is-warning">Awaiting Signature</p>
                                <p class="help">Drop the file here or click to upload</p>
                            </div>
                        </section>
                    </b-upload>
                </b-field>
                <div class="tags">
                    <span v-for="(file, index) in droppedFile"
                          :key="index"
                          class="tag" >
                        {{file.name}}
                        <button class="delete is-small"
                                type="button"
                                @click="droppedFile=null">
                        </button>
                    </span>
                    <button v-if="droppedFile"
                            class="button is-success is-small"
                            :class="{'is-loading': $isLoading('UPLOADING_SIGNATURE')}"
                            @click="startUploading()">
                        Save
                    </button>
                </div>
            </template>
        </template>
    </div>
</template>

<script>
    export default {
        props: {
            /**
             * The API url endpoint to store the file.
             */
            endPoint: {
                type: String,
                required: true,
            },
            /**
             * The ID is sent along with API request.
             */
            resourceId: {
                type: Number,
                required: true,
            },
            /**
             * This string is sent along with API request.
             * E.g. 'engineer', 'finance-dept'...
             */
            signatureType: {
                type: String,
                required: false,
            },
        },
        data() {
            return {
                droppedFile: null,
                progressValue: null,
                completed: false,
            }
        },
        methods: {
            startUploading() {
                this.uploadFile(this.onProgress);
            },
            onProgress(percent) {
                this.progressValue = percent;
            },
            uploadFile(onProgress) {
                if(! this.droppedFile) {
                    alert('Missing file to upload');
                    return false;
                }

                let fileToUpload = new FormData();
                fileToUpload.append('id', this.resourceId);
                fileToUpload.append('file', this.droppedFile[0]);
                fileToUpload.append('type', this.signatureType);

                this.$startLoading('UPLOADING_SIGNATURE');

                var config = {
                    onUploadProgress: function(progressEvent) {
                        var percentCompleted = Math.round( (progressEvent.loaded * 100) / progressEvent.total );
                        if(onProgress) onProgress(percentCompleted);
                        return percentCompleted;
                    },
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    },
                };

                axios.post(this.apiUrl() + this.endPoint, fileToUpload, config)
                    .then(response => {
                        this.$emit('completed');
                        this.completed = true;
                        this.$endLoading('UPLOADING_SIGNATURE');
                    }).catch(error => {
                        alert('Error occurred while uploading the file.');
                        this.$endLoading('UPLOADING_SIGNATURE');
                })
            },
        }
    }
</script>
