<template>
    <form @submit.prevent="submitForm">
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">Edit Status</p>
            </header>
            <section class="modal-card-body">
                <b-field :label="$t('words.status')">
                    <b-select :loading="isFetching" v-model="selectedStatus" required>
                        <option
                                v-for="option in statusesAvailable"
                                :value="option.id"
                                :key="option.id">
                            {{ option.name }}
                        </option>
                    </b-select>
                </b-field>
            </section>
            <footer class="modal-card-foot">
                <button type="submit"
                        class="button is-success"
                        :class="{'is-loading': $isLoading('UPDATING_ASSET_STATUS')}">
                    {{ $t('words.save') }}
                </button>
                <button class="button" type="button" @click="closeModal">{{ $t('words.cancel') }}</button>
            </footer>
        </div>
    </form>
</template>

<script>
    export default {
        data() {
            return {
                statusesAvailable: [],
                isFetching: true,
                selectedStatus: null,
            }
        },
        mounted() {
            this.getAvailableStatuses();
        },
        methods: {
            getAvailableStatuses() {
                this.isFetching = true;

                axios.get(this.apiUrl() + '/settings/asset-status').then(response => {
                    this.statusesAvailable = response.data;
                    this.isFetching = false;
                }).catch(error => {
                    alert(error.response.data.message);
                })
            },
            closeModal() {
                this.$store.commit('Asset/setChangedStatusModal', false);
            },
            submitForm() {
                if(!this.selectedStatus) {
                    this.$toast.open({
                        type: 'is-danger',
                        message: 'Please select a status',
                    });
                    return false;
                }

                let asset = this.$store.getters['Asset/asset'];
                if(!asset) return alert('There is an error. No asset is in store.');

                this.$startLoading('UPDATING_ASSET_STATUS');
                axios.post(this.apiUrl() + `/assets/${asset.id}/update-status`, {
                    asset_status_id: this.selectedStatus,
                }).then(() => {
                    window.location.reload();
                }).catch(() => {
                    this.$endLoading('UPDATING_ASSET_STATUS');
                })
            }
        },
    }
</script>
