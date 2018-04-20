<template>
    <div>
        <button class="button is-small" :class="{'is-loading': $isLoading('DOWNLOAD_ATTACHMENT')}" @click="download()">
            <span class="icon is-small"><i class="fa fa-download"></i></span>
            <span>
                <slot></slot>
            </span>
        </button>
    </div>
</template>

<script>
    import * as fileUtil from '../../utils/file.js';

    export default {
        props: {
            mediaId: {
                type: Number,
                required: true,
            },
        },
        data() {
            return {
                loading: false,
            }
        },
        methods: {
            download() {
                this.$startLoading('DOWNLOAD_ATTACHMENT');
                axios.post(this.apiUrl() + '/media/download', {
                    id: this.mediaId,
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
        }
    }
</script>
