<template>
    <div>
        <b-modal :active.sync="is_editing">
            <edit-supplier-token-modal :url="url"
                                       :name="name"
            ></edit-supplier-token-modal>
        </b-modal>
        <div :class="{'transparent-highlighter-bg': highlighted}" @click="is_editing=true">
            <span v-if="!current_value"><i>[{{ placeholder }}]</i></span>
            {{ current_value }}
        </div>
    </div>
</template>

<script>
    import EditSupplierTokenModal from "../EditSupplierTokenModal/EditSupplierTokenModal";

    export default {
        components: {EditSupplierTokenModal},
        props: {
            highlighted: {
                type: Boolean,
                default: false,
            },
            /**
             * An HTTP PUT method URL to save/current_value the token.
             */
            url: {
                type: String,
                required: true,
            },
            /**
             * The name of the token/field/column-name of the resource.
             */
            name: {
                type: String,
                required: true,
            },
            /**
             * The value.
             */
            value: {
                type: String,
                required: false,
            },
            placeholder: {
                type: String,
                default: 'TOKEN',
            },
        },
        computed: {
            po() {
                return this.$store.getters['PurchaseOrder/po'];
            },
        },
        data() {
            return {
                is_editing: false,

                current_value: null,
            }
        },
        mounted() {
            this.current_value = this.value;
        },
        methods: {

        }
    }
</script>
