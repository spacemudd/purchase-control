<template>
    <div class="modal is-active" v-if="open" @keyup.esc="closeModal">
        <div class="modal-background" @click="closeModal"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">
                    {{ $t('words.new-item-return') }}
                </p>
                <button class="delete" aria-label="close" @click="closeModal"></button>
            </header>
            <section class="modal-card-body">
                <!--<div v-if="$isLoading('GETTING_ISSUED_FOR_OPTIONS')" key="loading" style="height:450px;">-->
                <!--<loading-screen size="is-small"></loading-screen>-->
                <!--</div>-->
                <div key="form">
                    <div key="form-view" class="columns is-multiline">
                        <div class="column is-12">
                            <div class="field">
                                <label class="label">{{ $t('words.asset') }} <span class="has-text-danger">*</span></label>
                                <div class="control">

                                    <input v-if="newReturnedAsset"
                                           @click="newReturnedAsset = null"
                                           type="text"
                                           class="input"
                                           :value="assetDisplayName"
                                           readonly>
                                    <simple-search
                                            v-else
                                            search-endpoint="assets"
                                            size="is-small"
                                            @selectedRecord="updateNewReturnedAsset"
                                    ></simple-search>
                                </div>
                            </div>
                        </div>

                        <div class="column is-12" v-if="newReturnedAsset">

                            <p class="title is-6">
                                {{ $t('words.selected-item') }}
                            </p>

                            <table class="table is-fullwidth is-narrow">
                                <colgroup>
                                    <col style="width:250px;">
                                </colgroup>
                                <thead>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><strong>{{ $t('words.code') }}</strong></td>
                                    <td>{{ newReturnedAsset.asset_template.code }}</td>
                                </tr>
                                <tr>
                                    <td><strong>{{ $t('words.description') }}</strong></td>
                                    <td>{{ newReturnedAsset.asset_template.description }}</td>
                                </tr>
                                <tr>
                                    <!-- TOOD: Let asset have the manufacturer model be eager loaded -->
                                    <td><strong>{{ $t('words.manufacturer') }}</strong></td>
                                    <td>
                                        <span  v-if="newReturnedAsset.manufacturer">
                                            {{ newReturnedAsset.manufacturer.code }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>{{ $t('words.manufacturer-serial-number') }}</strong></td>
                                    <td>{{ newReturnedAsset.manufacturer_serial_number }}</td>
                                </tr>
                                <tr>
                                    <td><strong>{{ $t('words.system-tag-number') }}</strong></td>
                                    <td>{{ newReturnedAsset.system_tag_number }}</td>
                                </tr>
                                <tr>
                                    <td><strong>{{ $t('words.finance-tag-number') }}</strong></td>
                                    <td>{{ newReturnedAsset.finance_tag_number }}</td>
                                </tr>
                                <tr>
                                    <td><strong>{{ $t('words.condition') }}</strong></td>
                                    <td class="is-capitlized">{{ newReturnedAsset.condition }}</td>
                                </tr>
                                <tr>
                                    <td><strong>{{ $t('words.status') }}</strong></td>
                                    <td class="is-capitlized">{{ newReturnedAsset.status.name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>{{ $t('words.unit-price') }}</strong></td>
                                    <td>{{ newReturnedAsset.unit_price_human }}</td>
                                </tr>
                                <tr>
                                    <td><strong>{{ $t('words.warranty-expiry-date') }}</strong></td>
                                    <td>{{ newReturnedAsset.warranty_expiry_human }}</td>
                                </tr>
                                <tr>
                                    <td><strong>{{ $t('words.warranty-condition') }}</strong></td>
                                    <td>
                                                <span v-if="newReturnedAsset.is_warranty_expired" class="tag is-danger">
                                                    {{ $t('words.expired') }}
                                                </span>
                                        <span class="tag is-success" v-else>
                                                    {{ $t('words.active') }}
                                                </span>
                                    </td>
                                </tr>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </section>
            <footer class="modal-card-foot">
                <button class="button is-success"
                        :class="{'is-loading': $isLoading('ADDING_ASSET_TO_ISSUANCE')}"
                        @click.prevent="submitForm">{{ $t('words.save') }}</button>
                <button class="button" @click="closeModal">{{ $t('words.cancel') }}</button>
            </footer>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
            }
        },
        mounted() {

        },
        computed: {
            open() {
                return this.$store.getters['AssetIssuanceReturn/showNewItemModal'];
            },
            newReturnedAsset() {
                return this.$store.getters['AssetIssuanceReturn/newReturnedAsset'];
            },
            assetDisplayName() {
                let asset = this.$store.getters['AssetIssuanceReturn/newReturnedAsset'];

                return asset.asset_template.code + ' - ' + asset.asset_template.description + ' - ' + 'Serial #: ' + asset.manufacturer_serial_number;
            },
        },
        mounted() {

        },
        methods: {
            closeModal() {
                this.$store.commit('AssetIssuanceReturn/setNewItemModal', false);
            },
            submitForm() {
                this.$store.dispatch('AssetIssuanceReturn/submitNewItem');
            },
            updateNewReturnedAsset(asset) {
                this.$store.commit('AssetIssuanceReturn/setNewReturnedAsset', asset);
            }
        },
    }
</script>

<style lang="sass" scoped>
    .modal-card-body
        min-height: 500px
</style>
