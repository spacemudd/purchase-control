<style lang="sass">
.hidden
    display: none
</style>
<template>
    <div style="width:100%;">
        <b-collapse :open="show">
            <loading-screen v-if="loading"
                            style="min-height:450px;"></loading-screen>
            <iframe :src="url"
                    @load="loaded"
                    style="width:100%;min-height:450px;"
                    :class="{'hidden': loading}">
            </iframe>
        </b-collapse>
    </div>
</template>

<script>
    export default {
        props: {
            url: {
                type: String,
                required: true,
            }
        },
        data() {
            return {
                loading: true,
            }
        },
        computed: {
            show() {
                return this.$store.getters['PurchaseRequisition/previewPdf'];
            }
        },
        methods: {
            loaded() {
                this.loading = false;
            },
        }
    }
</script>
