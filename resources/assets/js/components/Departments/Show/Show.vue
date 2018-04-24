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
                <!--
                <b-tab-item :label="'Employees (' + department.employees.length + ')'" v-if="department">
                        <b-table :data="department.employees"
                                 class="is-size-7"
                                 detail-key="id"
                                 detailed>
                            <template slot-scope="props">
                                <b-table-column field="code" label="Code" sortable>
                                    {{ props.row }}
                                </b-table-column>
                            </template>
                        </b-table>
                </b-tab-item>
                -->
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
