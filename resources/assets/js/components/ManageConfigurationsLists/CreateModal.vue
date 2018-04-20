<template>
    <div class="modal is-active">
        <div class="modal-background" @click="closeModal"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">
                    {{ $t('words.new') }}
                </p>
                <button class="delete" aria-label="close" @click="closeModal"></button>
            </header>
            <section class="modal-card-body">
                <div class="columns">
                    <div class="column is-6">
                        <div class="field">
                            <label class="label">{{ $t('words.property') }} <span class="has-text-danger">*</span></label>

                            <p class="control">
                                <input type="text"
                                       class="input"
                                       name="property"
                                       v-validate="'required'"
                                       :class="{'is-danger': errors.has('property')}"
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
                                       @keyup.enter="sendCreateRequest"
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
                        @click="sendCreateRequest">{{ $t('words.save') }}</button>
                <button class="button" @click="closeModal">{{ $t('words.cancel') }}</button>
            </footer>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            resource: {
                type: String,
                require: true
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
            //
        },
        methods: {
            closeModal() {
                this.$emit('closeModal');
            },
            sendCreateRequest() {
                if((!this.property) || (!this.value)) {
                    alert('Please fill the property/value field.');
                    return false;
                }

                this.isLoading = true;

                axios.post(this.apiUrl() + '/component-list/' + this.resource + '/store', {
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
