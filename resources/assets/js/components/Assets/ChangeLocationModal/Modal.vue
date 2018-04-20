<template>
    <div class="modal is-active" @keyup.esc="closeModal">
        <div class="modal-background" @click="closeModal"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">
                    Edit Location
                </p>
                <button class="delete" aria-label="close" @click="closeModal"></button>
            </header>
            <section class="modal-card-body">

                <transition enter-active-class="animated fadeIn" leave-active-class="animated fadeOut" mode="out-in">
                    <div v-if="$anyLoading()" key="loading" style="height:250px;">
                        <loading-screen size="is-small"></loading-screen>
                    </div>
                    <div v-if="!$anyLoading()" key="form">

                            <div class="column is-6">

                                <div class="field">
                                    <label class="label">{{ $t('words.locations') }} <span class="has-text-danger">*</span></label>
                                    <div class="control">
                                        <input v-if="location" type="text" class="input" :value="locationDisplayName" readonly
                                               @click="location = null">
                                        <simple-search
                                                v-else
                                                :size="'is-small'"
                                                search-endpoint="locations"
                                                @selectedRecord="updateLocation">
                                        </simple-search>
                                    </div>
                                </div>

                            </div>
                    </div>
                </transition>
            </section>
            <footer class="modal-card-foot">
                <button class="button is-success"
                        :class="{'is-loading': $isLoading('UPDATING_LOCATION')}"
                        @click.prevent="submitForm">{{ $t('words.save') }}</button>
                <button class="button" @click="closeModal">{{ $t('words.cancel') }}</button>
            </footer>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            assetId: {
                type: Number,
                required: true,
            }
        },
        data() {
            return {
                location: null,
            }
        },
        computed: {
            locationDisplayName() {
                return this.location.code;
            }
        },
        methods: {
            closeModal() {
                this.$emit('closeModal');
            },
            updateLocation(payload) {
                this.location = payload;
            },
            submitForm() {

                if(!this.location) {
                    alert('Please choose a location');
                    return false;
                }

                axios.post(this.apiUrl() + '/assets/update-location', {
                    asset_id: this.assetId,
                    location_id: this.location.id,
                }).then(response => {
                    this.closeModal();
                    this.$emit('updateAsset');
                })

            }
        },
    }
</script>

<style lang="sass">
    .control.has-icons-right .input, .control.has-icons-right .select select
        padding-right: 4.25em

        .modal-card-body
            min-height: 310px

    .dropdown-content
        position: fixed;

</style>
