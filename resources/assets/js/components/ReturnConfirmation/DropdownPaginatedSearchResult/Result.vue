<template>
        <div class="dropdown-item result" v-if="paginatedRecords.data">
            <div class="information">
                <template v-if="$isLoading('SEARCHING')">
                    <div class="loading-mask">
                        <loading-screen size="is-small"></loading-screen>
                    </div>
                </template>
                <table class="table is-bordered is-hoverable is-striped is-narrow is-fullwidth">
                    <thead>
                        <tr>
                            <th style="width:105px;">{{ $t('words.number') }}</th>
                            <th>{{ $t('words.manufacturer-serial-number') }}</th>
                            <th>{{ $t('words.system-tag-number') }}</th>
                            <th>{{ $t('words.finance-tag-number') }}</th>
                            <th>{{ $t('words.employee') }}</th>
                            <th>{{ $t('words.return-date') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="record in paginatedRecords.data" class="pointer" @click="recordSelected(record)">
                            <td>{{ record.id_human }}</td>
                            <td>{{ record.asset.manufacturer_serial_number }}</td>
                            <td>{{ record.asset.system_tag_number }}</td>
                            <td>{{ record.asset.finance_tag_number }}</td>
                            <td>{{ record.employee.code + ' - ' + record.employee.name }}</td>
                            <td>{{ record.return_date_human }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="pagination">
                <b-pagination
                        :total="results.total"
                        :current.sync="results.current_page"
                        :order="order"
                        size="is-small"
                        :simple="isSimple"
                        :per-page="results.per_page"
                        @change="changePage"
                        >
                </b-pagination>
            </div>
        </div>
</template>

<script>
    export default {
        props: {
            paginatedRecords: {
                required: true,
            },
            hyperLinked: {
                required: false,
                default: false,
            }
        },
        data() {
            return {
                order: '',
                size: '',
                isSimple: true
            }
        },
        computed: {
            results() {
                return this.paginatedRecords;
            }
        },
        mounted() {

        },
        methods: {
            recordSelected(record) {
                if(this.hyperLinked) {
                    window.document.location = this.baseUrl() + '/issuance-returns/' + record.id;
                }
                this.$emit('recordSelected', record);
            },
            changePage(pageNumber) {

                if(this.paginatedRecords) {
                    this.$emit('changePage', pageNumber);
                }

            }
        }
    }
</script>

<style lang="sass">
    .dropdown-item
        border-bottom: none

    a.dropdown-item:hover, .dropdown .dropdown-menu .has-link a:hover
        background-color: unset

    .tag:not(body)
        font-size: 12px

    .dropdown-item
        border-bottom: 1px solid #eeeaea

    a.dropdown-item
        padding-right: 10px

    .loading-mask
        margin: auto
        position: absolute
        top: 0
        bottom: 0
        left: 0
        right: 0
        background-color: rgba(255, 255, 255, 0.8)
        z-index: 1
        cursor: default
        pointer-events: none

    .pointer
        cursor: pointer

</style>
