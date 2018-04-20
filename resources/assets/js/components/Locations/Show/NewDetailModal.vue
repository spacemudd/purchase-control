<!--
    @author Shafiq al-Shaar (shafiqshaar@gmail.com)
-->
<template>
    <div class="modal is-active" @keyup.esc="closeModal">
        <div class="modal-background" @click="closeModal"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">
                    {{ $t('words.new-detail') }}
                </p>
                <button class="delete" aria-label="close" @click="closeModal"></button>
            </header>
            <section class="modal-card-body">

                <transition enter-active-class="animated fadeIn" leave-active-class="animated fadeOut" mode="out-in">
                    <div v-if="isLoading" key="loading" style="height:350px;">
                        <loading-screen size="is-small"></loading-screen>
                    </div>

                    <div v-if="!isLoading">
                            <div class="columns is-multiline">

                                <div class="column is-6">
                                    <div class="field">
                                        <label class="label">{{ $t('words.name') }}</label>
                                        <div class="control">
                                            <input name="detail-name"
                                                   class="input"
                                                   type="text"
                                                   v-model="newDetailName">
                                        </div>
                                    </div>
                                </div>

                                <div class="column is-6">
                                    <label class="label">{{ $t('words.value') }}</label>
                                    <div class="field has-addons">
                                        <div class="control">
                                            <input name="detail-value"
                                                   class="input is-fullwidth"
                                                   type="text"
                                                   @keyup.enter="addToDetails()"
                                                   v-model="newDetailValue">
                                        </div>
                                        <div class="control" @keyup.enter="addToDetails()">
                                            <a class="button is-primary" @keyup.enter="addToDetails()" @click="addToDetails()">
                                                {{ $t('words.new') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <div class="column is-12" v-if="newDetails.length > 0">
                                    <hr>

                                    <table class="table is-narrow is-fullwidth">
                                        <thead>
                                        <tr>
                                            <th>{{ $t('words.name') }}</th>
                                            <th>{{ $t('words.value') }}</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(detail, index) in newDetails">
                                            <td>{{ detail.name }}</td>
                                            <td>{{ detail.value }}</td>
                                            <td class="has-text-centered">
                                                <a tabindex="1" class="button is-small is-danger is-outlined" @click="removeDetail(index)">
                                                    <span>{{ $t('words.delete') }}</span>
                                                    <span class="icon is-small">
                                                              <i class="fa fa-times"></i>
                                                            </span>
                                                </a>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>

                                </div>

                            </div>
                    </div>
                </transition>
            </section>
            <footer class="modal-card-foot">
                <button class="button is-success"
                        :class="{'is-loading': isLoading}"
                        @click.prevent="submitForm">{{ $t('words.save') }}</button>
                <button class="button" @click="closeModal">{{ $t('words.cancel') }}</button>
            </footer>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            locationId: {
                required: true,
            }
        },
        data() {
            return {
                isLoading: false,

                newDetailName: null,
                newDetailValue: null,
                newDetails: [],
            }
        },
        methods: {
            closeModal() {
                this.newDetailName = null;
                this.newDetailValue = null;
                this.newDetails = [];
                this.$emit('closedModal');
            },
            addToDetails() {
                if(!this.newDetailName) {
                    alert('Please fill the detail name');
                    return false;
                }
                if(!this.newDetailValue) {
                    alert('Please fill the detail value');
                    return false;
                }

                let newDetail = {
                    name: this.newDetailName,
                    value: this.newDetailValue,
                };

                this.newDetails.push(newDetail);

                this.newDetailName = null;
                this.newDetailValue = null;
            },
            removeDetail(index) {
                this.newDetails.splice(index, 1);
            },
            submitForm() {

                if(!this.locationId) {
                    alert('Missing location id.');
                    return false;
                }

                this.isLoading = true;
                this.errorBag = null;

                let newBulkStore = {
                    location_id: this.locationId,
                    details: this.newDetails
                };
                axios.post(this.apiUrl() + '/locations/details/bulk-store', newBulkStore).then(response => {
                    this.isLoading = false;
                    this.closeModal();
                    this.$emit('updateDetails');
                }).catch(error => {
                    this.isLoading = false;
                })

            }
        },
    }
</script>

<style lang="sass">
    .control.has-icons-right .input, .control.has-icons-right .select select
        padding-right: 4.25em

        .modal-card-body
            min-height: 350px
</style>
