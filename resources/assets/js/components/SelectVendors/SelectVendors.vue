<template>
    <span>
        <input type="hidden" v-if="selectedVendor" :name="name" :value="selectedVendor.id">

        <input v-if="selectedVendor"
               type="text"
               class="input"
               :value="selectedVendor.id + ' - ' + selectedVendor.name"
               @click="selectedVendor=search=null"
               readonly>
        <!-- When searching -->
        <b-autocomplete
                    v-else
                    v-model="search"
                    :data="vendors"
                    :loading="isLoading"
                    field="code"
                    @input="getData"
                    @select="selectVendor">
            <template slot="empty" v-if="!isLoading">No results found</template>
            <template slot-scope="props">
                <a class="dropdown-item">
                    {{ props.option.id }} - {{ props.option.name }}
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
             * api endpoint to search for vendors.
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
                selectedVendor: null,
                search: null,
                vendors: [],
            }
        },
        mounted() {

        },
        methods: {
            getData: debounce(function () {
                this.vendors = []
                this.isLoading = true
                axios.get(this.url + '?q=' + this.search)
                    .then(response => {
                        response.data.data.forEach((item) => this.vendors.push(item))
                        this.isLoading = false
                    })
                    .catch((error) => {
                        this.isLoading = false
                        throw error
                    })
            }, 500),
            selectVendor(vendor) {
                this.selectedVendor = vendor;
                this.$emit('selected', vendor);
            }
        }
    }
</script>
