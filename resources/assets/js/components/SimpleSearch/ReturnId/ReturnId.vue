<template>
    <div>
        <v-select
            :on-search="startSearching"
            :options="results"
            :placeholder="placeholderText"
            v-model="target"
            >
        </v-select>
        <input type="hidden" :name="fieldName" :value="resultId">
    </div>
</template>

<script>
    import vSelect from "vue-select";

    export default {
        components: {
            vSelect
        },
        props: {
            searchEndpoint: {
                type: String,
                required: true,
            },
            fieldName: {
                type: String,
                require: true,
            },
            placeholderText: {
                type: String,
                require: false,
                default: '',
            },
        },
        data() {
            return {
                results: [],
                target: null,
            }
        },
        computed: {
            resultId() {
                if(this.target) {
                    return this.target.value;
                } else {
                    return null;
                }
            },
        },
        mounted() {
            //
        },
        methods: {
            startSearching(search, loading) {
                loading(true);
                this.sendSearchRequest(search, loading)
            },
            sendSearchRequest: _.debounce(function(search, loading) {
                axios.get(this.searchEndpoint, {
                    params: {
                        q: search
                    },
                }).then(response => {
                    this.results = response.data;
                    loading(false);
                })
            }, 500),
        }
    }
</script>

<style lang="sass" scoped>
    .search-input
        min-width: 350px
        .left
            float: left
            min-width: 350px
        .right
            float: right
        .btn
            background-color: #f5f5f6
            height: 36px
            border-top-left-radius: 0
            border-bottom-left-radius: 0
            padding-left: 10px
        .dropdown-toggle, .clearfix
            border-top-right-radius: 0
            border-bottom-right-radius: 0

</style>
