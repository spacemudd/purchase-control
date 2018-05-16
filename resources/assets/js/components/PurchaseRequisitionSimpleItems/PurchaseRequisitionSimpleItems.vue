<template>
    <div>
        <div class="columns">
            <div class="column"><p class="title is-4">{{ $t('words.requisition-items') }}</p></div>
            <div class="column has-text-right">
                <button class="button has-icon"
                        v-if="this.getPermissions()['create-purchase-requisition-item'] && inDraft"
                        @click="isAdding=!isAdding"
                >
                    <span class="icon"><i class="fa fa-plus"></i></span>
                    <span>New Item</span>
                </button>
            </div>
        </div>

        <div class="columns">
            <div class="column">
                <loading-screen v-if="$isLoading('DELETING_SIMPLE_ITEM')"></loading-screen>
                <table v-else class="table is-fullwidth is-bordered is-size-7">
                    <colgroup>
                        <col style="width:1%;">
                        <col style="width:40%">
                        <col style="width:1%">
                        <col style="width:1%">
                        <col>
                    </colgroup>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Description</th>
                            <th class="has-text-right">Quantity</th>
                            <th v-if="inDraft"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, key) in items">
                            <td>{{ ++key }}</td>
                            <td>{{ item.description }}</td>
                            <td class="has-text-right">{{ item.qty }}</td>
                            <td class="has-text-centered" v-if="inDraft">
                                <button v-if="inDraft" @click="deleteItem(item)"
                                        class="button is-outlined is-danger has-icon is-small">
                                    <span class="icon is-small"><i class="fa fa-times"></i></span>
                                </button>
                            </td>
                        </tr>
                        <tr v-if="isAdding">
                            <td></td>
                            <td @keyup.enter="saveNewItem"><b-input v-model="form.description" autofocus></b-input></td>
                            <td><b-input v-model="form.qty"></b-input></td>
                            <td class="has-text-centered">
                                <button class="button is-primary is-small"
                                        :class="{'is-loading': $isLoading('SAVING_NEW_PR_SIMPLE_ITEM')}"
                                        @click="saveNewItem">
                                    Save
                                </button>
                            </td>
                        </tr>
                    <tr class="has-text-centered" v-if="items.length === 0">
                        <td colspan="5"><p class="has-text-centered"><i>No items</i></p></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            requisitionId: {
                type: Number,
                required: true,
            },
            inDraft: {
                type: Number,
                required: true,
            },
            isApproved: {
                type: Number,
                required: true,
            },
        },
        data() {
            return {
                newItemModal: false,
                isAdding: false,

                items: [],

                form: {
                    purchase_requisition_id: this.requisitionId,
                    description: '',
                    qty: 1,
                },
            }
        },
        computed: {
            showAttachItemToPoItemModal: {
                get() {
                    return this.$store.getters['PurchaseRequisitionItem/showModal'];
                },
                set(value) {
                    this.$store.commit('PurchaseRequisitionItem/showModal', value)
                }
            }
        },
        mounted() {
            this.getItems();
        },
        methods: {
            getItems() {
                axios.get(this.apiUrl() + `/purchase-requisitions/${this.requisitionId}/simple-items`)
                    .then(response => {
                        this.items = response.data;
                        this.$endLoading('DELETING_SIMPLE_ITEM');
                    })
            },
            /**
             * @param item Object
             */
            deleteItem(item) {
                this.$startLoading('DELETING_SIMPLE_ITEM');
                axios.delete(this.apiUrl() + `/purchase-requisitions-simple-items/${item.id}`)
                    .then((response) => {
                        this.getItems();
                    })
            },
            /**
             * Attach a Purchase Requisition item to a PO.
             *
             * @param item Object
             */
            attachItemToPo(item) {
                this.$store.commit('PurchaseRequisitionItem/setItem', item);
                this.$store.commit('PurchaseRequisitionItem/showModal', true);
            },
            saveNewItem(item) {
                this.$startLoading('SAVING_NEW_PR_SIMPLE_ITEM');
                axios.post(this.apiUrl() + `/purchase-requisitions-simple-items`, this.form)
                    .then(response => {
                        this.items.push(response.data);
                        this.form.description = '';
                        this.form.qty = 1;
                        this.$endLoading('SAVING_NEW_PR_SIMPLE_ITEM');
                    })
                    .catch(error => {
                        alert(error.response.data.message);
                        this.$endLoading('SAVING_NEW_PR_SIMPLE_ITEM');
                    })
            },
        }
    }
</script>
