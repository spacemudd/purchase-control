<template>
    <div class="columns is-multiline">
        <b-modal :active.sync="showBuildReport" has-modal-card>
            <build-stock-by-item-report></build-stock-by-item-report>
        </b-modal>

        <div class="column is-3">
            <b-field :label="'Stock Code'">
                <b-input v-model="search"></b-input>
            </b-field>
        </div>

        <div class="column is-9 has-text-right">
            <button class="button" @click="showBuildReport=true">Build Report</button>
        </div>

        <div class="column is-12">
            <table class="table is-fullwidth">
            <thead>
            <tr>
            	<th>Code</th>
                <th>Description</th>
                <th>Manufacturer</th>
                <th class="has-text-right">In Stock Count</th>
            </tr>
            </thead>
            	<tbody>
            			<tr v-for="record in result">
                            <td><a :href="record.link">{{ record.code }}</a></td>
                            <td>{{ record.description }}</td>
                            <td>{{ record.manufacturer.code }}</td>
                            <td class="has-text-right">{{ record.assets_count }}</td>
            			</tr>
            	</tbody>
            </table>
        </div>
    </div>
</template>

<script>
    import StockTable from './Table.vue';

    export default {
        components: {
            StockTable,
        },
        data() {
            return {
                fuse: null,
                search: '',
                searchOptions: {
                    shouldSort: true,
                    threshold: 0.6,
                    location: 0,
                    distance: 100,
                    maxPatternLength: 32,
                    minMatchCharLength: 1,
                    keys: [
                        "code",
                        "description",
                        "manufacturer.code",
                    ],
                },
                columns: [
                    {
                        field: 'code',
                        label: 'Code',
                        width: '40',
                        numeric: true
                    },
                    {
                        field: 'description',
                        label: 'Description',
                    },
                    {
                        field: 'manufacturer',
                        label: 'Manufacturer',
                    },
                ],
                list: [],
                result: [],
                showBuildReport: false,
            }
        },
        mounted() {
            this.initiateSearchList();
        },
        methods: {
            initiateSearchList() {
                axios.post(this.apiUrl() + '/asset-templates').then((response) => {
                    this.list = response.data;
                    this.fuse = new window.Fuse(this.list, this.searchOptions);
                    this.result = this.list;
                })
            }
        },
        watch: {
            search() {
                if (this.search.trim() === '')
                    this.result = this.list;
                else
                    this.result = this.fuse.search(this.search.trim());
            }
        },
    }
</script>

<style lang="sass">
    //
</style>
