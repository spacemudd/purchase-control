<template>
    <span class="has-text-left">
        <div class="button is-small" @click="isAttachmentsModalActive = true">
            <span class="icon is-small">
                <i class="fa fa-upload"></i>
            </span>
            <span>
                Attachments
            </span>
        </div>

        <b-modal :active.sync="isAttachmentsModalActive" has-modal-card>
            <attachments-modal></attachments-modal>
        </b-modal>
    </span>
</template>

<script>
    import AttachmentsModal from './ViewAllModal.vue';

    export default {
        props: {
            resourceName: {
                type: String,
                required: true,
            },
            resourceId: {
                type: Number,
                required: true,
            }
        },
        components: {
            AttachmentsModal,
        },
        data() {
            return {
                isAttachmentsModalActive: false,
            }
        },
        mounted() {
            this.$store.commit('FileUpload/setResourceName', this.resourceName);
            this.$store.commit('FileUpload/setResourceId', this.resourceId);
        },
        methods: {
            //
        }
    }
</script>
