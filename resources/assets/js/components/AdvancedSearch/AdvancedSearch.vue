<template>
    <section class="section">
        <div class="columns is-multiline">
            <div class="column is-3">
                <aside class="sidebar menu">
                    <p class="menu-label has-text-weight-bold">{{ $t('words.search') }}</p>
                        <b-field>
                            <b-input type="search"
                                     icon-pack="fa"
                                     v-model="searchTerm"
                                     @keyup.enter.native="sendSearchRequest"
                                     icon="search">
                            </b-input>
                            <p class="control">
                                <button class="button is-primary" @click="sendSearchRequest">{{ $t('words.search') }}</button>
                            </p>
                        </b-field>

                    <hr>

                    <p class="menu-label has-text-weight-bold">{{ $t('words.show-results-for') }}</p>
                    <div class="field">
                        <b-radio v-model="searchEndpoint"
                                 native-value="purchase-orders">
                            {{ $t('words.purchase-orders') }}
                        </b-radio>
                    </div>
                    <div class="field">
                        <b-radio v-model="searchEndpoint"
                                 native-value="asset-issuances">
                            {{ $t('words.issuances') }}
                        </b-radio>
                    </div>
                    <div class="field">
                        <b-radio v-model="searchEndpoint"
                                 native-value="asset-issuances-returns">
                            {{ $t('words.issuance-returns') }}
                        </b-radio>
                    </div>

                    <hr>

                    <p class="menu-label has-text-weight-bold">{{ $t('words.refine-by') }}</p>

                    <b-field :label="$t('words.date-from')">
                        <b-datepicker
                                placeholder="Click to select..."
                                icon-pack="fa"
                                v-model="dateFrom"
                                :readonly="false"
                                icon="calendar">
                        </b-datepicker>
                    </b-field>

                    <b-field :label="$t('words.date-to')">
                        <b-datepicker
                                placeholder="Click to select..."
                                icon-pack="fa"
                                v-model="dateTo"
                                :readonly="false"
                                icon="calendar">
                        </b-datepicker>
                    </b-field>
                </aside>
            </div>

            <div class="column">
                <div class="columns">
                    <div class="column is-12">
                        <template v-if="searchEndpoint === 'employees'">
                            <employees-results @changePage="changePage"
                                               :hyper-linked="true"
                                               :paginated-records="searchResults">
                            </employees-results>
                        </template>

                        <template v-if="searchEndpoint === 'purchase-orders'">
                            <purchase-orders-results @changePage="changePage"
                                                     :hyper-linked="true"
                                                     :paginated-records="searchResults">
                            </purchase-orders-results>
                        </template>

                        <template v-if="searchEndpoint === 'asset-issuances'">
                            <asset-issuances-results @changePage="changePage"
                                                     :hyper-linked="true"
                                                     :paginated-records="searchResults">
                            </asset-issuances-results>
                        </template>

                        <template v-if="searchEndpoint === 'asset-issuances-returns'">
                            <asset-issuances-returns-results @changePage="changePage"
                                                             :hyper-linked="true"
                                                             :paginated-records="searchResults">
                            </asset-issuances-returns-results>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
    import moment from 'moment';

    export default {
        data() {
            return {
                searchEndpoint: 'purchase-orders',
                searchTerm: '',
                previousSearchTerm: null,
                searchResults: [],

                dateFrom: new Date(),
                dateTo: new Date(),
            }
        },
        watch: {
            searchEndpoint() {
                this.searchResults = [];
            }
        },
        mounted() {
            //
        },
        methods: {
            sendSearchRequest() {
                this.startLoading('SEARCHING');

                if(this.dateFrom && this.dateTo) {
                    var params = {
                        q: this.searchTerm,
                        datefrom: moment(this.dateFrom).format('YYYY-MM-DD'),
                        dateto: moment(this.dateTo).format('YYYY-MM-DD'),
                    }
                } else {
                    var params = {
                        q: this.searchTerm,
                    }
                }

                axios.get(this.apiUrl() + '/search/' + this.searchEndpoint, { params: params}).then(response => {
                    this.searchResults = response.data;
                    this.previousSearchTerm = this.searchTerm;
                    this.endLoading('SEARCHING');
                })
            },
            changePage(pageNumber) {

                let searchTerm = this.search;

                this.startLoading('SEARCHING');

                if(this.dateFrom && this.dateTo) {
                    var params = {
                        q: this.searchTerm,
                        datefrom: moment(this.dateFrom).format('YYYY-MM-DD'),
                        dateto: moment(this.dateTo).format('YYYY-MM-DD'),
                        page: pageNumber,
                    }
                } else {
                    var params = {
                        q: this.searchTerm,
                        page: pageNumber,
                    }
                }

                axios.get(this.apiUrl() + '/search/' + this.searchEndpoint, {params: params}).then(response => {
                    this.searchResults = response.data;
                    this.endLoading('SEARCHING');
                })
            },
        }
    }
</script>

<style lang="sass">
    .sidebar
        padding: 10px
        margin-right: 0
        min-height: 45rem
</style>
