<template>
    <form @submit.prevent="save">
        <div class="modal-card" style="width: auto;height:500px">
            <header class="modal-card-head">
                <p class="modal-card-title">New PO Item</p>
            </header>
            <section class="modal-card-body">

                <!-- Form Errors -->
                <div class="notification is-danger" v-if="form.errors.length > 0">
                    <strong>An error occurred.</strong>
                    <br>
                    <ul>
                        <li v-for="error in form.errors">
                            {{ error }}
                        </li>
                    </ul>
                </div>

                <!-- Choose from a PR requisition -->
                <b-field label="Choose purchase requisition">
                    <select-purchase-requisition :url="apiUrl() + '/search/saved-requisitions'"
                                                 @selected="selectPurchaseRequisition"
                    >
                    </select-purchase-requisition>
                </b-field>

                <!-- Only show the table if the RQ has items? -->
                <table v-if="purchase_requisition_items.length && !pr_item"
                       class="table is-size-7 is-fullwidth"
                       >
                <thead>
                <tr>
                	<th>Code</th>
                    <th>Description</th>
                    <th></th>
                </tr>
                </thead>
                	<tbody>
                			<tr v-for="item in purchase_requisition_items">
                				<td>{{ item.code }}</td>
                                <td>{{ item.model_number }}</td>
                                <td class="has-text-right">
                                    <button class="button is-small is-primary" type="button" @click="selectPrItem(item)">Select</button>
                                </td>
                			</tr>
                	</tbody>
                </table>

                <!-- The item's pricing information -->
                <div class="columns is-multiline" v-if="pr_item">
                    <div class="column is-12">
                        <b-field label="Selected Item">
                            <b-input :value="pr_item.code + ' - ' + pr_item.model_number" readonly></b-input>
                        </b-field>
                    </div>
                    <div class="column is-6">
                        <b-field label="Unit Price">
                            <b-input type="number" step="0.001" min="0.00" v-model="form.unit_price"></b-input>
                        </b-field>
                    </div>
                    <div class="column is-6">
                        <b-field label="Quantity">
                            <b-input type="number" v-model="form.qty" readonly></b-input>
                        </b-field>
                    </div>
                    <div class="column is-6">
                        <b-field label="Discount">
                            <b-input type="number" step="0.001" min="0.00" v-model="form.discount"></b-input>
                        </b-field>
                    </div>
                    <div class="column is-6">
                        <b-field label="Tax (%)">
                            <b-input type="number" step="0.001" min="0.00" v-model="form.tax_rate1"></b-input>
                        </b-field>
                    </div>
                </div>

            </section>
            <footer class="modal-card-foot">
                <button class="button" type="button" @click="$parent.close()">Close</button>
                <button class="button is-primary"
                        :class="{'is-loading': $isLoading('SAVING_PO_ITEM')}"
                        type="submit" v-if="pr_item">Save</button>
            </footer>
        </div>
    </form>
</template>

<script>
    export default {
        data() {
            return {
                purchase_requisition: [],
                purchase_requisition_items: [],

                pr_item: null,

                form: {
                    pr_item_id: null,
                    qty: 1,
                    unit_price: 1,
                    discount: 0.00,
                    tax_rate1: 5,

                    errors: [],
                }
            }
        },
        computed: {
            poId() {
                return this.$store.getters['PurchaseOrderItem/poId'];
            },
        },
        mounted() {
            //
        },
        methods: {
            selectPurchaseRequisition(purchase_requisition) {
                this.purchase_requisition = purchase_requisition;
                this.purchase_requisition_items = purchase_requisition.items;
            },
            /**
             * PurchaseRequisitionItem.
             * @param item
             */
            selectPrItem(item) {
                this.form.pr_item_id = item.id;
                this.form.qty = item.qty;
                this.pr_item = item;
            },
            /**
             * Send the request for a new item.
             */
            save() {
                this.$startLoading('SAVING_PO_ITEM');
                axios.post(this.apiUrl() + '/purchase-orders/' + this.poId + '/requisition-items', this.form)
                    .then(response => {
                        window.location.reload();
                    })
                    .catch(error => {
                        this.$endLoading('SAVING_PO_ITEM');

                        if (typeof error.response.data === 'object') {
                            this.form.errors = _.flatten(_.toArray(error.response.data.errors));
                        } else {
                            this.form.errors = ['Something went wrong. Please try again.'];
                        }

                        this.$dialog.alert({
                            message: this.form.errors,
                            type: 'is-danger',
                        });

                        throw error;
                    })
            },
        }
    }
</script>
