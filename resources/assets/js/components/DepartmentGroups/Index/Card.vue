<template>
    <div class="card">
        <header class="card-header">
            <p class="card-header-title">
                {{ titleName }}
            </p>
            <div class="card-header-icon" aria-label="more options">
                <span class="icon is-primary" @click="toggleAttachDepartmentModal">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                </span>
            </div>
        </header>
        <div class="card-content">
            <!-- Modal -->
            <attach-modal v-if="showAttachModal"
                          :departmentGroupId="departmentGroup.id"
                          @attachedDepartment="getDepartmentGroupsChildren"
                          @closeModal="toggleAttachDepartmentModal">
            </attach-modal>

            <loading-screen size="is-small" v-if="isLoading"></loading-screen>
            <table class="table is-narrow is-fullwidth is-size-7" v-else>
                <thead>
                <tr>
                    <th v-for="column in tableList">
                        {{ column }}
                    </th>
                    <th class="has-text-right">
                        {{ $t('words.actions') }}
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(record, index) in currentDepartmentGroup.departments">
                    <td>
                        {{ record.code }}
                    </td>
                    <td>{{ record.description }}</td>
                    <td>
                        <template v-if="record.region">
                            {{ record.region.title }}
                        </template>
                    </td>
                    <td v-if="tableList.includes('Active')">
                        <span class="has-text-centered tag"
                              :class="record.active ? 'is-success' : 'is-danger'">
                            <template v-if="record.active">
                                {{ $t('words.yes') }}
                            </template>
                            <template v-else>
                                {{ $t('words.no') }}
                            </template>
                        </span>
                    </td>
                    <td class="has-text-right">
                        <b-tooltip label="Unlink">
                            <button @click="removeAttachedDepartment(record)"
                                    class="button is-small is-primary is-outlined">
                                    <span class="icon is-small">
                                        <li class="fa fa-chain-broken"></li>
                                    </span>
                            </button>
                        </b-tooltip>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    import AttachModal from './AttachDepartmentModal';
    import BTooltip from "../../../../../../node_modules/buefy/src/components/tooltip/Tooltip.vue";

    export default {
        components: {
            BTooltip,
            AttachModal,
        },
        props: {
            departmentGroup: {
                type: Object,
                required: true
            },
            tableList: {
                type: Array,
                required: true
            }
        },
        computed: {
            titleName() {
                return this.departmentGroup.code;
            }
        },
        data() {
            return {
                showAttachModal: false,
                isLoading: false,
                currentDepartmentGroup: [],
            }
        },
        mounted() {
            this.currentDepartmentGroup = this.departmentGroup;
        },
        methods: {
            getDepartmentGroupsChildren() {
                this.isLoading = true;
                this.showAttachModal = false;
                axios.post(this.apiUrl() + '/department-groups/show', {
                    id: this.departmentGroup.id
                }).then(response => {
                    this.isLoading = false;
                    this.currentDepartmentGroup = response.data;
                }).catch(error => {
                    alert('Error occurred in getting departments.');
                    this.isLoading = false;
                })
            },
            toggleAttachDepartmentModal() {
                console.log('[DepartmentGroups/Card]: Show attach department modal.');
                this.showAttachModal = ! this.showAttachModal;
            },
            removeAttachedDepartment(record) {
                this.isLoading = true;
                axios.post(this.apiUrl() + '/department-groups/detach', {
                    department_group_id: this.departmentGroup.id,
                    department_id: record.id
                }).then(response => {
                    this.getDepartmentGroupsChildren();
                }).catch(error => {
                    alert(error.response.data.message);
                    this.isLoading = false;
                })
            }
        }
    }
</script>
