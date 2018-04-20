<template>
    <div class="search-input">
        <div class="dropdown is-active"
             v-click-outside="onSearchBlur">
            <div class="dropdown-trigger">

                <div class="field is-horizontal">
                    <div v-if="showLabel" class="field-label">
                        <label class="label">{{ $t('words.search') }}</label>
                    </div>
                    <div class="field-body is-normal">
                        <p class="control has-icons-right has-icons-left is-expanded">
                            <input
                                    ref="search"
                                    id="search"
                                    class="input is-fullwidth"
                                    type="text"
                                    v-model="search"
                                    :placeholder="placeholderText"
                                    @keyup.esc="onEscape"
                                    @focus="onSearchFocus"
                                    autocomplete="off"
                            >
                            <span class="icon is-small is-left">
                                <i class="fa fa-search"></i>
                            </span>
                            <span class="icon is-small is-right">
                                <i :class="{'fa fa-circle-o-notch fa-spin fa-fw': loading}"></i>
                            </span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="dropdown-menu" id="dropdown-menu" :class="size" role="menu" v-if="open">
                <div class="dropdown-content">

                    <template v-if="searchEndpoint === 'employees'">
                        <employees-results @recordSelected="recordSelected"
                                             @changePage="changePage"
                                             :hyper-linked="hyperLinkedResults"
                                             :paginated-records="searchResults">
                        </employees-results>
                    </template>

                    <template v-if="searchEndpoint === 'vendors'">
                        <vendors-results @recordSelected="recordSelected"
                                         @changePagePagination="changePage"
                                         :hyper-linked="hyperLinkedResults"
                                         :paginated-records="searchResults">
                        </vendors-results>
                    </template>

                    <template v-if="searchEndpoint === 'departments'">
                        <departments-results @recordSelected="recordSelected"
                                                 @changePage="changePage"
                                                 :hyper-linked="hyperLinkedResults"
                                                 :paginated-records="searchResults">
                        </departments-results>
                    </template>

                    <template v-if="searchEndpoint === 'asset-templates'">
                        <asset-templates-results @recordSelected="recordSelected"
                                             @changePage="changePage"
                                             :hyper-linked="hyperLinkedResults"
                                             :paginated-records="searchResults">
                        </asset-templates-results>
                    </template>

                    <template v-if="(searchEndpoint === 'assets') || (searchEndpoint === 'all-assets')">
                        <assets-results @recordSelected="recordSelected"
                                         @changePage="changePage"
                                         :hyper-linked="hyperLinkedResults"
                                         :paginated-records="searchResults">
                        </assets-results>
                    </template>

                    <template v-if="searchEndpoint === 'manufacturers'">
                        <manufacturers-results @recordSelected="recordSelected"
                                           @changePage="changePage"
                                           :hyper-linked="hyperLinkedResults"
                                           :paginated-records="searchResults">
                        </manufacturers-results>
                    </template>

                    <template v-if="searchEndpoint === 'locations'">
                        <locations-results @recordSelected="recordSelected"
                                             @changePage="changePage"
                                             :hyper-linked="hyperLinkedResults"
                                             :paginated-records="searchResults">
                        </locations-results>
                    </template>

                    <template v-if="searchEndpoint === 'purchase-orders'">
                        <purchase-orders-results @recordSelected="$emit('recordSelected')"
                                               @changePage="changePage"
                                               :hyper-linked="hyperLinkedResults"
                                               :paginated-records="searchResults">
                        </purchase-orders-results>
                    </template>

                    <template v-if="searchEndpoint === 'return-confirmations'">
                        <return-confirmation-paginated-results @recordSelected="$emit('recordSelected')"
                                                               @changePage="changePage"
                                                               :hyper-linked="hyperLinkedResults"
                                                               :paginated-records="searchResults">
                        </return-confirmation-paginated-results>
                    </template>

                    <template v-if="searchEndpoint === 'asset-issuances'">
                        <asset-issuances-results @recordSelected="$emit('recordSelected')"
                                                 @changePage="changePage"
                                                 :hyper-linked="hyperLinkedResults"
                                                 :paginated-records="searchResults">
                        </asset-issuances-results>
                    </template>

                    <template v-if="searchEndpoint === 'asset-issuances-returns'">
                        <asset-issuances-returns-results @recordSelected="$emit('recordSelected')"
                                                 @changePage="changePage"
                                                 :hyper-linked="hyperLinkedResults"
                                                 :paginated-records="searchResults">
                        </asset-issuances-returns-results>
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import VendorsResults from "../../Vendors/DropdownPaginatedSearchResult/Result";
    import DepartmentsResults from "../../Departments/DropdownPaginatedSearchResult/Result.vue";
    import AssetTemplatesResults from "../../AssetTemplates/DropdownPaginatedSearchResult/Result";
    import AssetsResults from "../../Assets/DropdownPaginatedSearchResult/Result";
    import ManufacturersResults from "../../Manufacturers/DropdownPaginatedSearchResult/Result";
    import LocationsResults from "../../Locations/DropdownPaginatedSearchResult/Result.vue";
    import ReturnConfirmationPaginatedResults from "../../ReturnConfirmation/DropdownPaginatedSearchResult/Result";

    export default {
        components: {
            VendorsResults,
            DepartmentsResults,
            AssetTemplatesResults,
            AssetsResults,
            ManufacturersResults,
            LocationsResults,
            ReturnConfirmationPaginatedResults,
        },
        props: {
            searchEndpoint: {
                type: String,
                required: true,
            },
            hyperLinkedResults: {
                type: Boolean,
                default: false,
            },
            placeholderText: {
                type: String,
                default: '',
            },
            showLabel: {
                type: Boolean,
                default: false,
            },
            size: {
                type: String,
                default: 'is-large',
            }
        },
        watch: {
            search() {
                if(this.search.length > 0) {
                    this.sendSearchRequest(this.search);
                    this.$emit('search', this.search);
                    this.open = true;
                }

                if(!this.search) {
                    this.open = false;
                    this.searchResults = [];
                    this.loading = false;
                }
            },
        },
        data() {
            return {
                searchResults: [],
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
                    this.searchResults = response.data;
                    this.open = true;
                    this.loading = false;
                })
            }, 500),
            assetsChangePage(pageNumber) {

                let searchTerm = this.search;

                this.loading = true;

                this.startLoading('SEARCHING_ASSETS');

                axios.get(this.apiUrl() + '/search/' + this.searchEndpoint, {
                    params: {
                        q: searchTerm,
                        page: pageNumber
                    },
                }).then(response => {
                    this.searchResults = response.data;
                    this.open = true;
                    this.loading = false;
                    this.endLoading('SEARCHING_ASSETS');
                })
            },
            changePage(pageNumber) {

                let searchTerm = this.search;

                this.loading = true;

                this.startLoading('SEARCHING');

                axios.get(this.apiUrl() + '/search/' + this.searchEndpoint, {
                    params: {
                        q: searchTerm,
                        page: pageNumber
                    },
                }).then(response => {
                    this.searchResults = response.data;
                    this.loading = false;
                    this.endLoading('SEARCHING');
                })
            },
            onEscape() {
                this.$refs.search.blur();
                this.search = '';
                this.loading = false;
            },
            onSearchBlur() {
                this.open = false;
                this.$emit('search:blur');
                this.search = '';
                this.loading = false;
            },
            onSearchFocus() {
                this.$emit('search:focus');
            },
            emitSelectedEmployee(employee) {
                this.open = false;
                this.$emit('selectedEmployee', employee);
            },
            emitSelectedVendor(vendor) {
                this.open = false;
                this.$emit('selectedVendor', vendor);
            },
            emitSelectedDepartment(department) {
                this.open = false;
                this.$emit('selectedDepartment', department);
            },
            emitSelectedAssetTemplate(payload) {
                this.open = false;
                this.$emit('selectedAssetTemplate', payload);
            },
            emitSelectedAsset(payload) {
                this.open = false;
                this.$emit('selectedAsset', payload);
            },
            emitSelectedManufacturer(payload) {
                this.open = false;
                this.$emit('selectedManufacturer', payload);
            },
            emitSelectedLocation(payload) {
                this.open = false;
                this.$emit('selectedLocation', payload);
            },
            emitSelectedPurchaseOrder(payload) {
                this.open = false;
                this.$emit('selectedPurchaseOrder', payload);
            },
            recordSelected(payload) {
                this.open = false;
                this.$emit('selectedRecord', payload);
            }
        }
    }
</script>

<style lang="sass" scoped>
    .is-large
        min-width: 50rem

    .is-small
        width: 17.4rem
</style>
