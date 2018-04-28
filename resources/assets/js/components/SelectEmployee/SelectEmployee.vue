<template>
    <span>
        <input type="hidden" v-if="selectedEmployee" :name="name" :value="selectedEmployee.id">

        <input v-if="selectedEmployee"
               type="text"
               class="input"
               :value="selectedEmployee.code + ' - ' + selectedEmployee.name"
               @click="selectedEmployee=search=null"
               readonly>
        <!-- When searching -->
            <b-autocomplete
                    v-else
                    v-model="search"
                    :data="employees"
                    :loading="isLoading"
                    field="code"
                    @input="getData"
                    @select="option => selectedEmployee = option">
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
             * api endpoint to search for employees.
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
                selectedEmployee: null,
                search: null,
                employees: [],
            }
        },
        mounted() {

        },
        methods: {
            getData: debounce(function () {
                this.employees = []
                this.isLoading = true
                axios.get(this.url + '?q=' + this.search)
                    .then(response => {
                        response.data.data.forEach((item) => this.employees.push(item))
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
