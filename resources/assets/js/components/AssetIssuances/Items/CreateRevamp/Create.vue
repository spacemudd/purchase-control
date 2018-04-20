<template>
    <div class="section">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">

                    <div class="panel-heading">
                        <div class="panel-title">
                            {{ $t('words.issue-item') }}
                        </div>
                    </div>

                    <div class="panel-body">

                        <transition enter-active-class="animated fadeIn" leave-active-class="animated fadeOut" mode="out-in">
                            <form key="stage1" v-if="stage === 1" class="form-horizontal form-groups-bordered">

                                <div class="form-group">
                                    <label class="col-md-3 control-label">{{ $t('words.begin-by-choosing-asset') }}</label>

                                    <div class="col-md-5">
                                        <v-select
                                                :on-search="searchAssets"
                                                :options="assets"
                                                v-model="assetId"
                                            >
                                        </v-select>
                                        <div class="help-block">
                                            <p>{{ $t('statements.search-asset-help') }}</p>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <transition enter-active-class="animated fadeIn" leave-active-class="animated fadeOut" mode="out-in">
                                            <div class="form-group" v-if="assetId">
                                                <div class="col-sm-offset-3 col-sm-5">
                                                    <button
                                                        type="submit"
                                                        class="btn btn-success btn-lg"
                                                        :class="{'is-loading': isLoading}"
                                                        @click.prevent="insertDetails()"
                                                        >
                                                        {{ $t('words.continue') }}
                                                    </button>
                                                </div>
                                            </div>
                                        </transition>
                                    </div>
                                </div>

                            </form>

                            <div key="stage2" v-if="stage === 2">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p>{{ $t('words.details') }}</p>
                                        <table class="table table-bordered table-hover table-striped">
                                            <tbody>
                                                <tr>
                                                    <td><strong>{{ $t('words.asset-code') }}</strong></td>
                                                    <td>{{ toBeIssuedAsset.asset_template.code }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>{{ $t('words.manufacturer') }}</strong></td>
                                                    <td>{{ toBeIssuedAsset.manufacturer_name }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>{{ $t('words.manufacturer-serial-number') }}</strong></td>
                                                    <td>{{ toBeIssuedAsset.manufacturer_serial_number }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>{{ $t('words.finance-tag-number') }}</strong></td>
                                                    <td>{{ toBeIssuedAsset.finance_tag_number }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>{{ $t('words.system-tag-number') }}</strong></td>
                                                    <td>{{ toBeIssuedAsset.system_tag_number }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>{{ $t('words.unit-price') }}</strong></td>
                                                    <td>{{ toBeIssuedAsset.unit_price }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>{{ $t('words.description') }}</strong></td>
                                                    <td>{{ toBeIssuedAsset.asset_template.description }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>{{ $t('words.details') }}</strong></td>
                                                    <td>{{ toBeIssuedAsset.asset_template.details }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>{{ $t('words.warranty-expiry-date') }}</strong></td>
                                                    <td>{{ toBeIssuedAsset.warranty_expiry_human }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>{{ $t('words.warranty-status') }}</strong></td>
                                                    <td class="text-centered">
                                                        <template v-if="toBeIssuedAsset.is_warranty_expired">
                                                            <span class="tag is-danger">
                                                                {{ $t('words.expired') }}
                                                            </span>
                                                        </template>
                                                        <template v-else>
                                                            <span class="tag is-success">
                                                                {{ $t('words.ok') }}
                                                            </span>
                                                        </template>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-6 text-centered">
                                        <form class="form-horizontal form-groups-bordered">

                                            <div class="row">
                                                <div class="form-group"
                                                     :class="{'validate-has-error has-error': errors.has('issued_for')}">
                                                    <label class="col-md-3 control-label">{{ $t('words.issued-for') }}</label>

                                                    <div class="col-md-5">
                                                        <select class="form-control"
                                                                v-model="issuedForId"
                                                                v-validate="'required'"
                                                                autofocus
                                                                required>
                                                            <option v-for="issuedForOption in issuedForOptions"
                                                                    :value="issuedForOption.id">
                                                                {{ issuedForOption.name }}</option>
                                                        </select>

                                                        <span class="validate-has-error" v-if="errors.has('issued_for')">
                                                            {{ errors.first('issued_for') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="form-group">
                                                    <div class="col-sm-offset-3 col-sm-5">
                                                        <button
                                                                type="submit"
                                                                class="btn btn-success btn-lg"
                                                                :class="{'is-loading': isLoading}"
                                                                @click.prevent="sendAttachRequest()"
                                                        >
                                                            {{ $t('words.confirm') }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        </transition>

                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import vSelect from 'vue-select';

    export default {
        components: {
            vSelect,
        },
        props: {
          assetIssuanceId: {
              type: Number,
              required: true
          }
        },
        data() {
            return {
                isLoading: false,
                issuedForOptions: [],
                assets: [],
                completed: false,
                errorMessage: '',

                issuedForId: null,
                assetId: null,
                room: null,
                floor: null,

                toBeIssuedAsset: null,


                // Steps
                stage: 1,
            }
        },
        mounted() {
            this.getIssuedForList();
            this.getAssets();
        },
        methods: {
            getIssuedForList() {
              axios.get(this.apiUrl() + '/settings/assets/issued-for/options')
                  .then(response => {
                      this.issuedForOptions = response.data;
                  }).catch(error => {
                      alert(this.getTrans('words.error-occurred'));
              })
            },
            searchAssets(search, loading) {
                loading(true)
                this.getAssets(search, loading)
            },
            getAssets: _.debounce(function(search, loading) {
                axios.get(this.apiUrl() + '/search/assets', {
                    params: {
                        q: search
                    },
                }).then(response => {
                        this.assets = response.data;
                        loading(false);
                    })
            }, 500),
            sendAttachRequest() {
                this.isLoading = true;

                axios.post(this.apiUrl() + '/procedures/asset-issuances/' + this.assetIssuanceId + '/items/store', {
                    asset_id: this.assetId.value,
                    issued_for_id: this.issuedForId,
                }).then(response => {
                    this.isLoading = false;
                    this.errorMessage = null;
                    this.completed = true;
                    window.location.href = this.baseUrl() + '/procedures/asset-issuances/' + this.assetIssuanceId;
                }).catch(error => {
                    this.errorMessage = this.getTrans('messages.all-fields-are-required');
                    alert(error.response.data.message);
                    this.isLoading = false;
                })
            },
            clearData() {
                this.assetId = null;
                this.completed = false;
            },
            insertDetails() {
                this.isLoading = true;

                axios.get(this.apiUrl() + '/assets/' + this.assetId.value).then(response => {
                    this.toBeIssuedAsset = response.data;
                    this.stage = 2;
                    this.isLoading = false;
                });
            },
        }
    }
</script>

<style lang="sass" scoped>
    .panel .panel-body
        overflow-x: unset

    .fancy-transitions
        transition: all 200ms ease-in-out

    .help-block
        color: #9e9e9e

</style>
