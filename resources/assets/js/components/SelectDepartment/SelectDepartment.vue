<template>
    <span>
        <input type="hidden" v-if="selected" :name="name" :value="selected.id">

        <input v-if="selected"
               type="text"
               class="input"
               :value="selected.code + ' - ' + selected.description"
               @click="selected=search=null"
               readonly>
        <!-- When searching -->
            <b-autocomplete
                    v-else
                    v-model="search"
                    :data="records"
                    :loading="isLoading"
                    field="code"
                    @input="getData"
                    @select="selectDepartment">
            <template slot="empty" v-if="!isLoading">No results found</template>
            <template slot-scope="props">
                <a class="dropdown-item">
                    {{ props.option.code }} - {{ props.option.description }}
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
             * api endpoint to search for records.
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
                selected: null,
                search: null,
                records: [],
            }
        },
        mounted() {

        },
        methods: {
          selectDepartment(department) {
            this.selected = department;
            this.$emit('selected', department);
          },
            getData: debounce(function () {
                this.records = []
                this.isLoading = true
                axios.get(this.url + '?q=' + this.search)
                    .then(response => {
                        response.data.data.forEach((item) => this.records.push(item))
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
