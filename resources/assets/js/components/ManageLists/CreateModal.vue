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
                    <div class="column is-12">
                        <div class="field">
                            <label class="label">{{ $t('words.name') }} <span class="has-text-danger">*</span></label>

                            <p class="control">
                                <input type="text"
                                       class="input"
                                       name="name"
                                       v-validate="'required'"
                                       :class="{'is-danger': errors.has('name')}"
                                       @keyup.enter="sendCreateRequest"
                                       v-model="nameField">

                                <span class="help is-danger" v-if="errors.has('name')">
                                    {{ $t('validation.required', {attribute: $t('words.name')}) }}
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
                nameField: '',
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
                if(!this.nameField) {
                    alert('Please fill the name field.');
                    return false;
                }

                this.isLoading = true;

                axios.post(this.apiUrl() + '/component-list/' + this.resource + '/store', {
                    name: this.nameField
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
