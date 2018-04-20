<template>
    <div class="search-input">
        <div class="dropdown is-active" v-click-outside="onSearchBlur">
            <div class="dropdown-trigger">
                <p class="control has-icons-right has-icons-left is-expanded">
                    <input
                            ref="search"
                            id="search"
                            class="input"
                            type="text"
                            :placeholder="placeholderText"
                            v-model="search"
                            @keyup.esc="onEscape"
                            @focus="onSearchFocus"
                    >
                    <span class="icon is-small is-left">
                        <i class="fa fa-search"></i>
                    </span>
                    <span class="icon is-small is-right">
                    <i :class="{'fa fa-circle-o-notch fa-spin fa-fw': loading}"></i>
                </span>
                </p>
            </div>
            <div class="dropdown-menu" id="dropdown-menu" role="menu" v-if="open">
                <div class="dropdown-content">
                    <template v-if="searchEndpoint === 'purchase-orders'">
                        <purchase-orders-results :purchase-orders="results"></purchase-orders-results>
                    </template>

                    <template v-if="searchEndpoint === 'asset-issuances'">
                        <asset-issuances-results :asset-issuances="results"></asset-issuances-results>
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import purchaseOrdersResults from "../PurchaseOrders/SimpleSearchResult/Result.vue";
    import assetIssuancesResults from '../AssetIssuances/SimpleSearchResult/Result.vue';

    export default {
        components: {
            purchaseOrdersResults,
            assetIssuancesResults
        },
        props: {
            searchEndpoint: {
                type: String,
                required: true,
            },
            placeholderText: {
                type: String,
                required: false
            }
        },
        watch: {
            search() {
                if(this.search.length > 0) {
                    this.sendSearchRequest(this.search);
                    this.$emit('search', this.search);
                }
            },
        },
        data() {
            return {
                results: [],
                target: null,
                search: '',
                open: false,
                loading: false,
            }
        },
        mounted() {
            //
        },
        methods: {
            sendSearchRequest: _.debounce(function(search) {
                this.loading = true;
                axios.get(this.apiUrl() + '/search/' + this.searchEndpoint, {
                    params: {
                        q: search
                    },
                }).then(response => {
                    this.results = response.data;
                    this.open = true;
                    this.loading = false;
                })
            }, 500),
            onEscape() {
                if(!this.search.length) {
                    this.$refs.search.blur();
                } else {
                    this.search = '';
                }
                this.loading = false;
            },
            onSearchBlur() {
                this.open = false;
                this.$emit('search:blur');
                this.loading = false;
            },
            onSearchFocus() {
                this.open = true;
                this.$emit('search:focus');
            },
        }
    }
</script>

<style lang="sass" scoped>
    .level
        -webkit-box-pack: justify

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

    .dropdown-content
        overflow: auto
        max-width: 500px
        max-height: 500px
</style>
