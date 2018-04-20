<template>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">
                    {{ $t('words.inventory-auditing') }}
                </p>
            </header>
            <section class="modal-card-body">
                Exports all the <strong>locations</strong> with all assets attached to them.
            </section>
            <footer class="modal-card-foot">
                <button type="button"
                        class="button is-success"
                        :class="{'disabled is-loading': isLoading }"
                        :disable="isLoading"
                        @click="requestReport">
                    {{ $t('words.export') }}
                </button>
                <button type="button"
                        class="button"
                        @click="closeModal">{{ $t('words.close') }}
                </button>
            </footer>
        </div>
</template>

<script>
    import * as fileUtil from '../../../../utils/file.js';

    export default {
        data() {
            return {
                isLoading: false,
            }
        },
        mounted() {
            //
        },
        methods: {
            closeModal() {
                this.$emit('close');
            },
            requestReport() {
                this.isLoading = true;

                axios.post(this.apiUrl() + '/reports/inventory-auditing/print/locations', {},{responseType: 'arraybuffer'})
                .then(response => {
                    const blob = new Blob([response.data], {type: 'application/pdf'});
                    let filename = (response.headers['content-disposition'] || '').split('filename=')[1];
                    filename = filename.replace('"', '');
                    filename = filename.replace('"', '');
                    fileUtil.downloadBlob(blob, filename);

                    this.isLoading = false;
                }).catch(error => {
                    alert(error.response.data.message);
                    this.isLoading = false;
                });
            },
        }
    }
</script>
