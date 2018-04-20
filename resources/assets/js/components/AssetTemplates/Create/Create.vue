<template>
    <form class="form-horizontal form-groups-bordered">

        <div class="row text-danger" v-if="errorBag">
            <div class="col-md-12">
                <ul>
                    <li v-for="(error, key) in errorBag">
                        <strong>{{ key }}</strong>: {{ error[0] }}
                    </li>
                </ul>
            </div>
        </div>

        <div class="columns">

            <div class="column is-6">
                <div class="form-group"
                     :class="{'validate-has-error is-danger': errors.has('code')}">
                    <label for="code" class="col-sm-3 control-label">{{ $t('words.code') }} *</label>

                    <div class="col-sm-5">

                        <input type="text" class="input" id="code"
                               name="code"
                               v-model="assetCode"
                               v-validate="'required'"
                               data-rules="required"
                               autofocus>

                        <span class="validate-has-error help is-danger" v-if="errors.has('code')">
                            {{ errors.first('code') }}
                        </span>

                    </div>
                </div>
            </div>


            <div class="column is-6">
                <div class="form-group"
                     :class="{'validate-has-error is-danger': errors.has('description')}">
                    <label for="description" class="col-sm-3 control-label">{{ $t('words.description') }} *</label>
                    <div class="col-sm-5">
                        <input type="text" class="input" id="description"
                        name="description"
                        v-model="assetDescription"
                        v-validate="'required'">

                        <span class="validate-has-error help is-danger" v-if="errors.has('description')">
                            {{ errors.first('description') }}
                        </span>

                    </div>
                </div>
            </div>

        </div>

        <hr>

        <div class="columns">

            <div class="column is-6">
                <div class="form-group"
                     :class="{'validate-has-error is-danger': errors.has('manufacturer_id')}">
                    <label class="col-sm-3 control-label">{{ $t('words.manufacturer') }} *</label>
                    <div class="col-sm-5">
                        <div class="select is-fullwidth">
                            <select class="input"
                                    name="manufacturer_id"
                                    v-model="selectedManufacturer"
                                    data-vv-delay="5000"
                                    v-validate="'required'">
                                <option value=""></option>
                                <option v-for="manufacturer in manufacturers"
                                        :value="manufacturer.id">
                                    {{ manufacturer.code }} - {{ manufacturer.description }}
                                </option>
                            </select>
                        </div>

                        <span class="validate-has-error help is-danger" v-if="errors.has('manufacturer_id')">
                            {{ errors.first('manufacturer_id') }}
                        </span>
                    </div>
                </div>
            </div>


            <div class="column is-6">
                <div class="form-group"
                     :class="{'validate-has-error is-danger': errors.has('asset_type_id')}">
                    <label for="asset_type_id" class="col-sm-3 control-label">{{ $t('words.type') }} *</label>
                    <div class="col-sm-5">

                        <div class="select is-fullwidth">
                            <select class="input"
                                    id="asset_type_id"
                                    name="asset_type_id"
                                    data-vv-delay="5000"
                                    v-model="selectedAssetType"
                                    v-validate="'required'">
                                <option value=""></option>
                                <option v-for="type in assetTypes"
                                        :value="type.id">
                                    {{ type.name }}
                                </option>
                            </select>
                        </div>

                        <span class="validate-has-error help is-danger" v-if="errors.has('asset_type_id')">
                            {{ errors.first('asset_type_id') }}
                        </span>

                    </div>
                </div>
            </div>

        </div>


        <hr>


        <div class="columns">

            <div class="column is-6">
                <div class="form-group"
                     :class="{'validate-has-error is-danger': errors.has('category')}">
                    <label for="category" class="col-sm-3 control-label">{{ $t('words.category') }} *</label>
                    <div class="col-sm-5">
                        <div class="select is-fullwidth">
                            <select name="category"
                                    id="category"
                                    class="input"
                                    v-validate="'required'"
                                    data-vv-delay="5000"
                                    v-model="selectedCategory">
                                <option value=""></option>
                                <option v-for="(category, key) in assetCategories"
                                        :value="category">
                                    {{ key }}
                                </option>
                            </select>
                        </div>

                        <span class="validate-has-error help is-danger" v-if="errors.has('category')">
                            {{ errors.first('category') }}
                        </span>
                    </div>
                </div>
            </div>


            <div class="column is-6">
                <div class="form-group"
                     :class="{'validate-has-error is-danger': errors.has('subcategory')}">
                    <label for="subcategory" class="col-sm-3 control-label">{{ $t('words.subcategory') }} *</label>
                    <div class="col-sm-5">
                        <div class="select is-fullwidth">
                            <select class="input"
                                    v-validate="'required'"
                                    v-model="selectedSubcategory"
                                    id="subcategory">
                                <option v-for="subcategory in selectedCategory"
                                :value="subcategory.id">
                                    {{ subcategory.title }}
                                </option>
                            </select>
                        </div>

                        <span class="validate-has-error help is-danger" v-if="errors.has('subcategory')">
                            {{ errors.first('category') }}
                        </span>

                    </div>
                </div>
            </div>

        </div>

        <hr>

        <div class="columns">

            <div class="column is-6">
                <div class="form-group"
                     :class="{'validate-has-error is-danger': errors.has('model_details')}">
                    <label for="model-details" class="col-sm-3 control-label">{{ $t('words.model-details') }} *</label>
                    <div class="col-sm-5">
                        <textarea type="text" class="input" id="model-details" name="model_details" v-model="assetModelDetails" v-validate="'required'">
                        </textarea>

                        <span class="validate-has-error is-danger" v-if="errors.has('model_details')">
                            {{ errors.first('model_details') }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="column is-6">
                <div class="form-group"
                     :class="{'validate-has-error is-danger': errors.has('unit_price')}">
                    <label for="category" class="col-sm-3 control-label">{{ $t('words.unit-price') }} *</label>
                    <div class="col-sm-5">
                        <input type="number" min="0" step="0.01" class="input" v-model.number="assetUnitPrice" name="unit_price" id="unit-price" v-validate="'required|decimal:2'">

                        <span class="validate-has-error help is-danger" v-if="errors.has('unit_price')">
                            {{ errors.first('unit_price') }}
                        </span>

                    </div>
                </div>
            </div>

        </div>

        <hr>

        <div class="columns">
            <div class="column is-6">
                <label for="model-details" class="col-sm-3 control-label">{{ $t('words.active') }}</label>
                <div class="col-sm-5">
                    <input type="checkbox" v-model="assetActive">
                </div>
            </div>
        </div>


        <hr>


        <div class="columns">
            <div class="column is-12">
                <button class="button is-success"
                        :class="{'is-loading': isSendingForm}"
                        :disabled="isSendingForm"
                        @click.prevent="validateAndSend"
                >
                    {{ $t('words.save') }}
                </button>
                <button class="button is-link"
                        :class="{'is-loading': isSendingForm}"
                        :disabled="isSendingForm"
                        @click.prevent="validateAndSend"
                >
                    {{ $t('words.cancel') }}
                </button>
            </div>

        </div>


    </form>
</template>

<script>
    import vSelect from "vue-select";

    export default {
        components: {
            vSelect,
        },
        data() {
            return {

                errorBag: [],

                manufacturers: [],
                assetCategories: [],
                assetTypes: [],

                assetCode: null,
                assetDescription: null,
                selectedManufacturer: null,
                selectedCategory: null,
                selectedSubcategory: null,
                selectedAssetType: null,
                assetActive: true,
                assetUnitPrice: null,
                assetModelDetails: null,

                isSendingForm: false,
            }
        },
        mounted() {
            this.getAssetCategories();
            this.getManufacturers();
            this.getTypes();
        },
        methods: {
            getAssetCategories() {

                axios.get(this.apiUrl() + '/assets/category-list-tree').then(response => {
                    this.assetCategories = response.data;
                });
            },
            getManufacturers() {
                axios.get(this.apiUrl() + '/manufacturers').then(response => {
                    this.manufacturers = response.data;
                });
            },
            getTypes() {
                axios.get(this.apiUrl() + '/assets/types').then(response => {
                    this.assetTypes = response.data;
                });
            },
            validateAndSend() {

                this.errorBag = null;

                let newAsset = {
                    code: this.assetCode,
                    description: this.assetDescription,
                    manufacturer_id: this.selectedManufacturer,
                    type_id: this.selectedAssetType,
                    category_id: this.selectedSubcategory,
                    unit_price: this.assetUnitPrice,
                    details: this.assetModelDetails,
                    active: this.assetActive,
                };

                let readyForSubmit = ! this.$validator.errors.any();

                if(readyForSubmit) {

                    this.isSendingForm = true;
                    axios.post(this.apiUrl() + '/asset-templates/store', newAsset).then(response => {
                        window.location.href= this.baseUrl() + "/";
                        console.log(response.data);
                    }).catch(error => {
                        this.isSendingForm = false;
                        this.errorBag = error.response.data.errors;
                    })

                } else {

                    console.log(newAsset);
                    alert(this.getTrans('words.fill-the-fields'));

                }
            },
        }
    }
</script>

<style lang="sass">
    //
</style>
