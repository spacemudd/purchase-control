import { createActionHelpers } from 'vuex-loading';
import moment from 'moment';
const { startLoading, endLoading } = createActionHelpers({
    moduleName: 'loading'
});

export default {
    /**
     * Loads the purchase order.
     *
     * @param context
     * @param poId int @see \App\Models\PurchaseOrder
     */
    getPo(context, poId) {
        axios.post(this.getters.apiUrl + '/purchase-orders/show', {
            id: poId,
        })
            .then(response => {
                context.commit('setPo', response.data);
            }).catch(error => {
            alert(error.response.data.message);
        })
    },
    submitPo(context) {

        if(!this.getters['PurchaseOrder/vendor']) {
            return alert('Please complete the form.');
        }

        if(!this.getters['PurchaseOrder/department']) {
            return alert('Please complete the form.');
        }

        if(!this.getters['PurchaseOrder/employee']) {
            return alert('Please complete the form.');
        }

        if(!this.getters['PurchaseOrder/purchaseOrderDate']) {
            return alert('Please complete the form.');
        }

        startLoading(context.dispatch, 'CREATING_PURCHASE_ORDER');

        let purchaseOrderData = {
            vendor_id: this.getters['PurchaseOrder/vendor'].id,
            department_id: this.getters['PurchaseOrder/department'].id,
            employee_id: this.getters['PurchaseOrder/employee'].id,
            delivery_number: this.getters['PurchaseOrder/vendorDeliveryNumber'],
            date: this.getters['PurchaseOrder/purchaseOrderDate'] ? moment(this.getters['PurchaseOrder/purchaseOrderDate']).format('DD/MM/YYYY') : '',
            delivery_date: this.getters['PurchaseOrder/purchaseOrderDeliveryDate'] ? moment(this.getters['PurchaseOrder/purchaseOrderDeliveryDate']).format('DD/MM/YYYY') : '',
        };

        axios.post(this.getters.apiUrl + '/purchase-orders', purchaseOrderData)
            .then(response => {
                context.commit('setNewlyCreatedPurchaseOrder', response.data);
                context.commit('setCurrentView', 'completed');
                window.location = response.data.link;
                // endLoading(context.dispatch, 'CREATING_PURCHASE_ORDER');
        }).catch(error => {
            alert(error.response.data.message);
            endLoading(context.dispatch, 'CREATING_PURCHASE_ORDER');
        })
    },
    /**
     * Partial edit because it only updates the MSN, STG, FTN and Unit Price.
     *
     * @param context
     */
    submitPartialEditReceiptItem(context) {
        startLoading(context.dispatch, 'UPDATING_PURCHASE_ORDER_ITEM');

        let newData = {
            purchase_order_item_id: this.getters['PurchaseOrder/editingReceiptItemId'],
            manufacturer_serial_number: this.getters['PurchaseOrder/editingManufacturerSerialNumber'],
            system_tag_number: this.getters['PurchaseOrder/editingSystemTagNumber'],
            finance_tag_number: this.getters['PurchaseOrder/editingFinanceTagNumber'],
            unit_price: this.getters['PurchaseOrder/editingUnitPrice']
        };

        axios.post(this.getters.apiUrl + '/purchase-orders/items/partial-edit', newData).then(response => {

            endLoading(context.dispatch, 'UPDATING_PURCHASE_ORDER_ITEM');
            context.commit('viewEditReceiptModal', false);

            // TODO: Just load the new data into the store instead of refreshing the page.
            window.location.reload();

        }).catch(error => {
            endLoading(context.dispatch, 'UPDATING_PURCHASE_ORDER_ITEM');
        })
    },
}
