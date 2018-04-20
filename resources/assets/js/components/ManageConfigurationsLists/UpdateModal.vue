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
                    <div class="column is-6">
                        <div class="field">
                            <label class="label">{{ $t('words.property') }} <span class="has-text-danger">*</span></label>

                            <p class="control">
                                <input type="text"
                                       class="input"
                                       name="property"
                                       v-validate="'required'"
                                       :class="{'is-danger': errors.has('property')}"
                                       @keyup.enter="sendUpdateRequest"
                                       v-model="property">

                                <span class="help is-danger" v-if="errors.has('property')">
                                    {{ $t('validation.required', {attribute: $t('words.property')}) }}
                        	    </span>
                            </p>
                        </div>
                    </div>

                    <div class="column is-6">
                        <div class="field">
                            <label class="label">{{ $t('words.value') }} <span class="has-text-danger">*</span></label>

                            <p class="control">
                                <input type="text"
                                       class="input"
                                       name="value"
                                       v-validate="'required'"
                                       :class="{'is-danger': errors.has('value')}"
                                       @keyup.enter="sendUpdateRequest"
                                       v-model="value">

                                <span class="help is-danger" v-if="errors.has('value')">
                                    {{ $t('validation.required', {attribute: $t('words.value')}) }}
                        	    </span>
                            </p>
                        </div>
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
                property: '',
                value: '',
                isLoading: false,
            }
        },
        mounted() {
            this.property = this.recordToUpdate.property;
            this.value = this.recordToUpdate.value;
        },
        methods: {
            closeModal() {
                this.$emit('closeModal');
            },
            sendUpdateRequest() {
                if((!this.property) || (!this.value)) {
                    alert('Please fill the property/value field.');
                    return false;
                }

                this.isLoading = true;

                axios.put(this.apiUrl() + '/component-list/' + this.resource + '/update', {
                    id: this.recordToUpdate.id,
                    property: this.property,
                    value: this.value,
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
