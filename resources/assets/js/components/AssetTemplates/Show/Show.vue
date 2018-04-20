<template>
    <div class="columns is-multiline">

        <div class="column is-12">
            <loading-screen size="is-large" v-if="isLoading"></loading-screen>
            <div class="box" v-else>
                <div class="columns is-multiline">
                    <div class="column is-12">
                        <h1 class="title">{{ assetTemplateCode }}</h1>
                        <p class="subtitle is-6">{{ $t('words.asset-template-code') }}</p>
                    </div>
                    <div class="column is-6" v-if="! isLoading">
                        <table class="table is-size-7 is-fullwidth">
                            <tbody>
                            <tr>
                                <td><strong>{{ $t('words.active') }}</strong></td>
                                <td>
                            <span class="tag" :class="assetTemplate.active ? 'is-success' : 'is-danger'">
                                <template v-if="assetTemplate.active">
                                    {{ $t('words.yes') }}
                                </template>
                                <template v-else>
                                    {{ $t('words.no') }}
                                </template>
                            </span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>{{ $t('words.description') }}</strong></td>
                                <td>{{ assetTemplate.description }}</td>
                            </tr>
                            <tr>
                                <td><strong>{{ $t('words.manufacturer') }}</strong></td>
                                <td>{{ manufacturerDisplayName }}</td>
                            </tr>
                            <tr>
                                <td><strong>{{ $t('words.type') }}</strong></td>
                                <td>{{ assetTemplate.type.name }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="column is-6" v-if="! isLoading">
                        <table class="table is-size-7 is-fullwidth">
                            <tbody>
                            <tr>
                                <td><strong>{{ $t('words.unit-price') }}</strong></td>
                                <td>{{ assetTemplate.unit_price }}</td>
                            </tr>
                            <tr>
                                <td><strong>{{ $t('words.model-details') }}</strong></td>
                                <td>{{ assetTemplate.model_details }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="column is-12" v-if="!isLoading">
            <b-tabs v-model="activeTab">
                <b-tab-item :label="'In Stock ' + '(' + assetTemplate.in_stock_assets.length + ')'">
                    <div class="section">
                        <table class="table is-fullwidth is-size-7">
                    <thead>
                    <tr>
                        <th>Purchase Order Number</th>
                        <th>Purchase Date</th>
                        <th>Manufacturer Serial Number</th>
                        <th>System Tag Number</th>
                        <th>Finance Tag Number</th>
                        <th>Created at</th>
                        <th>Updated at</th>
                        <th class="has-text-right">Action</th>
                    </tr>
                    </thead>
                    	<tbody>
                    			<tr v-for="asset in assetTemplate.in_stock_assets">
                                    <td>
                                        <a :href="asset.purchase_order.link">{{ asset.purchase_order.order_number }}</a>
                                    </td>
                                    <td>{{ asset.purchase_order.date_human }}</td>
                    				<td>{{ asset.manufacturer_serial_number }}</td>
                                    <td>{{ asset.system_tag_number }}</td>
                                    <td>{{ asset.finance_tag_number }}</td>
                                    <td>{{ asset.created_at }}</td>
                                    <td>{{ asset.updated_at }}</td>
                                    <td class="has-text-right">
                                        <a :href="asset.link" class="button is-small is-primary">
                                            <span class="icon is-small"><i class="fa fa-eye"></i></span>
                                            <span>Go to Asset page</span>
                                        </a>
                                    </td>
                    			</tr>
                    	</tbody>
                    </table>
                    </div>
                </b-tab-item>
                <b-tab-item label="Configurations">
                    <div class="section">
                        <manage-configurations-lists :resource="'asset-templates/' + assetTemplateId + '/configurations'"
                                      title-name=""
                                      :table-list="['Property', 'Value']"
                                      :display-values="['property', 'value']">
                        </manage-configurations-lists>
                    </div>
                </b-tab-item>
            </b-tabs>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            assetTemplateId: {
                required: true,
            }
        },
        data() {
            return {
                activeTab: 0,
                isLoading: true,
                assetTemplate: null,
            }
        },
        computed: {
            assetTemplateCode() {
                if(this.assetTemplate) {
                    return this.assetTemplate.code;
                }
            },
            manufacturerDisplayName() {
                if(this.assetTemplate.manufacturer) {
                    return this.assetTemplate.manufacturer.code + ' - ' + this.assetTemplate.manufacturer.description;
                }
            }
        },
        mounted() {
            this.getAssetTemplateInformation();
        },
        methods: {
            getAssetTemplateInformation() {
                this.isLoading = true;
                axios.post(this.apiUrl() + '/asset-templates/show', {
                    asset_template_id: this.assetTemplateId
                }).then(response => {
                    this.assetTemplate = response.data;
                    this.isLoading = false;
                }).catch(error => {
                    alert('Error occurred getting asset template information.');
                    this.isLoading = false;
                })
            },
        }
    }
</script>
