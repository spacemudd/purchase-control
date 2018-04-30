<!--
  - Copyright (c) 2018 - Clarastars, LLC - All Rights Reserved.
  -
  - Unauthorized copying of this file via any medium is strictly prohibited.
  - This file is a proprietary of Clarastars LLC and is confidential.
  -
  - https://clarastars.com - info@clarastars.com
  -
  -->

<template>
    <form @submit.prevent="startUploadAttachment">
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">{{ $t('words.upload') }}</p>
            </header>
            <section class="modal-card-body">
                <!-- Form Errors -->
                <div class="notification is-danger" v-if="form.errors.length > 0">
                    <strong>Something went wrong.</strong>
                    <br>
                    <ul>
                        <li v-for="error in form.errors">
                            {{ error }}
                        </li>
                    </ul>
                </div>

                <progress v-if="isUploading"
                          style="margin-top: 50px;"
                          class="progress is-primary"
                          :value="progressValue"
                          max="100">{{ progressValue }}%</progress>
                <div v-else>
                    <b-field :label="$t('words.description')" message="Description of the file(s)">
                        <b-input v-model="form.purpose"></b-input>
                    </b-field>
                    <b-field class="has-text-centered upload-box-position-unset">
                        <b-upload v-model="dropFiles"
                                  multiple
                                  drag-drop>
                            <section class="section">
                                <div class="content has-text-centered">
                                    <p>
                                        <b-icon
                                                icon="upload"
                                                size="is-large">
                                        </b-icon>
                                    </p>
                                    <p>{{ $t('messages.upload-help') }}</p>
                                </div>
                            </section>
                        </b-upload>
                    </b-field>

                    <div class="tags">
                    <span v-for="(file, index) in dropFiles"
                          :key="index"
                          class="tag is-primary" >
                        {{file.name}}
                        <button class="delete is-small"
                                type="button"
                                @click="deleteDropFile(index)">
                        </button>
                    </span>
                    </div>
                </div>
            </section>
            <footer class="modal-card-foot">
                <button class="button" type="button" v-if="!isUploading" @click="$parent.close()">Close</button>
                <button type="submit" class="button is-primary" :disabled="isUploading">{{ $t('words.save') }}</button>
            </footer>
        </div>
    </form>
</template>

<script>
    export default {
        props: {
            /**
             * The ID of the notable resource.
             */
            resourceId: {
                type: {
                    type: Number,
                    required: false,
                }
            },
            /**
             * The same GET/POST routes to save notes for a given resource.Damn
             */
            url: {
                type: String,
                required: true,
            },
        },
        data() {
            return {
                dropFiles: [],
                isLoading: false,
                isUploading: false,
                progressValue: null,

                form: {
                    purpose: '',
                    errors: [],
                }
            }
        },
        mounted() {
            //
        },
        methods: {
            deleteDropFile(index) {
                this.dropFiles.splice(index, 1)
            },

            startUploadAttachment() {
                if( ! this.dropFiles.length) {
                    this.$toast.open({
                        message: 'No files to upload',
                    })
                    return false;
                }

                this.uploadFile(this.onProgress);
            },
            onProgress(percent) {
                this.progressValue = percent;
            },
            uploadFile(onProgress) {
                let fileToUpload = new FormData();

                this.dropFiles.forEach((file) => {
                    fileToUpload.append('files[]', file);
                })

                fileToUpload.append('purpose', this.form.purpose);

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

                this.form.errors = [];

                axios.post(this.url, fileToUpload, config)
                    .then(response => {
                        this.form.errors = [];
                        this.$parent.close();
                        this.$emit('completed', response.data);
                    })
                    .catch(error => {
                        this.$endLoading('SAVING_UPLOADS');

                        if (typeof error.response.data === 'object') {
                            this.form.errors = _.flatten(_.toArray(error.response.data.errors));
                        } else {
                            this.form.errors = ['Something went wrong. Please try again.'];
                        }
                    });
            },
        }
    }
</script>
