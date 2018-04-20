<template>
    <div class="modal is-active" @keyup.esc="closeModal">
        <div class="modal-background" @click="closeModal"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">
                    {{ $t('words.upload') }}
                </p>
                <button class="delete" aria-label="close" @click="closeModal"></button>
            </header>
            <section class="modal-card-body">

                <div v-if="isLoading">
                    <loading-screen></loading-screen>

                    <progress style="margin-top: 50px;" class="progress is-primary" :value="progressValue" max="100">{{ progressValue }}%</progress>
                </div>
                <div v-else>
                    <b-field>
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
                                    <p>{{ $t('messages.drop-or-click-to-upload') }}</p>
                                </div>
                            </section>
                        </b-upload>
                    </b-field>
                    <div class="tags">
                                    <span v-for="(file, index) in droppedFile"
                                          :key="index"
                                          class="tag is-primary" >
                                        {{file.name}}
                                        <button class="delete is-small"
                                                type="button"
                                                @click="deleteDroppedFile(index)">
                                        </button>
                                    </span>
                    </div>
                </div>
            </section>
            <footer class="modal-card-foot" v-if="!isLoading">
                <button class="button is-success"
                        @click.prevent="startUploadAttachment">{{ $t('words.save') }}</button>
                <button class="button" @click="closeModal">{{ $t('words.cancel') }}</button>
            </footer>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                droppedFile: [],
                isLoading: false,
                progressValue: null,
            }
        },
        props: {
            confirmationId: {
                required: true,
            }
        },
        mounted() {
            //
        },
        methods: {
            closeModal() {
                if(!this.isLoading) {
                    this.$emit('close');
                }
            },
            deleteDroppedFile(index) {
                this.droppedFile.splice(index, 1);
            },
            startUploadAttachment() {
                this.uploadAttachment(this.onProgress);
            },
            onProgress(percent) {
                this.progressValue = percent;
            },
            uploadAttachment(onProgress) {


                let fileToUpload = new FormData();
                fileToUpload.append('attachment', this.droppedFile[0]);
                fileToUpload.append('id', this.confirmationId);

                this.isLoading = true;

                var config = {
                    onUploadProgress: function(progressEvent) {
                            var percentCompleted = Math.round( (progressEvent.loaded * 100) / progressEvent.total );
                            console.log(percentCompleted)
                            if(onProgress) onProgress(percentCompleted);
                            return percentCompleted;
                    },
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    },
                };

                axios.post(this.apiUrl() + '/return-confirmations/upload-signature', fileToUpload, config)
                    .then(response => {
                    this.$emit('completed');
                    this.isLoading = false;
                }).catch(error => {
                    this.isLoading = false;
                    alert('Error occurred while uploading the attachment file.');
                })
            },
        }
    }
</script>

<style lang="sass">
    //
</style>

