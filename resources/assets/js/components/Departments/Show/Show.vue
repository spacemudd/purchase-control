<template>
    <div class="columns is-multiline">

        <div class="column is-12" v-if="isLoading">
            <loading-screen size="is-large" v-if="isLoading"></loading-screen>
        </div>
        <div class="column is-12" v-else>
            <div class="box">
                <div class="columns is-multiline" v-if="department">
                    <div class="column is-6">
                        <h1 class="title">{{ department.code }}</h1>
                        <p class="subtitle is-6">{{ $t('words.department') + ' ' + $t('words.code') }}</p>
                    </div>
                    <!-- Options -->
                    <div class="column is-6 has-text-right">
                        <a :href="department.edit_link" class="button is-warning is-small has-icon">
                            <span class="icon"><i class="fa fa-pencil"></i></span>
                            <span>{{ $t('words.edit') }}</span>
                        </a>
                    </div>
                    <div class="column is-6">
                        <table class="table is-size-7 is-fullwidth">
                            <tbody>
                                <tr>
                                    <td><strong>{{ $t('words.created-at') }}</strong></td>
                                    <td>{{ department.created_at }}</td>
                                </tr>
                                <tr>
                                    <td><strong>{{ $t('words.updated-at') }}</strong></td>
                                    <td>{{ department.updated_at }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="column is-6">
                        <table class="table is-size-7 is-fullwidth">
                            <tbody>
                                <tr>
                                    <td><strong>{{ $t('words.region') }}</strong></td>
                                    <td><span v-if="department.region">{{ department.region.title }}</span></td>
                                </tr>
                                <tr>
                                    <td><strong>{{ $t('words.description') }}</strong></td>
                                    <td>{{ department.description }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <b-tabs v-model="activeTab">
                <b-tab-item :label="'Employees (' + department.employees.length + ')'">
                    <!--<div class="section">-->
                        <b-table :data="department.employees"
                                 class="is-size-7"
                                 detail-key="id"
                                 @details-open="(row, index) => $toast.open(`Expanded ${row.code}`)"
                                 detailed>
                            <template slot-scope="props">
                                <b-table-column field="code" label="Code" sortable>
                                    <a :href="props.row.link">{{ props.row.code }}</a>
                                </b-table-column>
                                <b-table-column field="name" label="Name" sortable>
                                    {{ props.row.name }}
                                </b-table-column>
                                <b-table-column field="email" label="Email" sortable>
                                    {{ props.row.email }}
                                </b-table-column>
                                <b-table-column field="assets_in_custody.length" label="Assets in Custody" sortable numeric>
                                    {{ props.row.assets_in_custody.length }}
                                </b-table-column>
                            </template>

                            <template slot="detail" slot-scope="props">
                                <article class="media">
                                    <div class="media-content">
                                        <div class="content">
                                            <b-table class="is-size-7" :data="props.row.assets_in_custody">
                                                <template slot-scope="props">
                                                    <b-table-column field="asset_code" label="Asset Code">
                                                        {{ props.row.asset_template.code }}
                                                    </b-table-column>
                                                    <b-table-column field="description" label="Description">
                                                        {{ props.row.asset_template.description }}
                                                    </b-table-column>
                                                    <b-table-column field="serial_number" label="Serial Number">
                                                        <a :href="props.row.link">{{ props.row.manufacturer_serial_number }}</a>
                                                    </b-table-column>
                                                    <b-table-column field="system_number" label="System Tag Number">
                                                        <a :href="props.row.link">{{ props.row.system_tag_number }}</a>
                                                    </b-table-column>
                                                    <b-table-column field="issuance_number" label="Issuance Number">
                                                        <a :href="props.row.issuance_item.issuance.link">
                                                            {{ props.row.issuance_item.issuance.ref_id }}
                                                        </a>
                                                    </b-table-column>

                                                    <b-table-column field="reference_number" label="Reference Number">
                                                        {{ props.row.issuance_item.issuance.reference_number }}
                                                    </b-table-column>

                                                    <b-table-column field="created_at" label="Created">
                                                        {{ props.row.issuance_item.issuance.issuance_date_human }}
                                                    </b-table-column>
                                                </template>
                                            </b-table>
                                        </div>
                                    </div>
                                </article>
                            </template>
                        </b-table>
                    <!--</div>-->
                </b-tab-item>
            </b-tabs>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            departmentId: {
                required: true,
            }
        },
        data() {
            return {
                isLoading: true,
                department: null,
                activeTab: 0,
                assetsUnderDepartment: [],
            }
        },
        mounted() {
            this.getDepartment();
        },
        methods: {
            getDepartment() {
                this.isLoading = true;
                axios.post(this.apiUrl() + '/departments/show', {
                    id: this.departmentId,
                }).then(response => {
                    this.department = response.data;
                    this.isLoading = false;
                }).catch(error => {
                    alert('Error occurred getting department information.');
                    this.isLoading = false;
                })
            },
        }
    }
</script>
