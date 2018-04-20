<template>
    <div class="modal is-active" v-if="open" @keyup.esc="closeModal">
        <div class="modal-background" @click="closeModal"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">
                    {{ $t('words.new-issued-item') }}
                </p>
                <button class="delete" aria-label="close" @click="closeModal"></button>
            </header>
            <section class="modal-card-body">

                <transition enter-active-class="animated fadeIn" leave-active-class="animated fadeOut" mode="out-in">
                    <div v-if="$isLoading('GETTING_ISSUED_FOR_OPTIONS')" key="loading" style="height:450px;">
                        <loading-screen size="is-small"></loading-screen>
                    </div>
                    <div v-else key="form">
                        <div key="form-view" class="columns is-multiline">
                            <div class="column is-12">
                                <div class="field">
                                    <label class="label">{{ $t('words.asset') }} <span class="has-text-danger">*</span></label>
                                    <div class="control">

                                        <input v-if="newIssuedAsset" type="text" class="input" :value="assetDisplayName" @click="newIssuedAsset = null" readonly>
                                        <simple-search
                                                v-else
                                                search-endpoint="assets"
                                                size="is-small"
                                                @selectedRecord="updateNewIssuedAsset"
                                        ></simple-search>
                                    </div>
                                </div>
                            </div>

                            <div class="column is-12">
                                <div class="field">
                                    <label class="label">{{ $t('words.issued-for') }} <span class="has-text-danger">*</span></label>
                                    <div class="control">
                                        <div class="select is-fullwidth">
                                            <select v-model="issuedFor" class="form-control">
                                                <option v-for="option in issuedForOptions"
                                                        :value="option.id">
                                                    {{ option.name }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="column is-12" v-if="showReturnAtDate">
                                <div class="notification is-warning">
                                    <b-field label="Return at">
                                        <b-datepicker
                                                placeholder="Type or select a date..."
                                                icon-pack="fa"
                                                icon="calendar"
                                                v-model="returnAt"
                                                :readonly="false">
                                        </b-datepicker>
                                    </b-field>
                                </div>
                            </div>

                            <div class="column is-12" v-if="newIssuedAsset">

                                <p class="title is-6">
                                    {{ $t('words.selected-item') }}
                                </p>

                                <table class="table is-fullwidth is-narrow">
                                <thead>
                                </thead>
                                    <tbody>
                                        <tr>
                                            <td><strong>{{ $t('words.code') }}</strong></td>
                                            <td>{{ newIssuedAsset.asset_template.code }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ $t('words.description') }}</strong></td>
                                            <td>{{ newIssuedAsset.asset_template.description }}</td>
                                        </tr>
                                        <tr>
                                            <!-- TOOD: Let asset have the manufacturer model be eager loaded -->
                                            <td><strong>{{ $t('words.manufacturer') }}</strong></td>
                                            <td>{{ newIssuedAsset.manufacturer_name }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ $t('words.manufacturer-serial-number') }}</strong></td>
                                            <td>{{ newIssuedAsset.manufacturer_serial_number }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ $t('words.system-tag-number') }}</strong></td>
                                            <td>{{ newIssuedAsset.system_tag_number }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ $t('words.finance-tag-number') }}</strong></td>
                                            <td>{{ newIssuedAsset.finance_tag_number }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ $t('words.condition') }}</strong></td>
                                            <td class="is-capitlized">{{ newIssuedAsset.condition }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ $t('words.status') }}</strong></td>
                                            <td class="is-capitlized">{{ newIssuedAsset.status.name }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ $t('words.unit-price') }}</strong></td>
                                            <td>{{ newIssuedAsset.unit_price_human }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ $t('words.warranty-expiry-date') }}</strong></td>
                                            <td>{{ newIssuedAsset.warranty_expiry_human }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ $t('words.warranty-condition') }}</strong></td>
                                            <td>
                                                <span v-if="newIssuedAsset.is_warranty_expired" class="tag is-danger">
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
                </transition>
            </section>
            <transition leave-active-class="animated fadeOut">
                <footer class="modal-card-foot">
                    <button class="button is-success"
                            :class="{'is-loading': $isLoading('ADDING_ASSET_TO_ISSUANCE')}"
                            @click.prevent="submitForm">{{ $t('words.save') }}</button>
                    <button class="button" @click="closeModal">{{ $t('words.cancel') }}</button>
                </footer>
            </transition>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                returnAt: new Date(),
                showReturnAtDate: false,
            }
        },
        mounted() {
            let issuedForCookie = this.$cookie.get('issued-for');
            if(issuedForCookie==='3') {
                this.showReturnAtDate = true;
            }
        },
        watch: {
            issuedFor() {
                if(this.issuedFor==='3' || this.issuedFor===3) {
                    this.showReturnAtDate = true;
                } else {
                    this.showReturnAtDate = false;
                }
            },
            issuedForOptions() {
                /**
                 * Preselect the 'issued for' for the user.
                 * When the user submits the form, a cookie is saved.
                 */
                let issuedForCookie = this.$cookie.get('issued-for');
                if(issuedForCookie) {
                    this.issuedFor = issuedForCookie;
                }
            },
            open() {
                let issuedForCookie = this.$cookie.get('issued-for');
                if(issuedForCookie) {
                    this.issuedFor = issuedForCookie;
                }
            },
        },
        computed: {
            open() {
                return this.$store.getters['AssetIssuance/showNewItemModal'];
            },
            newIssuedAsset: {
                get() {
                    return this.$store.getters['AssetIssuance/newIssuedAsset'];
                },
                set(value) {
                    this.$store.commit('AssetIssuance/setNewIssuedAsset', value);
                }
            },
            assetDisplayName() {
                let asset = this.$store.getters['AssetIssuance/newIssuedAsset'];

                return asset.asset_template.code + ' - ' + asset.asset_template.description + ' - ' + 'Serial #: ' + asset.manufacturer_serial_number;
            },
            issuedForOptions() {
                return this.$store.getters['AssetIssuance/issuedForOptions'];
            },
            issuedFor: {
                get() {
                    return this.$store.getters['AssetIssuance/newIssuedFor'];
                },
                set(value) {
                    return this.$store.commit('AssetIssuance/setNewIssuedFor', value);
                }
            },
        },
        methods: {
            closeModal() {
                this.$store.commit('AssetIssuance/setNewItemModal', false);
            },
            submitForm() {
                this.$cookie.set('issued-for', this.issuedFor);

                // TODO: Clean up this area.
                if(this.issuedFor===3 || this.issuedFor==='3') {
                    this.$store.dispatch('AssetIssuance/submitNewItem', this.returnAt);
                } else {
                    this.$store.dispatch('AssetIssuance/submitNewItem');
                }
            },
            updateNewIssuedAsset(asset) {
                this.newIssuedAsset = asset;
            }
        },
    }
</script>

<style lang="sass" scoped>
    .control.has-icons-right .input, .control.has-icons-right .select select
        padding-right: 4.25em

    .modal-card-body
        min-height: 500px
</style>
