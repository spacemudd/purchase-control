<template>
    <div class="button is-small">
        <span class="icon is-small">
            <i class="fa fa-upload"></i>
        </span>
        <span>
            Upload File
        </span>
    </div>
</template>

<script>
    export default {
        props: {
            /**
             * Indicates the Model name.
             * (App\Models\PurchaseOrder) => 'PurchaseOrder'
             */
            resourceName: {
                type: String,
                required: true,
            },
            resourceId: {
                type: Number,
                required: true,
            }
        },
        data() {
            return {
                //
            }
        },
        mounted() {
            //
        },
        methods: {
            //
        }
    }
</script>
