export default {
    showNewModal(state) {
        return state.showNewModal;
    },
    employee(state) {
        return state.employee;
    },
    mainOrderNumber(state) {
        return state.mainOrderNumber;
    },
    purchaseOrderNumber(state) {
        return state.purchaseOrderNumber;
    },
    purchaseOrderDate(state) {
        return state.purchaseOrderDate;
    },
    vendor(state) {
        return state.vendor;
    },
    department(state) {
        return state.department;
    },
    purchaseOrderDeliveryDate(state) {
        return state.purchaseOrderDeliveryDate;
    },
    vendorDeliveryNumber(state) {
        return state.vendorDeliveryNumber;
    },
    newlyCreatedPurchaseOrder(state) {
        return state.newlyCreatedPurchaseOrder;
    },
    currentView(state) {
        return state.currentView;
    },
    showNewReceiptItemModal(state) {
        return state.showNewReceiptItemModal;
    },
    showEditReceiptItemModal(state) {
        return state.showEditReceiptItemModal;
    },
    editingManufacturerSerialNumber(state) {
        return state.editingManufacturerSerialNumber;
    },
    editingSystemTagNumber(state) {
        return state.editingSystemTagNumber;
    },
    editingFinanceTagNumber(state) {
        return state.editingFinanceTagNumber;
    },
    editingUnitPrice(state) {
        return state.editingUnitPrice;
    },
    editingWarranty(state) {
        return state.editingWarranty;
    },
    editingWarrantyType(state) {
        return state.editingWarrantyType;
    },
    editingReceiptItemId(state) {
        return state.editingReceiptItemId;
    }
}
