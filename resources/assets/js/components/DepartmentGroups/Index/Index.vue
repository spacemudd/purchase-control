<template>
    <div class="columns is-multiline">
        <!-- Create button / modal -->
        <div class="column is-12">
            <button class="button is-primary is-pulled-right"
                    @click="isNewGroupModalActive = true">
                {{ $t('words.new-department-group') }}
            </button>

            <b-modal :active.sync="isNewGroupModalActive" has-modal-card>
                <new-group-modal @createdNewDepartmentGroup="getDepartmentGroups"></new-group-modal>
            </b-modal>
        </div>

        <!-- Department Groups boxes -->
        <div class="column is-12" v-if="isLoading">
            <loading-screen size="is-large"></loading-screen>
        </div>
        <div class="column is-4" v-for="group in departmentGroups" v-else>
            <department-group-card :departmentGroup="group"
                                   :tableList="['Code', 'Description', 'Region']"
            ></department-group-card>
        </div>
    </div>
</template>

<script>
    import DepartmentGroupCard from './Card';
    import NewGroupModal from './NewGroupModal';

    export default {
        components: {
            NewGroupModal,
            DepartmentGroupCard
        },
        data() {
            return {
                departmentGroups: null,
                isLoading: true,
                isNewGroupModalActive: false,
            }
        },
        mounted() {
            this.getDepartmentGroups();
        },
        methods: {
            getDepartmentGroups() {
                this.isNewGroupModalActive = false;
                axios.get(this.apiUrl() + '/department-groups').then(response => {
                    this.departmentGroups = response.data;
                    this.isLoading = false;
                });
            },
        }
    }
</script>

<style lang="sass">
    //
</style>
