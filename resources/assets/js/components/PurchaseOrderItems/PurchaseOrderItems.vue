<template>
    <div>
        <table class="table is-fullwidth is-size-7">
        <thead>
        <tr>
            <!--<th></th>-->
        	<th>Item</th>
            <th class="has-text-right">qty</th>
            <th class="has-text-right">Price</th>
            <th class="has-text-right">Tax</th>
            <th class="has-text-right">Subtotal</th>
            <th></th>
        </tr>
        </thead>
        	<tbody>
                <tr v-for="(item, index) in items">
                    <!--
                    <td style="width:20px;" class="align-middle">
                        <button style="cursor:move;" class="button is-text has-icon" v-if="items.length > 1">
                            <span class="icon">
                                <i style="opacity:0.7" class="fa fa-ellipsis-v"></i>
                            </span>
                        </button>
                    </td>
                    -->
                    <td style="width:250px">
                        <v-select :options="itemsCatalog" label="display_name" v-model="item.item_catalog" @input="option => itemCatalogSelected(item, option)" @search="onSearch">
                            <template slot="no-options">
                                Type to search for items
                            </template>
                            <template slot="option" slot-scope="option">
                                <div class="d-center">
                                    {{ option.code }}
                                </div>
                            </template>
                            <template slot="selected-option" slot-scope="option">
                                <div class="selected d-center">
                                    {{ option.display_name }}
                                </div>
                            </template>
                        </v-select>
                    </td>
                    <td style="width:100px">
                        <b-input class="has-text-right" type="number" v-model.number="item.qty"></b-input>
                    </td>
                    <td style="width:100px">
                        <b-input class="has-text-right" type="number" v-model.number="item.unit_price"></b-input>
                    </td>
                    <td style="width:170px">
                        <v-select multiple :options="taxesOptions" label="display_name" v-model="item.taxes" @input="option => itemTaxesEdit(option, item)">
                            <template slot="no-options">
                                Type to search for taxes
                            </template>
                            <template slot="option" slot-scope="option">
                                <div class="d-center">
                                    {{ option.display_name }}
                                </div>
                            </template>
                            <template slot="selected-option" slot-scope="option">
                                <div class="selected d-center">
                                    {{ option.display_name }}
                                </div>
                            </template>
                        </v-select>
                    </td>
                    <td style="width:90px" class="has-text-right is-size-5">
                        <template v-if="item.unit_price">{{ formatPrice(item.unit_price * item.qty) }}</template>
                    </td>
                    <td style="width:20px;" class="align-middle has-text-centered">
                        <button class="button is-text has-icon"
                                v-if="items.length > 1"
                                @click="removeItem(index)">
                                <span class="icon">
                                    <i style="opacity:0.7" class="fa fa-ban"></i>
                                </span>
                        </button>
                    </td>
                </tr>
                <!-- Footer -->
                <tr class="is-borderless">
                    <!--
                    <td></td>
                    -->
                    <td>
                        <button class="button is-small has-icon" @click="addLine()">
                            <span class="icon"><i class="fa fa-plus"></i></span>
                            <span>Add line</span>
                        </button>
                    </td>
                    <td></td>
                    <td></td>
                    <td class="has-text-right is-size-5">
                        Subtotal:
                    </td>
                    <td class="has-text-right is-size-5">
                        {{ formatPrice(subTotal) }}
                    </td>
                </tr>
                <!--
                <tr v-for="(tax, index) in taxesCalculated">
                    <td colspan="4">
                        {{ index }}
                    </td>
                    <td>
                        {{ tax.amount }}
                    </td>
                </tr>
                -->
                <!-- Change this to 5 when you add the drag 'n drop -->
                <!--
                <tr>
                    <td colspan="4" class="has-text-right is-size-5">Total (SAR)</td>
                    <td class="has-text-centered">
                        <span class="has-text-danger has-text-weight-bold"></span>
                    </td>
                </tr>
                -->
        	</tbody>
        </table>

        <div class="has-text-centered" v-if="isEditingMode=true">
            <button class="button is-primary" @click="save" :class="{'is-loading':$isLoading('SAVING_PO_ITEMS')}">Save</button>
        </div>
    </div>
</template>

<script>
  import vSelect from 'vue-select';
    export default {
        props: {
            poId: {
                type: Number,
                required: true,
            }
        },
      components: {
        vSelect,
      },
        data() {
            return {
              isEditingMode: true,

              taxesOptions: [],
              itemsCatalog: [],
              po: [],
              items: [{item_catalog: '', description: '', qty: '', unit_price: '', taxes: []}],

              taxesCalculated: [],
            }
        },
      computed: {
        subTotal() {
          return this.items.reduce((total, item) => {
            return total + Number(item.unit_price * item.qty);
          }, 0);
        },
      },
      watch: {
        items: {
          handler: function(newValue) {
            // this.recalculateTaxes();
          },
          deep: true,
        },
      },
        mounted() {
           this.getTaxesOptions();
           this.getPoItems();
        },
        methods: {
          getPoItems() {
            axios.get(this.apiUrl() + `/purchase-orders/${this.poId}/items`)
              .then(response => {
                this.items = response.data;
              })
          },
          getTaxesOptions() {
            axios.get(this.apiUrl() + '/sales-taxes')
              .then(response => {
                this.taxesOptions = response.data;
              })
          },
          formatPrice(value) {
            let val = (value/1).toFixed(2).replace(',', '.')
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
          },
          onSearch(search, loading) {
            loading(true);
            this.search(loading, search, this);
          },
          search: _.debounce((loading, search, vm) => {
            axios.get(vm.apiUrl() + `/search/item-templates?q=${escape(search)}`)
              .then(response => {
                vm.itemsCatalog = response.data.data;
                loading(false);
              })
          }, 350),
            getItems() {
                axios.post(this.apiUrl() + '/purchase-orders/show', {id: this.poId})
                    .then(response => {
                        this.po = response.data;
                        this.items = response.data.items;
                    })
                    .catch(error => {
                        alert('Error occurred getting items.');
                    })
            },
            confirmDeletingItem(itemId) {
                this.$dialog.confirm({
                    title: 'Deleting item',
                    message: 'Are you sure?',
                    onConfirm: () => this.deleteItem(itemId),
                })
            },
            deleteItem(itemId) {
                axios.delete(this.apiUrl() + `/purchase-orders/${this.poId}/requisition-items/${itemId}`)
                    .then(response => {
                        this.getItems();
                    })
                    .catch(error => {
                        alert(error.response.data.message);
                    })
            },
          removeItem(index) {
            this.items.splice(index, 1);
          },
          addLine() {
            this.items.push({item_catalog: '', description: '', qty: '', unit_price: '', taxes: []});
          },
          itemTaxesEdit(taxOption, item) {
            // Calculate the new tax.
            // if(taxOption) {
            //   axios.post(this.apiUrl() + '/sales-taxes/calc', {
            //     subtotal: item.qty * item.unit_price,
            //     taxes: taxOption,
            //   }).then(response => {
            //     item.calculated_taxes = response.data;
            //   })
            // }

            item.taxes = taxOption;
          },
          // recalculateTaxes() {
          //   this.taxesCalculated = [];
          //   this.items.map(item => {
          //     if(item.calculated_taxes) {
          //       item.calculated_taxes.map(tax => {
          //         console.log('ok...' + tax.name + ' --' + tax.amount);
          //         this.taxesCalculated[tax.name] = tax.amount;
          //       })
          //     }
          //   });
          // },
          itemCatalogSelected(item, option) {
            item.item_catalog = option;
            if(!item.qty) {
              item.qty = 1;
            }
            item.unit_price = option.unit_price;
          },
          save() {
            this.$startLoading('SAVING_PO_ITEMS');
            axios.post(this.apiUrl() + `/purchase-orders/${this.poId}/items/update`, {
              items: this.items,
            }).then(response => {
              this.$toast.open({
                type: 'is-success',
                message: 'Successfully saved',
              })

              this.$endLoading('SAVING_PO_ITEMS');
            })
              .catch(error => {
                this.$endLoading('SAVING_PO_ITEMS');
              })
          },
        }
    }
</script>

<style>
    .v-select.single .selected-tag + input[type="search"] {
        padding: 0;
        width: 0px !important;
    }
    .v-select {
        width: 100%;
    }
    .dropdown-toggle {
        width: 100%;
    }
    .is-borderless td {
        border: 0;
    }
</style>
