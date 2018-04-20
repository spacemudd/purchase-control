<template>
    <div class="modal is-active">
        <div class="modal-background" @click="closeModal"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">
                    {{ $t('words.update') }}
                </p>
                <button class="delete" aria-label="close" @click="closeModal"></button>
            </header>
            <section class="modal-card-body">
                <div class="columns is-multiline">
                    <div class="column is-12">
                        <div class="field">
                            <label class="label">{{ $t('words.name') }} <span class="has-text-danger">*</span></label>

                            <p class="control">
                                <input type="text"
                                       class="input"
                                       name="name"
                                       v-validate="'required'"
                                       :class="{'is-danger': errors.has('name')}"
                                       @keyup.enter="sendUpdateRequest"
                                       v-model="nameField">

                                <span class="help is-danger" v-if="errors.has('name')">
                                    {{ $t('validation.required', {attribute: $t('words.name')}) }}
                        	    </span>
                            </p>
                        </div>
                    </div>

                    <div class="column is-12">
                        <label class="checkbox">
                            <input type="checkbox" v-model="activeField">
                            {{ $t('words.active') }}
                        </label>
                    </div>
                </div>
            </section>
            <footer class="modal-card-foot">
                <button class="button is-success"
                        :class="{'is-loading': isLoading}"
                        @click="sendUpdateRequest">{{ $t('words.save') }}</button>
                <button class="button" @click="closeModal">{{ $t('words.cancel') }}</button>
            </footer>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            recordToUpdate: {
                type: Object,
                required: true
            },
            resource: {
                type: String,
                required: true,
            }
        },
        data() {
            return {
                nameField: '',
                activeField: '',
                isLoading: false,
            }
        },
        mounted() {
            this.nameField = this.recordToUpdate.name;
            this.activeField = this.recordToUpdate.active;
        },
        methods: {
            closeModal() {
                this.$emit('closeModal');
            },
            sendUpdateRequest() {
                if(!this.nameField) {
                    alert('Please fill the name field.');
                    return false;
                }

                this.isLoading = true;

                axios.put(this.apiUrl() + '/component-list/' + this.resource + '/update', {
                    id: this.recordToUpdate.id,
                    name: this.nameField,
                    active: this.activeField
                }).then(response => {
                    this.isLoading = false;
                    this.$emit('updateDatasets');
                    this.$emit('closeModal');
                }).catch(error => {
                    alert(error.response.data);
                    this.isLoading = false;
                })
            },
        }
    }
</script>
