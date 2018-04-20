<template>
    <div>
        <loading-screen v-if="isLoading" size="is-small"></loading-screen>
        <table class="table is-fullwidth is-narrow is-size-7 has-text-small" v-else>
            <thead>
            <tr>
                <th>{{ $t('words.number') }}</th>
                <th>Note</th>
                <th>Created At</th>
                <th>Status</th>
                <th class="has-text-centered">Completed</th>
                <th class="has-text-centered">{{ $t('words.actions') }}</th>
            </tr>
            </thead>
        	<tbody>
                    <!-- Modals -->
                    <resolve-modal v-if="showResolveModal"
                                   @closeModal="toggleResolveModal(null)"
                                   @successCreated="getWorkOrders"
                                   :workOrder="order"></resolve-modal>

        			<tr v-for="order in workOrders">
        				<td>{{ order.id }}</td>
                        <td>{{ order.note }}</td>
                        <td>{{ order.created_at }}</td>
                        <td>{{ order.status }}</td>
                        <td class="has-text-centered">
                            <span class="tag is-success" v-if="order.completed">
                                {{ $t('words.yes') }}
                            </span>
                            <span class="tag is-warning" v-else>
                                {{ $t('words.no') }}
                            </span>
                        </td>

                        <td class="has-text-centered">
                            <button v-if="!order.completed" class="button is-small is-primary" @click="toggleResolveModal(order)">
                                <span class="icon is-small">
                                    <i class="fa fa-bolt"></i>
                                </span>
                                <span>Resolve</span>
                            </button>
                        </td>
        			</tr>
        	</tbody>
        </table>
    </div>
</template>

<script>
    import ResolveModal from './ResolveModal.vue';

    export default {
        components: {
            ResolveModal,
        },
        props: {
            assetId: {
                required: true,
            }
        },
        data() {
            return {
                workOrders: [],
                isLoading: true,
                showResolveModal: false,
                order: null,
            }
        },
        mounted() {
            this.getWorkOrders();
        },
        methods: {
            getWorkOrders() {
                axios.post(this.apiUrl() + '/assets/work-requests', {
                    asset_id: this.assetId,
                }).then(response => {
                    this.workOrders = response.data;
                    this.isLoading = false;
                })
            },
            /**
             * Set the order prop for the modal.
             *
             * @param order
             */
            toggleResolveModal(order) {
                this.order = order;
                this.showResolveModal = ! this.showResolveModal;
            }
        }
    }
</script>

<style lang="sass">
    //
</style>
