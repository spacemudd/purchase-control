<template>
    <button class="button is-small" @click="toggle()">
        <template v-if="open">
            Close
        </template>
        <template v-else>
            Print
        </template>
    </button>
</template>

<script>
    export default {
        data() {
            return {
                //
            }
        },
        mounted() {
            //
        },
        computed: {
            open() {
                return this.$store.getters['PurchaseOrder/previewPdf']
            },
        },
        methods: {
            toggle() {
                this.$store.commit('PurchaseOrder/togglePreviewPdf');
            },
        }
    }
</script>
