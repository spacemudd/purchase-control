<template>

    <div class="modal is-active">
        <div class="modal-background" @click="close()"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">
                    Attach New Department
                </p>
                <button class="delete" aria-label="close" @click="close()"></button>
            </header>
            <section class="modal-card-body">
                <div class="columns">
                    <div class="column is-12">
                        <input v-if="department" type="text" class="input" :value="departmentDisplayName" readonly>
                        <simple-search
                                v-else
                                search-endpoint="departments"
                                @selectedRecord="updateDepartment"
                        ></simple-search>
                    </div>
                </div>
            </section>
            <footer class="modal-card-foot">
                <button class="button is-primary"
                        :class="{'is-loading': isLoading}"
                        @click="sendAttachDepartmentRequest">{{ $t('words.save') }}</button>
                <button class="button" type="button" @click="close()">{{ $t('words.cancel') }}</button>
            </footer>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            departmentGroupId: {
                type: Number,
                required: true
            }
        },
        data() {
            return {
                department: null,
                isLoading: false,
            }
        },
        computed: {
            departmentDisplayName() {
                if(this.department) {
                    return this.department.code + ' - ' + this.department.region.title
                }
            }
        },
        methods: {
            updateDepartment(department) {
                this.department = department;
            },
            sendAttachDepartmentRequest() {
                this.isLoading = true;
                axios.post(this.apiUrl() + '/department-groups/attach', {
                    department_id: this.department.id,
                    department_group_id: this.departmentGroupId
                }).then(response => {
                    this.isLoading = false;
                    this.$emit('attachedDepartment');
                })
            },
            close() {
                this.$emit('closeModal');
            }
        }

    }
</script>

<style lang="sass">
    .modal-card-body
        min-height: 300px
</style>
