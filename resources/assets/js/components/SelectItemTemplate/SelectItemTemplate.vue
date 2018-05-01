<template>
    <span>
        <input type="hidden" v-if="selected" :name="name" :value="selected.id">

        <input v-if="selected"
               type="text"
               class="input"
               :value="selected.code + ' - ' + selected.name"
               @click="selected=search=null"
               readonly>
        <!-- When searching -->
            <b-autocomplete
                    v-else
                    v-model="search"
                    :data="results"
                    :loading="isLoading"
                    field="code"
                    @input="getData"
                    @select="selectRecord">
            <template slot="empty" v-if="!isLoading">No results found</template>
            <template slot-scope="props">
                <a class="dropdown-item">
                    {{ props.option.code }} - {{ props.option.name }}
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
             * api endpoint to search for results.
             */
            url: {
                type: String,
                required: true,
            },
            /**
             * input field name (for http requests).
             */
            name: {
                type: String,
                required: false,
            },
            redirect: {
                type: Boolean,
                required: false,
                default: false,
            },
        },
        data() {
            return {
                isLoading: false,
                selected: null,
                search: null,
                results: [],
            }
        },
        mounted() {

        },
        methods: {
            selectRecord(record) {
                this.selected = record;
                this.$emit('select', record);

                if(this.redirect) {
                    window.location = record.link;
                }
            },
            getData: debounce(function () {
                this.results = []
                this.isLoading = true
                axios.get(this.url + '?q=' + this.search)
                    .then(response => {
                        response.data.data.forEach((item) => this.results.push(item))
                        this.isLoading = false
                    })
                    .catch((error) => {
                        this.isLoading = false
                        throw error
                    })
            }, 500),
        }
    }
</script>
