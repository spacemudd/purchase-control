<template>
    <div>
        <b-modal :active="showModal" @close="close()">
        <div class="modal-card">
            <div class="modal-card-head">
                <p class="modal-card-title">
                   {{ $t('words.new-employee') }}
                </p>
            </div>
            <form @submit.prevent="save()">
                <section class="modal-card-body">
                    <div class="columns is-multiline">
                        <div class="column is-6">
                            <div class="field">
                                <label class="label">{{ $t('words.code') }} <span class="has-text-danger">*</span></label>

                                <p class="control">
                                    <input v-model="code" class="input" required>
                                </p>
                            </div>
                        </div>

                        <div class="column is-6">
                            <div class="field">
                                <label class="label">{{ $t('words.name') }} <span class="has-text-danger">*</span></label>

                                <p class="control">
                                    <input type="text" class="input"
                                           v-model="name" required>
                                </p>
                            </div>
                        </div>

                        <div class="column is-6">
                            <div class="field">
                                <label class="label">{{ $t('words.departments') }} <span class="has-text-danger">*</span></label>
                                <div class="control">
                                    <input v-if="department" type="text" class="input" :value="departmentName" readonly>
                                    <simple-search
                                            v-else
                                            :size="'is-small'"
                                            search-endpoint="departments"
                                            @selectedRecord="updateDepartment"
                                    ></simple-search>
                                    <button type="button" class="button is-text is-small" @click.prevent="newDepartment">New Department</button>
                                </div>
                            </div>
                        </div>

                        <div class="column is-6">
                            <div class="field">
                                <label class="label">{{ $t('words.staff-type') }}</label>

                                <div class="control">
                                    <div class="select is-fullwidth">
                                        <select v-model="staffType">
                                            <option v-for="type in staffTypesOptions"
                                                    :value="type.id">
                                                {{ type.name }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="column is-6">
                            <div class="field">
                                <label class="label">{{ $t('words.contact-number') }}</label>

                                <p class="control">
                                    <input v-model="phone" class="input">
                                </p>
                            </div>
                        </div>



                        <div class="column is-6">
                            <div class="field">
                                <label class="label">{{ $t('auth.email') }}</label>

                                <div class="control">
                                    <input type="email"
                                           name="email"
                                           class="input"
                                           v-model="email">
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <footer class="modal-card-foot">
                    <button class="button" type="button" @click="close">{{ $t('words.cancel') }}</button>
                    <button :class="{'is-loading': isLoading}"
                            type="submit"
                            class="button is-success">
                        {{ $t('words.save') }}
                    </button>
                </footer>
            </form>
        </div>
    </b-modal>
    </div>
</template>

<script>
    function initialState() {
        return {
            code: '',
            name: '',
            department: null,
            staffType: null,
            email: null,
            phone: null,

            // Loaded by server.
            staffTypesOptions: [],

            // Handled by Vue.
            isLoading: false,
            createdSuccess: false,
        }
    }
    export default {
        data: () => initialState(),
        computed: {
            showModal: {
                get() {
                    return this.$store.getters['Employee/showNewEmployeeModal'];
                }
            },
            departmentName() {
                if(this.department) {
                    return this.department.code + ' - ' + this.department.description;
                } else {
                    return '';
                }
            }
        },
        mounted() {
            this.getStaffTypes();
        },
        methods: {
          newDepartment() {
            this.close();
            this.$store.commit('Department/showNewModal', true);
          },
            close() {
                this.$store.commit('Employee/setNewEmployeeModal', false);
                // this.reset();
            },
            // reset() {
            //     Object.assign(this.$data, initialState());
            // },
            getStaffTypes() {
                axios.get(this.apiUrl() + '/employees/types').then(response => {
                    this.staffTypesOptions = response.data.types;
                })
            },

            updateDepartment(payload) {
                this.department = payload;
            },
            save() {
                // Check all is required
                if(this.isNotCompleted()) {
                   return alert('Please complete the form');
                }

                this.isLoading = true;

                axios.post(this.apiUrl() + '/employees/store', {
                    code: this.code,
                    department_id: this.department.id,
                    name: this.name,
                    staff_type_id: this.staffType,
                    email: this.email,
                    phone: this.phone,
                }).then(response => {
                    // this.isLoading = false;
                    // this.createdSuccess = true;
                    // window.location = response.data.link;
                  this.$toast.open({
                    type: 'is-success',
                    message: 'Success - ' + this.name,
                  });

                  this.close();
                }).catch(error => {
                    alert(error.response.data.message);
                    this.isLoading = false;
                })

            },

            /**
             * @returns {boolean}
             */
            isNotCompleted() {
                return (!this.code) ||
                    (!this.department) ||
                    (!this.name);
            }
        }
    }
</script>
