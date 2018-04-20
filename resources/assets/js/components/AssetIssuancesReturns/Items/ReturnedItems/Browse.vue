<template>
    <div>
        <div class="columns is-vcentered">
            <div class="column" v-if="isDraft">
                <button class="button is-primary pull-right" @click="showNewIssuanceItemModal">
                    <span class="icon is-small">
                        <i class="fa fa-plus-square"></i>
                    </span>
                    <span>{{ $t('words.new-item-return') }}</span>
                </button>

                <new-issued-item-modal></new-issued-item-modal>

                <!--
                <b-field grouped position="is-right">
                    <simple-search
                            :placeholder-text="'Add items by Serial Number'"
                            search-endpoint="assets"
                            size="is-small"
                            @selectedRecord="addAssetToReturnList">
                    </simple-search>
                </b-field>
                -->
            </div>
        </div>


        <div class="columns">

            <div class="column is-12">

                <div v-if="$isLoading('LOADING_ASSET_RETURN_ITEMS')">
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
                                <td>
                                    <button v-if="isDraft"
                                            @click="deleteItem(item.asset_issuance_return_id, item.id)"
                                            :class="{'is-loading': $isLoading('DELETING_ASSET_RETURNED_ITEM')}"
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
                return this.$store.getters['AssetIssuanceReturn/loadedItems'];
            },
            isDraft() {
                let status = this.$store.getters['AssetIssuanceReturn/loadedStatus'];

                return (status === 'draft');
            }
        },
        methods: {
            showNewIssuanceItemModal() {
                this.$store.commit('AssetIssuanceReturn/setNewItemModal', true);
            },
            deleteItem(asset_issuance_return_id, item_id) {

                if(!item_id) {
                    return 'No item to delete.';
                }

                this.startLoading('DELETING_ASSET_RETURNED_ITEM');

                axios.delete(this.apiUrl() + '/asset-issuances-returns/items/delete', {
                    params: {
                        id: item_id,
                    }
                }).then(response => {

                    this.$store.dispatch('AssetIssuanceReturn/getFullAssetIssuanceReturnItems', asset_issuance_return_id);

                    this.endLoading('DELETING_ASSET_RETURNED_ITEM');

                }).catch(error => {
                    alert(error.response.data.message);
                    this.endLoading('DELETING_ASSET_RETURNED_ITEM');
                })
            },
//            addAssetToReturnList(asset) {
//                this.$store.commit('AssetIssuanceReturn/setNewReturnedAsset', asset);
//                this.$store.dispatch('AssetIssuanceReturn/submitNewItem');
//                this.$store.commit('AssetIssuanceReturn/setNewReturnedAsset', null);
//            },
        }
    }
</script>
