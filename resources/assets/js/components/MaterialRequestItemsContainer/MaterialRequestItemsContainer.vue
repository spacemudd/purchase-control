<template>
    <div>
        <div class="columns">
            <div class="column">
                <p class="is-uppercase"><b>Request items</b></p>
            </div>
            <div class="column has-text-right">
                <button class="button has-icon is-small is-warning"
                        v-if="canEdit"
                        @click="isAdding=!isAdding"
                >
                    <span class="icon"><i class="fa fa-plus"></i></span>
                    <span>New Item</span>
                </button>
            </div>
        </div>

        <div class="columns">
            <div class="column">
                <loading-screen class="is-small" v-if="$isLoading('DELETING_ITEM') || $isLoading('LOADING_ITEMS')"></loading-screen>
                <table v-else class="table is-fullwidth is-bordered is-size-7">
                    <thead>
                        <tr>
                            <th width="50px" class="has-text-centered">#</th>
                            <th>Items</th>
                            <th width="50px" class="has-text-right">Quantity</th>
                            <th v-if="canEdit" width="50px"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, key) in items">
                            <td>{{ ++key }}</td>
                            <td>{{ item.description }}</td>
                            <td class="has-text-right">{{ item.qty }}</td>
                            <td class="has-text-centered" v-if="canEdit">
                                <button v-if="canEdit"
                                        @click="deleteItem(item, key)"
                                        class="button is-outlined is-danger has-icon is-small"
                                        :class="{'is-loading': $isLoading('DELETE_MATERIAL_REQUEST_ITEM_'+key)}"
                                >
                                    <span class="icon is-small"><i class="fa fa-times"></i></span>
                                </button>
                            </td>
                        </tr>
                        <tr v-if="isAdding">
                            <td></td>
                            <td @keyup.enter="saveNewItem">
                                <b-input v-model="form.description" autofocus></b-input>
                            </td>
                            <td><b-input type="numeric" v-model="form.qty"></b-input></td>
                            <td class="has-text-centered">
                                <button class="button is-primary is-small"
                                        :class="{'is-loading': $isLoading('SAVING_MATERIAL_REQUEST_ITEM')}"
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
        materialRequestId: {
          type: Number,
          required: true,
        },
        canEdit: {
          type: Boolean,
          required: false,
          default: false
        },
    },
    data() {
        return {
            newItemModal: false,
            isAdding: false,

            items: [],

            form: {
                material_request_id: this.material_request_id,
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
            this.$startLoading('LOADING_ITEMS');
            axios.get(this.apiUrl() + `/material-requests/${this.materialRequestId}/items`)
                .then(response => {
                    this.items = response.data;
                    this.$endLoading('LOADING_ITEMS');
                })
        },
        /**
         * @param item Object
         */
        deleteItem(item, key) {
            this.$startLoading('DELETE_MATERIAL_REQUEST_ITEM_'+key)
            axios.delete(this.apiUrl() + `/material-requests/${this.materialRequestId}/items/${item.id}`)
                .then((response) => {
                    this.items.splice(key-1, 1)
                    this.$endLoading('DELETE_MATERIAL_REQUEST_ITEM_'+key)
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
            this.$startLoading('SAVING_MATERIAL_REQUEST_ITEM');
            axios.post(this.apiUrl() + `/material-requests/`+this.materialRequestId+`/items/store`, this.form)
                .then(response => {
                    this.items.push(response.data);
                    this.form.description = '';
                    this.form.qty = 1;
                    this.$endLoading('SAVING_MATERIAL_REQUEST_ITEM');
                })
                .catch(error => {
                    alert(error.response.data.message);
                    this.$endLoading('SAVING_MATERIAL_REQUEST_ITEM');
                })
        },
    }
}
</script>
