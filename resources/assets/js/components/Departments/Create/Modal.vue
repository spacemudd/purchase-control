<template>
    <div>
        <b-modal :active="showModal" @close="close()">
        <div class="modal-card">
            <div class="modal-card-head">
                <p class="modal-card-title">
                   {{ $t('words.new-department') }}
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
                                <p class="help">An identity of the department, e.g. 001.</p>
                            </div>
                        </div>

                        <div class="column is-6">
                            <div class="field">
                                <label class="label">{{ $t('words.description') }} <span class="has-text-danger">*</span></label>

                                <p class="control">
                                    <input type="text" class="input"
                                           v-model="description" required>
                                </p>

                                <p class="help">Displayed with the code, e.g. in reports and forms.</p>
                            </div>
                        </div>

                        <div class="column is-6">
                            <div class="field">
                                <label class="label">{{ $t('words.head-of-department') }}</label>
                                <div class="control">
                                    <input type="text" class="input" v-model="head_department">
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
            description: '',
            head_department: '',

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
                    return this.$store.getters['Department/showNewModal'];
                }
            },
        },
        mounted() {
            //
        },
        methods: {
            close() {
                this.$store.commit('Department/showNewModal', false);
                this.reset();
            },
            reset() {
                Object.assign(this.$data, initialState());
            },
            save() {
                this.isLoading = true;

                axios.post(this.apiUrl() + '/departments', {
                    code: this.code,
                    description: this.description,
                    head_department: this.head_department,
                }).then(response => {
                    // this.isLoading = false;
                    // this.createdSuccess = true;
                    // window.location = response.data.link;
                  this.$toast.open({
                    type: 'is-success',
                    message: 'New department successfully created',
                  })
                  this.close();
                }).catch(error => {
                    alert(error.response.data.message);
                    this.isLoading = false;
                })

            },
        }
    }
</script>
