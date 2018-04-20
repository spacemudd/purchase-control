<template>
    <span>
            <b-autocomplete v-model="searchString"
                            :field="field"
                            :message="message"
                            :type="type"
                            :loading="isFetching"
                            @input="getSearchResults"
                            v-bind:required="isRequired"
                            @select="option => selectedItem = option"
                            :data="searchData">
            </b-autocomplete>
        <input v-if="selectedItem" type="hidden" :name="name" :value="selectedItem.id">
    </span>
</template>

<script>
    import debounce from "lodash/debounce";

    export default {
        props: {
            field: {
                type: String,
                required: true,
            },
            name: {
                type: String,
                required: true,
            },
            search: {
                type: String,
                required: true,
            },
            message: {
                type: String,
                required: false,
            },
            type: {
                type: String,
                required: false,
            },
            required: {
                type: Number,
                default: false,
            },
        },
        data() {
            return {
                isFetching: false,
                searchString: '',
                selectedItem: null,
                searchData: [],

                isRequired: false,
                searchUrl: '',
            }
        },
        mounted() {
            this.isRequired = Boolean(this.required === 1);

            if(this.search === 'departments') {
                this.searchUrl = this.apiUrl() + '/search/departments';
            }

            if(this.search === 'employees') {
                this.searchUrl = this.apiUrl() + '/search/employees';
            }

            if(this.search === 'vendors') {
                this.searchUrl = this.apiUrl() + '/search/vendors';
            }
        },
        methods: {
            getSearchResults: debounce(function() {
                this.searchData = [];
                this.isFetching = true;

                axios.get(this.searchUrl + `?q=${this.searchString}`)
                    .then(response => {
                        response.data.data.forEach(item => {
                            this.searchData.push(item);
                        })

                        this.isFetching = false;
                    })
                    .catch(error => {
                        this.isFetching = false;
                        alert(error.response.data.message);
                    })
            }, 500),
        }
    }
</script>

