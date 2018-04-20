<!--
    @author Shafiq al-Shaar (shafiqshaar@gmail.com)
-->
<template>
    <div class="modal is-active" @keyup.esc="closeModal">
        <div class="modal-background" @click="closeModal"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">
                    {{ $t('words.new-location') }}
                </p>
                <button class="delete" aria-label="close" @click="closeModal"></button>
            </header>
            <section class="modal-card-body">

                <transition enter-active-class="animated fadeIn" leave-active-class="animated fadeOut" mode="out-in">
                    <div v-if="isLoading" key="loading" style="height:350px;">
                        <loading-screen size="is-small"></loading-screen>
                    </div>

                    <div v-if="!isLoading">

                        <template v-if="currentView === 'creating-location'">
                            <div key="form">
                            <div key="form-view" class="columns is-multiline">

                                <div class="column is-12" v-if="errorBag">
                                    <error-bag :error-bag="errorBag"></error-bag>
                                </div>

                                <div class="column is-6">
                                    <div class="field">
                                        <label class="label">{{ $t('words.department') }} <span class="has-text-danger">*</span></label>
                                        <div class="control">
                                            <input v-if="department" type="text" class="input" :value="departmentDisplayName" readonly>
                                            <simple-search
                                                    v-else
                                                    :size="'is-small'"
                                                    search-endpoint="departments"
                                                    @selectedRecord="updateDepartment"
                                            ></simple-search>
                                        </div>
                                    </div>
                                </div>

                                <div class="column is-6">
                                    <div class="field">
                                        <label class="label">{{ $t('words.location-code') }}</label>
                                        <div class="control">
                                            <input name="location-code"
                                                   class="input"
                                                   type="text"
                                                   placeholder="E.g: LOC-DepartmentCode-01"
                                                   v-model="newLocationCode">

                                            <span class="help">{{ $t('messages.location-code-help') }}</span>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>
                        </template>

                        <template v-if="currentView === 'creating-details'">
                            <div class="columns is-multiline">
                                <div class="column is-12">
                                    <p class="title is-6">{{ $t('messages.add-location-details') }}</p>
                                    <div class="box">
                                        <p>Example:</p>
                                        <table class="table is-fullwidth">
                                            <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th class="has-text-right">Value</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td>Floor</td>
                                                <td class="has-text-right">1</td>
                                            </tr>
                                            <tr>
                                                <td>Room</td>
                                                <td class="has-text-right">5</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <hr>
                                </div>

                                <div class="column is-12" v-show="newDetails.length > 0">
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

                            </div>
                        </template>
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
    import ErrorBag from './../../ErrorBag/Bag.vue';

    export default {
        components: {
            ErrorBag,
        },
        data() {
            return {
                isLoading: false,
                department: null,
                newLocationCode: null,
                errorBag: null,
                currentView: 'creating-location',

                newDetailName: null,
                newDetailValue: null,
                newDetails: [],
                newLocationId: null,
            }
        },
        mounted() {
            console.log('Mounted modal');
        },
        computed: {
            departmentDisplayName() {
                if(this.department) {
                    return this.department.code + ' - ' + this.department.description;
                }
            },
        },
        mounted() {

        },
        methods: {
            closeModal() {
                this.department = null;
                this.newLocationCode = null;
                this.$emit('closed-modal');
            },
            updateDepartment(payload) {
                this.department = payload;
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

                if(!this.department) {
                    alert('Please select a department.');
                    return false;
                }
                if(!this.newLocationCode) {
                    alert('Please fill the new location code field.');
                    return false;
                }

                this.isLoading = true;
                this.errorBag = null;

                if(this.currentView === 'creating-location') {
                    let locationCode = {
                        department_id: this.department.id,
                        code: this.newLocationCode
                    };

                    axios.post(this.apiUrl() + '/locations/store', locationCode)
                        .then(response => {
                            this.newLocationId = response.data.id;
                            this.isLoading = false;
                            this.$emit('newLocationCodeCreated');
                            this.currentView = 'creating-details';

                        }).catch(error => {
                            this.isLoading = false;
                            this.errorBag = error.response.data;
                    });
                }

                if(this.currentView === 'creating-details') {
                    let newBulkStore = {
                        location_id: this.newLocationId,
                        details: this.newDetails
                    };
                    axios.post(this.apiUrl() + '/locations/details/bulk-store', newBulkStore).then(response => {
                        this.isLoading = false;
                        this.closeModal();
                        console.log(response);
                    }).catch(error => {
                        this.isLoading = false;
                        this.errorBag = error.response.data;
                    })
                };

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
