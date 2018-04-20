<template>
    <div id="assets-in-custody" v-if="!isLoading">
        <table class="table is-size-7 is-fullwidth">
        <thead>
        <tr>
        	<th>{{ $t('words.asset-code') }}</th>
            <th>{{ $t('words.description') }}</th>
            <th>{{ $t('words.manufacturer-serial-number') }}</th>
            <th>{{ $t('words.system-tag-number') }}</th>
            <th>{{ $t('words.issuance-number') }}</th>
            <th>{{ $t('words.issuance-date') }}</th>
            <th>{{ $t('words.employee-code') }}</th>
        </tr>
        </thead>
        	<tbody>
        			<tr v-for="asset in assets">
                        <td><a :href="asset.asset_template.link">{{ asset.asset_template.code }}</a></td>
                        <td>{{ asset.asset_template.description }}</td>
                        <td><a :href="asset.link">{{ asset.manufacturer_serial_number }}</a></td>
                        <td>{{ asset.system_tag_number }}</td>
                        <td>
                            <a :href="baseUrl() + '/procedures/asset-issuances/' + asset.issuance_item.asset_issuance_id">
                                {{ asset.issuance_item.issuance.ref_id }}
                            </a>
                        </td>
                        <td>{{ asset.issuance_item.issuance.issuance_date_human }}</td>
                        <td>
                            <b-tooltip v-if="employee" :label="employee.name">{{ employee.code }}</b-tooltip>
                        </td>
        			</tr>
        	</tbody>
        </table>
    </div>
</template>

<script>
    export default {
        props: {
            employeeId: {
                required: true,
                type: Number
            }
        },
        data() {
            return {
                isLoading: true,
                assets: [],
                employee: null,
            }
        },
        mounted() {
            this.getAssetsInCustody(this.employeeId);
        },
        methods: {
            getAssetsInCustody(employeeId) {
                this.isLoading = true;

                axios.post(this.apiUrl() + '/employees/show', {
                    id: this.employeeId,
                }).then(response => {
                    this.employee = response.data;
                    this.assets = response.data.assets_in_custody;
                    this.isLoading = false;
                }).catch(error => {
                    alert(error.response.data.message);
                    this.isLoading = false;
                })
            }
        }
    }
</script>

<style lang="sass">
    .loading-mask
        margin: auto
        position: absolute
        top: 0
        bottom: 0
        left: 0
        right: 0
        background-color: rgba(255, 255, 255, 0.8)
        z-index: 1
        cursor: default
        pointer-events: none
</style>
