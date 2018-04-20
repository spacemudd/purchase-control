<!--
    @author Shafiq al-Shaar (shafiqshaar@gmail.com)
-->
<template>
    <div class="modal is-active" @keyup.esc="closeModal">
        <div class="modal-background" @click="closeModal"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">
                    {{ $t('words.new-service-line') }}
                </p>
                <button class="delete" aria-label="close" @click="closeModal"></button>
            </header>
            <section class="modal-card-body">

                <transition enter-active-class="animated fadeIn" leave-active-class="animated fadeOut" mode="out-in">
                        <div v-if="loading" key="loading" style="height:350px;">
                            <loading-screen size="is-small"></loading-screen>
                        </div>


                        <div v-if="!loading" key="form">
                            <div key="form-view" class="columns is-multiline">

                                <div class="column is-6">
                                    <div class="field">
                                        <label class="label">{{ $t('words.description') }} <span class="has-text-danger">*</span></label>
                                        <div class="control">
                                            <input name="description"
                                                   class="input"
                                                   :class="{'is-danger': errors.has('description')}"
                                                   type="text"
                                                   v-model="description"
                                                   v-validate="'required'">

                                            <p class="help is-danger" v-if="errors.has('description')">
                                                {{ $t('validation.required', {attribute: $t('words.description')}) }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="column is-6">
                                    <div class="field">
                                        <label class="label">{{ $t('words.quantity') }}</label>
                                        <div class="control">
                                            <input name="quantity"
                                                   class="input"
                                                   type="number"
                                                   step="0.01"
                                                   v-model="quantity">
                                        </div>
                                    </div>
                                </div>

                                <div class="column is-6">
                                    <div class="field">
                                        <label class="label">{{ $t('words.unit-cost') }}</label>
                                        <div class="control">
                                            <input name="unit_cost"
                                                   class="input"
                                                   type="number"
                                                   step="0.01"
                                                   v-model="unit_cost">
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                </transition>
            </section>
            <footer class="modal-card-foot">
                <button class="button is-success"
                        :class="{'is-loading': loading}"
                        @click.prevent="submitForm">{{ $t('words.save') }}</button>
                <button class="button" @click="closeModal">{{ $t('words.cancel') }}</button>
            </footer>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            purchaseOrderId: {
                type: Number,
                required: true,
            }
        },
        data() {
            return {
                loading: false,

                description: '',
                quantity: 1,
                unit_cost: 0,
            }
        },
        mounted() {
            //
        },
        computed: {

        },
        mounted() {

        },
        methods: {
            closeModal() {
                this.description = '';
                this.quantity = 1;
                this.unit_cost = 0;
                this.$emit('closeModal');
            },
            submitForm() {

                if(!this.quantity) {
                    return alert('Please fill the quantity.');
                }
                if(!this.purchaseOrderId) {
                    return alert('Please fill the purchase order number.');
                }
                if(!this.unit_cost) {
                    return alert('Please fill the unit cost.');
                }
                if(!this.description) {
                    return alert('Please fill the description.');
                }

                this.loading = true;

                let newRequest = {
                    purchase_order_id: this.purchaseOrderId,
                    description: this.description,
                    quantity: this.quantity,
                    unit_cost: this.unit_cost
                };

                axios.post(this.apiUrl() + '/purchase-orders/service-items/store', newRequest)
                    .then(response => {
                        this.loading = false;
                        this.$emit('successNewItem');
                        this.closeModal();
                    }).catch(error => {
                    this.loading = false;
                });

            }
        },
    }
</script>
