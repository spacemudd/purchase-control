<template>
    <div class="section">
        <div class="panel panel-primary">

            <div class="panel-heading">
                <div class="panel-title">
                    {{ $t('words.issue-item') }}
                </div>
            </div>

            <div class="panel-body">

                <div v-if="errorMessage"
                    class="alert-danger">
                    <p>{{ this.errorMessage }}</p>
                </div>

                <form v-if="!completed" class="form-horizontal form-groups-bordered">
                    <div class="form-group">
                        <label class="col-md-3 control-label">{{ $t('words.asset') }}</label>

                        <div class="col-md-5">
                            <v-select
                                    :on-search="searchAssets"
                                    :options="assets"
                                    :placeholder="$t('messages.search-by-description-or-manufacturer-serial-number')"
                                    v-model="assetId"
                            >
                            </v-select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label">{{ $t('words.target-location-floor') }}</label>

                        <div class="col-md-5">
                            <input class="form-control"
                                   title="floor"
                                   v-model.number="floor">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label">{{ $t('words.target-location-room') }}</label>

                        <div class="col-md-5">
                            <input class="form-control"
                                   title="room"
                                   v-model.number="room">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label">{{ $t('words.issued-for') }}</label>

                        <div class="col-md-5">
                                <select class="form-control"
                                v-model="issuedForId"
                                required>
                                    <option v-for="issuedForOption in issuedForOptions"
                                            :value="issuedForOption.id">
                                        {{ issuedForOption.name }}</option>
                                </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-5">
                            <button
                            type="submit"
                            class="btn btn-success btn-lg"
                            :class="{'is-loading': isLoading}"
                            @click.prevent="sendForm()"
                            >
                                {{ $t('words.create') }}
                            </button>
                        </div>
                    </div>

                </form>

                <a v-if="completed" href="#" class="btn btn-info text-center" @click="clearData()">Attach new item?</a>

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
            sendForm() {
                this.isLoading = true;
                if(!this.assetId) {
                    this.errorMessage = this.getTrans('messages.all-fields-are-required');
                    this.loading = false;
                    return false;
                }
                axios.post(this.apiUrl() + '/procedures/asset-issuances/' + this.assetIssuanceId + '/items/store', {
                    asset_id: this.assetId.value,
                    issued_for_id: this.issuedForId,
                    room: this.room,
                    floor: this.floor,
                }).then(response => {
                    this.isLoading = false;
                    this.errorMessage = null;
                    this.completed = true;
                }).catch(error => {
                    this.errorMessage = this.getTrans('messages.all-fields-are-required');
                    this.isLoading = false;
                })
            },
            clearData() {
                this.assetId = null;
                this.completed = false;
            }
        }
    }
</script>

<style lang="sass">
    //
</style>
