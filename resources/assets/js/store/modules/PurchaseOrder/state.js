import PurchaseOrderGetters from './getters';
import PurchaseOrderMutations from './mutations';
import PurchaseOrderActions from './actions';

export default {
    state: {
        po: null,

        previewPdf: false,

        'currentView': 'form',
        'showNewModal': false,
        'mainOrderNumber': null,
        'purchaseOrderNumber': null,
        'purchaseOrderDate': null,
        'purchaseOrderDeliveryDate': null,
        'vendor': null, // used
        'vendorDeliveryNumber': null,
        'department': null,
        'employee': null,
        'newlyCreatedPurchaseOrder': null,
        'showNewReceiptItemModal': false,
        'showEditReceiptItemModal': false,

        // For editing modal.
        'editingReceiptItemId': null,
        'editingManufacturerSerialNumber': null,
        'editingSystemTagNumber': null,
        'editingFinanceTagNumber': null,
        'editingUnitPrice': null,
        'editingWarranty': null,
        'editingWarrantyType': null,
    },
    getters: PurchaseOrderGetters,
    mutations: PurchaseOrderMutations,
    actions: PurchaseOrderActions,
};
