<template>
    <div>
        <b-table :data="items" class="is-size-7">
            <template slot-scope="props">
                <!--<b-table-column label="#">-->
                    <!--/-->
                <!--</b-table-column>-->
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
    </div>
</template>

<script>
    export default {
        props: {
            poId: {
                type: Number,
                required: true,
            }
        },
        data() {
            return {
                po: [],
                items: [],
            }
        },
        mounted() {
            this.getItems();
        },
        methods: {
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
        }
    }
</script>
