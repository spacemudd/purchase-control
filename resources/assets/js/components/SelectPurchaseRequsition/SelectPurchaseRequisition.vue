<template>
    <span>
        <input type="hidden" v-if="selectedPurchaseRequisition" :name="name" :value="selectedPurchaseRequisition.id">

        <input v-if="selectedPurchaseRequisition"
               type="text"
               class="input"
               :value="selectedPurchaseRequisition.number"
               @click="selectedPurchaseRequisition=search=null"
               readonly>
        <!-- When searching -->
        <b-autocomplete
                    v-else
                    v-model="search"
                    :data="purchaseRequisition"
                    :loading="isLoading"
                    field="code"
                    @input="getData"
                    @select="selectPurchaseRequisition">
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
             * api endpoint to search for purchaseRequisition.
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
                selectedPurchaseRequisition: null,
                search: null,
                purchaseRequisition: [],
            }
        },
        mounted() {

        },
        methods: {
            getData: debounce(function () {
                this.purchaseRequisition = []
                this.isLoading = true
                axios.get(this.url + '?q=' + this.search)
                    .then(response => {
                        response.data.data.forEach((item) => this.purchaseRequisition.push(item))
                        this.isLoading = false
                    })
                    .catch((error) => {
                        this.isLoading = false
                        throw error
                    })
            }, 500),
            selectPurchaseRequisition(purchaseRequisition) {
                this.selectedPurchaseRequisition = purchaseRequisition;
                this.$emit('selected', purchaseRequisition);
            }
        }
    }
</script>
