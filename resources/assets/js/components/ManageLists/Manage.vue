<template>
    <div class="card">
        <header class="card-header">
            <p class="card-header-title">
                {{ titleName }}
            </p>
            <div class="card-header-icon" aria-label="more options">
                <span class="icon is-primary" v-if="actions.includes('create')" @click="toggleCreateModal()">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                </span>
            </div>
        </header>
        <div class="card-content">
            <!-- Modal -->
            <create-modal v-if="showCreateModal"
                          :resource="resource"
                          @updateDatasets="getData"
                          @closeModal="toggleCreateModal">
            </create-modal>

            <update-modal v-if="showUpdateModal"
                          :recordToUpdate="recordToUpdate"
                          :resource="resource"
                          @updateDatasets="getData"
                          @closeModal="toggleUpdateModal">
            </update-modal>

            <loading-screen size="is-small" v-if="isLoading"></loading-screen>
            <table class="table is-narrow is-fullwidth is-size-7" v-else>
                <thead>
                <tr>
                    <th v-for="column in tableList">
                        {{ column }}
                    </th>
                    <th class="has-text-right" v-if="actions.includes('update')">
                        {{ $t('words.actions') }}
                    </th>
                </tr>
                </thead>
            	<tbody>
            			<tr v-for="(record, index) in dataSets">
            				<td>
                                {{ record.name }}
                            </td>
                            <td v-if="tableList.includes('Active')">
                                <span class="has-text-centered tag"
                                        :class="record.active ? 'is-success' : 'is-danger'">
                                    <template v-if="record.active">
                                        {{ $t('words.yes') }}
                                    </template>
                                    <template v-else>
                                        {{ $t('words.no') }}
                                    </template>
                                </span>
                            </td>
                            <td v-if="actions.length > 0"
                                class="has-text-right">
                                <button v-if="actions.includes('update')"
                                        @click="toggleUpdateModal(record)"
                                        class="button is-small is-primary">
                                    <span class="icon is-small">
                                        <li class="fa fa-pencil"></li>
                                    </span>
                                    <span>{{ $t('words.edit') }}</span>
                                </button>
                            </td>
            			</tr>
            	</tbody>
            </table>
        </div>
    </div>
</template>

<script>
    import CreateModal from './CreateModal';
    import UpdateModal from './UpdateModal';

    export default {
        components: {
            CreateModal,
            UpdateModal,
        },
        props: {
            resource: {
                type: String,
                required: true
            },
            actions: {
                type: Array,
                default() {
                    return ['create', 'update'];
                }
            },
            titleName: {
                type: String,
                required: true
            },
            /**
             * The columns' names that the user sees.
             */
            tableList: {
                type: Array,
                required: true
            },
            /**
             * The values for each record to display.
             */
            displayValues: {
                type: Array,
                required: true
            },
        },
        data() {
            return {
                isLoading: false,

                dataSets: [],

                showCreateModal: false,
                showUpdateModal: false,

                recordToUpdate: null,
            }
        },
        mounted() {
            console.log('[ManageLists/Manage]: Component mounted.');
            this.getData();
        },
        methods: {
            getData() {
                console.log('[ManageLists/Manage]: Getting datasets.');

                this.isLoading = true;

                axios.post(this.apiUrl() + '/component-list/' + this.resource).then(response => {
                    this.dataSets = response.data;

                    this.dataSets.map((record) => {
                        record.loading = false;
                    })

                    this.isLoading = false;
                });
            },
            toggleCreateModal() {
                console.log('[ManageLists/Manage]: Toggled create modal.');
                this.showCreateModal = ! this.showCreateModal;
            },
            toggleUpdateModal(record) {
                console.log('[ManageLists/Manage]: Toggled update modal.');
                this.recordToUpdate = record;
                this.showUpdateModal = ! this.showUpdateModal;
            },
        }
    }
</script>
