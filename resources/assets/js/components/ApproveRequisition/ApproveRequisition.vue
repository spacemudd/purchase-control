<template>
    <span style="text-align:left">
        <b-modal :active.sync="ApproveRequisitionModal">
            <approve-requisition-modal :url="url"
                                       :search-approvers-url="searchApproversUrl">
            </approve-requisition-modal>
        </b-modal>
        <button class="button is-success is-small" @click="ApproveRequisitionModal=true">
            {{ buttonText }}
        </button>
    </span>
</template>

<script>
    export default {
        props: {
            /**
             * Where the request is saved.
             */
            url: {
                type: String,
                required: true,
            },
            buttonText: {
                type: String,
                required: false,
                default: 'Approve',
            },
            /**
             * The search endpoint for approvers.
             */
            searchApproversUrl: {
                type: String,
                required: true,
            }
        },
        data() {
            return {
                ApproveRequisitionModal: false,
            }
        },
        mounted() {

        },
        methods: {
            //
        }
    }
</script>
