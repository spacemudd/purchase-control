<template>
    <span>
        <input type="hidden" v-if="selectedPurchaseOrder" :name="name" :value="selectedPurchaseOrder.id">

        <input v-if="selectedPurchaseOrder"
               type="text"
               class="input"
               :value="selectedPurchaseOrder.number"
               @click="selectedPurchaseOrder=search=null"
               readonly>
        <!-- When searching -->
        <b-autocomplete
                    v-else
                    v-model="search"
                    :data="purchaseOrder"
                    :loading="isLoading"
                    field="code"
                    @input="getData"
                    @select="selectPurchaseOrder">
            <template slot="empty" v-if="!isLoading">No results found</template>
            <template slot-scope="props">
                <a class="dropdown-item">
                    {{ props.option.number }}
                </a>
            </template>
        </b-autocomplete>
    </span>
</template>

<script>
    import debounce from "lodash/debounce";

    export default {
        props: {
            /**
             * api endpoint to search for purchaseOrder.
             */
            url: {
                type: String,
                required: true,
            },
            name: {
                type: String,
                required: false,
            }
        },
        data() {
            return {
                isLoading: false,
                selectedPurchaseOrder: null,
                search: null,
                purchaseOrder: [],
            }
        },
        mounted() {

        },
        methods: {
            getData: debounce(function () {
                this.purchaseOrder = []
                this.isLoading = true
                axios.get(this.url + '?q=' + this.search)
                    .then(response => {
                        response.data.data.forEach((item) => this.purchaseOrder.push(item))
                        this.isLoading = false
                    })
                    .catch((error) => {
                        this.isLoading = false
                        throw error
                    })
            }, 500),
            selectPurchaseOrder(purchaseOrder) {
                this.selectedPurchaseOrder = purchaseOrder;
                this.$emit('selected', purchaseOrder);
            }
        }
    }
</script>
