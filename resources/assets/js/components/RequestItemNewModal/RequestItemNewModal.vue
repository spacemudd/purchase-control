<!--
  - Copyright (c) 2018 - Clarastars, LLC - All Rights Reserved.
  -
  - Unauthorized copying of this file via any medium is strictly prohibited.
  - This file is a proprietary of Clarastars LLC and is confidential.
  -
  - https://clarastars.com - info@clarastars.com
  -
  -->

<template>
    <div class="modal-card">
        <header class="modal-card-head">
            <p class="modal-card-title">New Item</p>
        </header>
        <form @submit.prevent="submit">
            <section class="modal-card-body">
                <loading-screen v-if="isLoading"></loading-screen>
                <div class="columns is-multiline" v-else>
                    <div class="column is-12">
                        <b-field label="Name *" message="You can create items on the fly if you didn't select any of the pre-inserted items.">
                            <b-autocomplete v-model="itemName"
                                            placeholder="Search or type a new item name"
                                            field="name"
                                            :loading="isFetching"
                                            @input="getSearchResults"
                                            @select="option => selectedItem = option"
                                            :data="searchData"
                                            required>
                            </b-autocomplete>
                        </b-field>

                        <transition enter-active-class="animated fadeInLeft" leave-active-class="animated fadeOutLeft">
                            <p v-if="selectedItem">
                                Selected: {{ selectedItem.name }}. <button type="button" class="button is-small is-text" @click="selectedItem=itemName=null">Clear</button>
                            </p>
                        </transition>
                    </div>
                    <div class="column is-6">
                        <b-field label="Unit price *">
                            <b-input name="unit_price" v-model="unitPrice" type="number" min="0" step="0.01" required></b-input>
                        </b-field>
                    </div>

                    <div class="column is-6">
                        <b-field label="Quantity *">
                            <b-input name="qty" v-model="qty" type="number" min="0" step="0.01" required></b-input>
                        </b-field>
                    </div>
                </div>
            </section>
            <footer class="modal-card-foot">
                <button class="button is-success"
                        :class="{'is-loading': isLoading}">
                    {{ $t('words.save') }}
                </button>
                <button class="button" @click="$parent.close">{{ $t('words.cancel') }}</button>
            </footer>
        </form>
    </div>
</template>

<script>
    import debounce from "lodash/debounce";

    export default {
        data() {
            return {
                isLoading: false,
                isFetching: false,

                searchData: [],

                selectedItem: null,
                itemName: '',

                unitPrice: null,
                qty: 1,

            }
        },
        computed: {
            request() {
                return this.$store.getters['PurchaseControlRequest/request'];
            }
        },
        mounted() {
            //
        },
        methods: {
            getSearchResults: debounce(function() {
                this.searchData = [];
                this.isFetching = true;

                axios.get(this.apiUrl() + `/search/items?q=${this.itemName}`)
                    .then(response => {
                        response.data.forEach(item => {
                            this.searchData.push(item);
                        })

                        this.isFetching = false;
                    })
                    .catch(error => {
                        this.isFetching = false;
                        alert(error.response.data.message);
                    })
            }, 500),
            submit() {
                let form = {
                    item_id: this.selectedItem ? this.selectedItem.id : null,
                    name: this.itemName,
                    unit_price: this.unitPrice,
                    qty: this.qty,
                }

                this.isLoading = true;

                axios.post(this.apiUrl() + `/requests/${this.request.id}/items`, form)
                    .then(response => {
                        window.location.reload();
                    })
                    .catch(error => {
                        this.isLoading = false;
                        alert(error.response.data.message);
                    })
            },
        }
    }
</script>
