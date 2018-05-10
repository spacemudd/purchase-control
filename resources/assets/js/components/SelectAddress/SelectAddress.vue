<template>
    <span>
        <input type="hidden" v-if="selectedAddress" :name="name" :value="selectedAddress.id">

        <input v-if="selectedAddress"
               type="text"
               class="input"
               :value="selectedAddress.location"
               @click="selectedAddress=search=null"
               readonly>
        <!-- When searching -->
        <b-autocomplete
                    v-else
                    v-model="search"
                    :data="addresses"
                    :loading="isLoading"
                    field="code"
                    @input="getData"
                    @select="selectAddress">
            <template slot="empty" v-if="!isLoading">No results found</template>
            <template slot-scope="props">
                <a class="dropdown-item">
                    {{ props.option.location }}
                    <span v-if="props.option.department">{{ props.option.department }}<br/></span>
                    <span v-if="props.option.contact_name">{{ props.option.contact_name }}<br/></span>
                    <span v-if="props.option.phone">{{ props.option.phone }}<br/></span>
                    <span v-if="props.option.email">{{ props.option.email }}<br/></span>
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
             * api endpoint to search for addresses.
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
                selectedAddress: null,
                search: null,
                addresses: [],
            }
        },
        mounted() {

        },
        methods: {
            getData: debounce(function () {
                this.addresses = []
                this.isLoading = true
                axios.get(this.url + '?q=' + this.search)
                    .then(response => {
                        response.data.data.forEach((item) => this.addresses.push(item))
                        this.isLoading = false
                    })
                    .catch((error) => {
                        this.isLoading = false
                        throw error
                    })
            }, 500),
            selectAddress(address) {
                this.selectedAddress = address;
                this.$emit('selected', address);
            }
        }
    }
</script>
