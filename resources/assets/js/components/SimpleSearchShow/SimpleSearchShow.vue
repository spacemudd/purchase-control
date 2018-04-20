<template>
    <div class="search-input">
        <div class="left">
            <v-select
                    :on-search="startSearching"
                    :options="results"
                    v-model="target"
            >
            </v-select>
        </div>
        <div class="right">
            <a :href="targetLink" class="btn btn-default">Go</a>
        </div>
    </div>
</template>

<script>
    import vSelect from 'vue-select';

    export default {
        components: {
            vSelect
        },
        props: {
            searchEndpointUrl: {
                type: String,
                required: true,
            },
            targetUrl: {
                type: String,
                required: true,
            },
        },
        data() {
            return {
                results: [],
                target: null,
            }
        },
        computed: {
            targetLink() {
                if(this.target) {
                    return this.baseUrl() +  this.targetUrl + '/' + this.target.value;
                } else {
                    return '#';
                }
            }
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
                axios.get(this.searchEndpointUrl, {
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
