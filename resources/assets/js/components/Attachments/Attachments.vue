<template>
    <div class="content">
        <loading-screen v-if="$isLoading('DOWNLOAD_ATTACHMENT')"></loading-screen>
        <table class="table is-fullwidth is-size-7" v-else>
            <colgroup>
                <col width="30%">
                <col width="30%">
                <col width="30%">
            </colgroup>
            <tbody>
                    <tr v-for="file in files">
                        <td>{{ file.file_name }}</td>
                        <th dir="ltr" class="has-text-right">{{ roundUp(file.size / 1024, 2) }} KB</th>
                        <th class="has-text-right">
                            <button @click.prevent="downloadMedia(file)" class="button is-small">
                                Download
                            </button>
                        </th>
                    </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    import * as fileUtil from '../../utils/file.js';

    export default {
        props: {
            files: {
                type: Array,
                required: true,
            },
            resourceName: {
                type: String,
                required: true,
            },
        },
        data() {
            return {
                expanded: false,
            }
        },
        mounted() {
            //
        },
        methods: {
            roundUp(num, precision) {
                precision = Math.pow(10, precision);
                return Math.ceil(num * precision) / precision;
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
        }
    }
</script>

<style lang="sass">
    //
</style>
