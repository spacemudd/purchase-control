<template>
    <div>
        <div class="columns is-vcentered">
            <div class="column" v-if="isDraft">
                <button class="button is-primary pull-right" @click="showNewIssuanceItemModal">
                    <span class="icon is-small">
                        <i class="fa fa-plus-square"></i>
                    </span>
                    <span>{{ $t('words.new-issued-item') }}</span>
                </button>

                <transition enter-active-class="animated fadeIn" leave-active-class="animated fadeOut">
                    <new-issued-item-modal></new-issued-item-modal>
                </transition>
            </div>
        </div>


        <div class="columns">

            <div class="column is-12">

                <div v-if="$isLoading('DELETING_ASSET_ISSUED_ITEM')">
                    <loading-screen size="is-medium"></loading-screen>
                </div>
                <table class="table is-fullwidth is-size-7" v-else>
                    <thead>
                        <tr>
                            <th>{{ $t('words.asset-code') }}</th>
                            <th>{{ $t('words.description') }}</th>
                            <th>{{ $t('words.manufacturer') }}</th>
                            <th>{{ $t('words.manufacturer-serial-number') }}</th>
                            <th>{{ $t('words.system-tag-number') }}</th>
                            <th>{{ $t('words.finance-tag-number') }}</th>
                            <th>{{ $t('words.unit-price') }}</th>
                            <th>{{ $t('words.issued-for') }}</th>
                            <th class="text-center">{{ $t('words.actions') }}</th>
                        </tr>
                    </thead>
                	<tbody v-if="loadedItems">
                			<tr v-for="(item, index) in loadedItems">
                                <td class="has-text-primary">{{ item.asset.asset_template.code }}</td>
                                <td>{{ item.asset.asset_template.description }}</td>
                                <td>{{ item.asset.manufacturer_name }}</td>
                                <td>{{ item.asset.manufacturer_serial_number }}</td>
                                <td>{{ item.asset.system_tag_number }}</td>
                                <td>{{ item.asset.finance_tag_number }}</td>
                                <td>{{ item.asset.unit_price }}</td>
                                <td>{{ item.issued_for.name }} <span v-if="item.return_at">(Return: {{item.return_at_human}})</span></td>
                                <td>
                                    <button v-if="isDraft"
                                       @click="deleteItem(item.asset_issuance_id, item.id)"
                                       class="button is-small is-danger">
                                        <span class="icon is-small">
                                            <i class="fa fa-ban"></i>
                                        </span>
                                        <span>{{ $t('words.delete') }}</span>
                                    </button>
                                </td>
                			</tr>
                	</tbody>
                </table>

            </div>

        </div>


    </div>
</template>

<script>
    import NewIssuedItemModal from './../NewItemModal/Modal.vue';

    export default {
        components: {
            NewIssuedItemModal,
        },
        computed: {
            loadedItems() {
                return this.$store.getters['AssetIssuance/loadedItems'];
            },
            isDraft() {
                let status = this.$store.getters['AssetIssuance/loadedStatus'];

                return (status === 'draft');
            }
        },
        methods: {
            showNewIssuanceItemModal() {
                this.$store.commit('AssetIssuance/setNewItemModal', true);

                let optionsDontExist = ! (this.$store.getters['AssetIssuance/issuedForOptions']);

                if( optionsDontExist ) {
                    this.$store.dispatch('AssetIssuance/getIssuedForOptions');
                }
            },
            deleteItem(asset_issuance_id, item_id) {

                if(!item_id) {
                    return 'No item to delete.';
                }

                this.startLoading('DELETING_ASSET_ISSUED_ITEM');

                axios.delete(this.apiUrl() + '/asset-issuances/items/delete', {
                    params: {
                        id: item_id,
                    }
                }).then(response => {

                    this.$store.dispatch('AssetIssuance/getFullAssetIssuanceItems', asset_issuance_id);

                    this.endLoading('DELETING_ASSET_ISSUED_ITEM');

                }).catch(response => {

                    alert('Error occurred when deleting an issued item.');

                    this.endLoading('DELETING_ASSET_ISSUED_ITEM');
                })
            }
        }
    }
</script>
