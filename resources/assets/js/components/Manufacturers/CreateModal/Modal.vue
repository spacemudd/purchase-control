<template>
    <div v-if="open">
        <b-modal :active="open" @close="close">
            <div class="modal-card">
                <div class="modal-card-head">
                    <p class="modal-card-title">
                        {{ $t('words.new-manufacturer') }}
                    </p>
                </div>
                <section class="modal-card-body">
                    <div v-if="createdManufacturer">
                        <created-success :manufacturer="createdManufacturer" @close="close"></created-success>
                    </div>
                    <div class="columns is-multiline" v-else>
                        <div class="column is-6">
                            <div class="field">
                                <label class="label">{{ $t('words.code') }} <span class="has-text-danger">*</span></label>

                                <p class="control">
                                    <input v-model="code" class="input">
                                </p>
                            </div>
                        </div>

                        <div class="column is-6">
                            <div class="field">
                                <label class="label">{{ $t('words.description') }} <span class="has-text-danger">*</span></label>
                                <div class="control">
                                    <input type="text" class="input" v-model="description">
                                </div>
                            </div>
                        </div>

                        <div class="column is-6">
                            <div class="field">
                                <label class="label">{{ $t('words.active') }}</label>
                                <div class="control">
                                    <input type="checkbox" v-model="active">
                                </div>
                            </div>
                        </div>

                    </div>
                </section>
                <footer class="modal-card-foot">
                    <button @click="save()"
                            v-if="!createdManufacturer"
                            :class="{'is-loading': $isLoading('SAVING_MANUFACTURER')}"
                            class="button is-success">
                        {{ $t('words.save') }}
                    </button>
                    <button class="button" @click="close">{{ $t('words.cancel') }}</button>
                </footer>
            </div>
        </b-modal>
    </div>
</template>

<script>
    import CreatedSuccess from './CreatedSuccess.vue';
    export default {
        components: {
            CreatedSuccess,
        },
        data() {
            return {
                createdManufacturer: false,

                code: null,
                description: null,
                active: true,
            }
        },
        computed: {
            open: {
                get() {
                    return this.$store.getters['Manufacturer/showNewModal'];
                }
            },
        },
        methods: {
            close() {
                this.createdManufacturer = false;
                this.code = null;
                this.description = null;
                this.active = true;
                this.$store.commit('Manufacturer/setNewModal', false);
            },
            save() {
                this.$startLoading('SAVING_MANUFACTURER');

                axios.post(this.apiUrl() + '/manufacturers/store', {
                    code: this.code,
                    description: this.description,
                    active: this.active,
                }).then(response => {
                    this.createdManufacturer = response.data;
                    window.location = response.data.link;
                    // this.$endLoading('SAVING_MANUFACTURER');
                }).catch(error => {
                    alert(error.response.data.message);
                    this.$endLoading('SAVING_MANUFACTURER');
                });
            },
        }
    }
</script>
