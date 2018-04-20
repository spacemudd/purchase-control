<template>
    <div class="columns is-multiline">

        <div class="column is-12">
            <loading-screen size="is-large" v-if="isLoading"></loading-screen>
            <div class="box" v-else>
                <div class="columns is-multiline">
                    <div class="column is-6">
                        <h1 class="title">{{ employeeCode }}</h1>
                        <p class="subtitle is-6">{{ $t('words.employee-code') }}</p>
                    </div>
                    <!-- Options -->
                    <div class="column is-6 has-text-right">
                        <a :href="employee.edit_link" class="button is-warning is-small has-icon">
                            <span class="icon"><i class="fa fa-pencil"></i></span>
                            <span>{{ $t('words.edit') }}</span>
                        </a>
                    </div>
                    <div class="column is-6" v-if="! isLoading">
                        <table class="table is-size-7 is-fullwidth" v-if="employee">
                            <tbody>
                                <tr>
                                    <td><strong>{{ $t('words.name') }}</strong></td>
                                    <td>{{ employee.name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>{{ $t('auth.email') }}</strong></td>
                                    <td>{{ employee.name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>{{ $t('words.department') }}</strong></td>
                                    <td>{{ employeeDepartmentDisplayName }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="column is-6" v-if="! isLoading">
                        <table class="table is-size-7 is-fullwidth">
                            <tbody>
                                <tr>
                                    <td><strong>{{ $t('words.type') }}</strong></td>
                                    <td v-if="employee.type">{{ employee.type.title }}</td>
                                </tr>
                                <tr>
                                    <td><strong>{{ $t('words.created-at') }}</strong></td>
                                    <td>{{ employee.created_at }}</td>
                                </tr>
                                <tr>
                                    <td><strong>{{ $t('words.updated-at') }}</strong></td>
                                    <td>{{ employee.updated_at }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="column is-12" v-if="!isLoading">
            <b-tabs v-model="activeTab">
                <b-tab-item label="Log">
                    <audit-trail resource-type="employees"
                                 :resource-id.number="employeeId">
                    </audit-trail>
                </b-tab-item>
            </b-tabs>
        </div>
    </div>
</template>

<script>
    import AssetsInCustody from "./AssetsInCustody.vue";

    export default {
        components: {
            AssetsInCustody,
        },
        props: {
            employeeId: {
                required: true,
            }
        },
        data() {
            return {
                activeTab: 0,
                isLoading: true,
                employee: null,
            }
        },
        computed: {
            employeeCode() {
                if(this.employee) {
                    return this.employee.code;
                }
            },
            employeeDepartmentDisplayName() {
                if(this.employee.department) {
                    return this.employee.department.code + ' - ' + this.employee.department.description;
                }
            }
        },
        mounted() {
            this.getEmployee();
        },
        methods: {
            getEmployee() {
                this.isLoading = true;
                axios.post(this.apiUrl() + '/employees/show', {
                    id: this.employeeId
                }).then(response => {
                    this.employee = response.data;
                    this.isLoading = false;
                }).catch(error => {
                    alert('Error occurred getting employee information.');
                    this.isLoading = false;
                })
            },
        }
    }
</script>
