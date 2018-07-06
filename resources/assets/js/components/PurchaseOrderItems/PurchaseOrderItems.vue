<template>
    <div>
        <table class="table is-fullwidth is-size-7">
        <thead>
        <tr>
            <th></th>
        	<th>Item</th>
            <th>Description</th>
            <th class="has-text-right">Quantity</th>
            <th class="has-text-right">Unit Price</th>
            <th class="has-text-right">Subtotal</th>
        </tr>
        </thead>
        	<tbody>
                <tr v-for="(item, index) in items">
                    <td class="align-middle">
                        <button style="cursor:move;" class="button is-text has-icon" v-if="items.length > 1">
                            <span class="icon"><fa style="opacity:0.7" icon="ellipsis-v"/></span>
                        </button>
                    </td>
                    <td style="width:300px">
                        <v-select :options="itemsCatalog" label="display_name" @input="itemCatalogSelected" @search="onSearch">
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
                        <b-input v-model="item.description" style="width:300px"></b-input>
                    </td>
                    <td style="width:100px">
                        <b-input class="has-text-right" type="number" v-model.number="item.quantity"></b-input>
                    </td>
                    <td style="width:100px">
                        <b-input class="has-text-right" type="number" v-model.number="item.unit_price"></b-input>
                    </td>
                    <td class="align-middle has-text-centered">
                        <button class="button is-text has-icon"
                                v-if="items.length > 1"
                                @click="removeItem(index)">
                                <span class="icon">
                                    <fa style="opacity:0.7" icon="ban"/>
                                </span>
                        </button>
                    </td>
                </tr>
        	</tbody>
        </table>
        <!--
        <b-table :data="items" class="is-size-7">
            <template slot-scope="props">
                <b-table-column label="Code">
                    {{ props.row.code }}
                </b-table-column>
                <b-table-column label="Description">
                    {{ props.row.description }}
                </b-table-column>
                <b-table-column label="Unit price" numeric>
                    {{ props.row.unit_price }}
                </b-table-column>
                <b-table-column label="Quantity" numeric>
                    {{ props.row.qty }}
                </b-table-column>
                <b-table-column label="Discount (flat)" numeric>
                    {{ props.row.discount_flat }}
                </b-table-column>
                <b-table-column label="Sub Total" numeric>
                    {{ props.row.subtotal }}
                </b-table-column>
                <b-table-column label="Tax (%)" numeric>
                    {{ props.row.tax_rate1 }}
                </b-table-column>
                <b-table-column label="Total" numeric>
                    {{ props.row.total }}
                </b-table-column>
                <b-table-column label="Actions" numeric>
                    <button v-if="po.status_name === 'draft'"
                            class="button is-small is-danger has-icon"
                            @click="confirmDeletingItem(props.row.id)">
                        <span class="icon"><i class="fa fa-trash"></i></span>
                        <span>Delete</span>
                    </button>
                </b-table-column>
            </template>
            <template slot="empty">
                <div class="section">
                    <div class="content has-text-grey has-text-centered">
                        <p>No items.</p>
                    </div>
                </div>
            </template>
        </b-table>
        -->
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
              itemsCatalog: [],
              po: [],
              items: [{item_catalog: '', description: '', quantity: '', unit_price: ''}],
            }
        },
        mounted() {
           // this.getItems();
        },
        methods: {
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
          itemCatalogSelected(option, item) {
            console.log(option)
            item.description = option.description;
          },
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
</style>
