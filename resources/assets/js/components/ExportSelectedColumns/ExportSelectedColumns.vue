<template>
    <div>
        <button class="button is-primary"
                :class="{'is-loading': isLoading}"
                @click="toggleModal()">
            {{ buttonName }}
        </button>

        <b-modal :active.sync="viewModal">
            <div class="modal-card">
                <header class="modal-card-head">
                    <p class="modal-card-title">{{ $t('words.export') }}</p>
                </header>
                <section class="modal-card-body">
                    <div class="columns">
                        <template v-if="! tableName === 'assets'">
                            <div class="column is-6">
                                <div class="field">
                                    <label class="label">{{ $t('words.date-from') }} <span v-if="!tableName === 'assets'" class="has-text-danger">*</span></label>
                                    <div class="control">
                                        <template>
                                            <b-field>
                                                <b-datepicker
                                                        placeholder="Type or select a date..."
                                                        icon-pack="fa"
                                                        icon="calendar"
                                                        v-model="dateFrom"
                                                        :readonly="false">
                                                </b-datepicker>
                                            </b-field>
                                        </template>
                                    </div>
                                </div>
                            </div>
                            <div class="column is-6">
                                <div class="field">
                                    <label class="label">{{ $t('words.date-to') }} <span v-if="!tableName === 'assets'" class="has-text-danger">*</span></label>
                                    <div class="control">
                                        <template>
                                            <b-field>
                                                <b-datepicker
                                                        placeholder="Type or select a date..."
                                                        icon-pack="fa"
                                                        icon="calendar"
                                                        v-model="dateTo"
                                                        :readonly="false">
                                                </b-datepicker>
                                            </b-field>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                    <div class="columns" v-if="tableName === 'assets'">
                        <div class="column is-6">
                            <div class="field">
                                <label class="label">{{ $t('words.date-from') }} <span class="has-text-danger">*</span></label>
                                <div class="control">
                                    <template>
                                        <b-field>
                                            <b-datepicker
                                                    placeholder="Type or select a date..."
                                                    icon-pack="fa"
                                                    icon="calendar"
                                                    v-model="updatedAtFrom"
                                                    :readonly="false">
                                            </b-datepicker>
                                        </b-field>
                                    </template>

                                    <p class="help">This date represents the asset's updated date.</p>
                                </div>
                            </div>
                        </div>
                        <div class="column is-6">
                            <div class="field">
                                <label class="label">{{ $t('words.date-to') }} <span class="has-text-danger">*</span></label>
                                <div class="control">
                                    <template>
                                        <b-field>
                                            <b-datepicker
                                                    placeholder="Type or select a date..."
                                                    icon-pack="fa"
                                                    icon="calendar"
                                                    v-model="updatedAtTo"
                                                    :readonly="false">
                                            </b-datepicker>
                                        </b-field>
                                    </template>
                                    <p class="help">This date represents the asset's updated date.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="columns">
                        <div class="column is-6">

                            <label class="label">{{ $t('words.selected-columns') }}</label>

                            <div class="field"
                                 v-for="column in availableColumns">
                                <b-checkbox v-model="column.selected">
                                    {{ column.displayName }}
                                </b-checkbox>
                            </div>

                        </div>

                        <div class="column is-6" v-if="askUnitPrice">
                            <div class="field" v-if="tableName === 'assets'">
                                <label for="lowPrice" class="label">Selected Status</label>

                                <div class="control" v-if="assetStatuses">
                                    <div class="select is-fullwidth">
                                        <select
                                                name="selected_status"
                                                v-model="selectedStatusId">
                                            <option value=""></option>
                                            <option v-for="status in assetStatuses"
                                                    :value="status.id">
                                                {{ status.name }} - (Internal code: {{ status.code }})
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <p class="help">The report will include all statuses if no status is selected.</p>
                            </div>

                            <div class="field">
                                <label for="lowPrice" class="label">Unit Price From</label>

                                <p class="control">
                                    <input id="lowPrice" type="number"
                                           class="input"
                                           v-model.number="lowPrice"
                                           name="lowPrice">
                                </p>
                            </div>

                            <div class="field">
                                <label for="highPrice" class="label">Unit Price To</label>
                                <p class="control">
                                    <input id="highPrice" type="number"
                                           class="input"
                                           v-model.number="highPrice"
                                           name="highPrice">
                                </p>
                            </div>
                        </div>
                    </div>
                </section>
                <footer class="modal-card-foot">
                    <button type="button"
                            v-if="!tableName === 'assets'"
                            class="button is-success"
                            :class="{'disabled is-loading': isLoading }"
                            :disable="isLoading"
                            @click="download()">
                        {{ $t('words.export') }}
                    </button>
                    <!-- TODO: Allow for Excel export for all tables. -->
                    <button type="button"
                            v-if="tableName === 'assets'"
                            class="button is-success"
                            :class="{'disabled is-loading': isLoadingExcel }"
                            :disable="isLoadingExcel"
                            @click="initiateAssetsJob()">
                        Build
                    </button>
                    <button type="button"
                            class="button"
                            @click="toggleModal()">{{ $t('words.close') }}
                    </button>
                </footer>
            </div>
        </b-modal>

    </div>
</template>

<script>
    import * as fileUtil from '../../utils/file.js'
    import MaskedInput from 'vue-masked-input'
    import moment from "moment";

    export default {
        components: {
            MaskedInput,
        },
        props: {
            tableName: {
                required: true,
                type: String
            },
            columns: {
                required: false,
                type: Array,
            },
            askUnitPrice: {
                required: false,
                type: Boolean,
                default: true,
            },
            buttonName: {
                required: false,
                type: String,
                default: 'Export',
            }
        },
        data() {
            return {
                isLoading: false,
                viewModal: false,
                availableColumns: [],
                dateFrom: null,
                dateTo: null,
                updatedAtFrom: null,
                updatedAtTo: null,
                lowPrice: null,
                highPrice: null,

                assetStatuses: [],
                selectedStatusId: null,

                isLoadingExcel: false,
            }
        },
        mounted() {
            this.columns.map(column => {
                let newColumn = {displayName: column.displayName, columnName: column.columnName, selected: true};

                this.availableColumns.push(newColumn);
            });

            if(this.tableName === 'assets') {
                this.loadAssetStatuses();
            }
        },
        methods: {
            toggleModal() {
                this.viewModal = ! this.viewModal;
            },
            toggleColumnSelection(column) {
                column.selected = ! column.selected;
            },
            loadAssetStatuses() {
                axios.get(this.apiUrl() + '/asset-status').then(response => {
                    this.assetStatuses = response.data;
                }).catch(error => {
                    alert(error.response.data.message);
                });
            },
            buildRequest() {
                if((!this.updatedAtFrom) || (!this.updatedAtTo)) {
                    return alert('Please fill the from/to (asset status updated) dates.');
                }

                const selectedColumns = [];
                this.availableColumns.map(column => {
                    if(column.selected) {
                        selectedColumns.push(column.columnName);
                    }
                });

                if(this.askUnitPrice) {
                    var userRequest = {
                        columns: selectedColumns,
                        table_name: this.tableName,
                        date_from: this.dateFrom ? moment(this.dateFrom).format('DD/MM/YYYY') : null,
                        date_to: this.dateTo ? moment(this.dateTo).format('DD/MM/YYYY') : null,
                        updated_at_from: moment(this.updatedAtFrom).format('DD/MM/YYYY'),
                        updated_at_to: moment(this.updatedAtTo).format('DD/MM/YYYY'),
                        low_price: this.lowPrice,
                        high_price: this.highPrice,
                    }
                } else {
                    var userRequest = {
                        columns: selectedColumns,
                        table_name: this.tableName,
                        date_from: this.dateFrom ? moment(this.dateFrom).format('DD/MM/YYYY') : null,
                        date_to: this.dateTo ? moment(this.dateTo).format('DD/MM/YYYY') : null,
                        updated_at_from: moment(this.updatedAtFrom).format('DD/MM/YYYY'),
                        updated_at_to: moment(this.updatedAtTo).format('DD/MM/YYYY'),
                    }
                }

                if(this.selectedStatusId) {
                    userRequest.asset_status_id = this.selectedStatusId;
                }

                return userRequest;
            },
            download() {
                let userRequest = this.buildRequest();

                userRequest.type = 'pdf';

                this.isLoading = true;
                axios.post(this.apiUrl() + '/reports/custom', userRequest, {responseType: 'arraybuffer'})
                    .then(response => {
                        this.isLoading = false;

                        const blob = new Blob([response.data], {type: 'application/pdf'});
                        let filename = (response.headers['content-disposition'] || '').split('filename=')[1];
                        filename = filename.replace('"', '');
                        filename = filename.replace('"', '');
                        fileUtil.downloadBlob(blob, filename);
                    }).catch(error => {
                        alert(error.response.data.message);
                        this.isLoading = false;
                })
            },
            initiateAssetsJob() {
                let userRequest = this.buildRequest();

                userRequest.type = 'xls';

                this.isLoadingExcel = true;
                axios.post(this.apiUrl() + '/reports/custom', userRequest)
                    .then(response => {
                        this.isLoadingExcel = false;
                        this.$dialog.alert({
                            title: 'In Progress',
                            message: 'This process might take time. You will receive an <b>inbox message</b> when it is finished.',
                        });
                    }).catch(error => {
                        this.$dialog.alert({
                            type: 'is-danger',
                            hasIcon: true,
                            icon: 'times-circle',
                            iconPack: 'fa',
                            message: 'Error occurred while dispatching your job.',
                        });
                        this.isLoadingExcel = false;
                })
            },
        }
    }
</script>

<style lang="sass" scoped>

    .modal-mask
        position: fixed
        z-index: 9998
        top: 0
        left: 0
        width: 100%
        height: 100%
        background-color: rgba(0, 0, 0, .5)
        display: table
        transition: opacity .3s ease


    .modal-wrapper
        display: table-cell
        vertical-align: middle


    .modal-container
        width: 300px
        margin: 0px auto
        padding: 20px 30px
        background-color: #fff
        border-radius: 2px
        box-shadow: 0 2px 8px rgba(0, 0, 0, .33)
        transition: all .3s ease


    .modal-header h3
        margin-top: 0
        color: #42b983


    .modal-body
        margin: 20px 0


    .modal-default-button
        float: right

    .modal-enter
        opacity: 0


    .modal-leave-active
        opacity: 0

    .modal-enter .modal-container
        .modal-leave-active .modal-container
            -webkit-transform: scale(1.1)
            transform: scale(1.1)

    h2
        margin-left: 12px

    .radio label input, .checkbox label input
        top: 0

    .radio + .radio, .checkbox + .checkbox
        margin-top: 0
</style>
