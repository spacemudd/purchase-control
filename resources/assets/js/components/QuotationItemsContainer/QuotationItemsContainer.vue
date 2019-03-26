<template>
    <div>
        <div class="columns">
            <div class="column">
                <p class="is-uppercase"><b>Quotation Items</b></p>
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
                <loading-screen v-if="$isLoading('DELETING_ITEM')"></loading-screen>
                <table v-else class="table is-fullwidth is-bordered is-size-7">
                    <thead>
                    <tr>
                        <th width="50px" class="has-text-centered">#</th>
                        <th>Items</th>
                        <th width="70px" class="has-text-right">Quantity</th>
                        <th width="100px" class="has-text-right">Unit Price</th>
                        <th width="100px" class="has-text-right">Amount</th>
                        <th v-if="canEdit" width="50px"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(item, key) in items">
                        <td>{{ ++key }}</td>
                        <td>{{ item.description }}</td>
                        <td class="has-text-right">{{ item.qty }}</td>
                        <td class="has-text-right">{{ item.unit_price }}</td>
                        <td class="has-text-right">{{ item.unit_price * item.qty }}</td>
                        <td class="has-text-centered" v-if="canEdit">
                            <button v-if="canEdit"
                                    @click="deleteItem(item, key)"
                                    class="button is-outlined is-danger has-icon is-small"
                                    :class="{'is-loading': $isLoading('DELETE_QUOTATION_ITEM_'+key)}"
                            >
                                <span class="icon is-small"><i class="fa fa-times"></i></span>
                            </button>
                        </td>
                    </tr>
                    <tr v-if="isAdding">
                        <td colspan="2" @keyup.enter="saveNewItem">
                            <b-input ref="newItemDescription" size="is-small" v-model="form.description" autofocus></b-input>
                        </td>
                        <td><b-input size="is-small" type="number" v-model="form.qty"></b-input></td>
                        <td><b-input size="is-small" type="number" v-model="form.unit_price"></b-input></td>
                        <td>
                            <b-input size="is-small" type="number" :value="form.qty * form.unit_price" readonly></b-input>
                        </td>
                        <td class="has-text-centered">
                            <button class="button is-primary is-small"
                                    :class="{'is-loading': $isLoading('SAVING_QUOTATION_ITEM')}"
                                    @click="saveNewItem">
                                Save
                            </button>
                        </td>
                    </tr>
                    <tr class="has-text-centered" v-if="items.length === 0">
                        <td colspan="6"><p class="has-text-centered"><i>No items</i></p></td>
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
      quotationId: {
        type: Number,
        required: true,
      },
      canEdit: {
        type: Boolean,
        required: false,
        default: false
      },
    },
    mounted() {
      this.getItems();
    },
    data() {
      return {
        newItemModal: false,
        isAdding: false,

        items: [],

        form: {
          description: '',
          qty: 1,
          unit_price: 1,
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
    methods: {
      getItems() {
        axios.get(this.apiUrl() + `/quotations/${this.quotationId}/items`)
          .then(response => {
            this.items = response.data;
            this.$endLoading('DELETING_ITEM');
          })
      },
      /**
       * @param item Object
       */
      deleteItem(item, key) {
        this.$startLoading('DELETE_QUOTATION_ITEM_'+key)
        axios.delete(this.apiUrl() + `/quotations/${this.quotationId}/items/${item.id}`)
          .then((response) => {
            this.items.splice(key-1, 1)
            this.$endLoading('DELETE_QUOTATION_ITEM_'+key)
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
        // Validation
        if (!this.form.description) {
          alert('Item description is required');
          return;
        }

        this.$startLoading('SAVING_QUOTATION_ITEM');
        this.form.quotation_id = this.quotationId;
        axios.post(this.apiUrl() + `/quotations/`+this.quotationId+`/items/store`, this.form)
          .then(response => {
            this.items.push(response.data);
            this.form.description = '';
            this.form.qty = 1;
            this.$endLoading('SAVING_QUOTATION_ITEM');
            this.$refs.newItemDescription.focus();
          })
          .catch(error => {
            alert(error.response.data.message);
            this.$endLoading('SAVING_QUOTATION_ITEM');
          })
      },
    }
  }
</script>
