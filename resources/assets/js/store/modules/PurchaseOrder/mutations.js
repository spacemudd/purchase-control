export default {
    /**
     * Set the modal on/off.
     *
     * @param state
     * @param payload bool
     */
    setModalActive(state, payload) {
        state.showNewModal = payload;
        state.employee = null;
        state.mainOrderNumber = null;
        state.purchaseOrderNumber = null;
        state.vendor = null;
        state.vendorDeliveryNumber = null;
        state.department = null;
        state.currentView = 'form';
    },
    updateEmployee(state, employee) {
        state.employee = employee;
    },
    updateMainOrderNumber(state, payload) {
        state.mainOrderNumber = payload;
    },
    updatePurchaseOrderNumber(state, payload) {
        state.purchaseOrderNumber = payload;
    },
    updatePurchaseOrderDate(state, payload) {
        state.purchaseOrderDate = payload;
    },
    updateVendor(state, payload) {
        state.vendor = payload;
    },
    updateDepartment(state, payload) {
        state.department = payload;
    },
    updatePurchaseOrderDeliveryDate(state, payload) {
        state.purchaseOrderDeliveryDate = payload;
    },
    updateVendorDeliveryNumber(state, payload) {
        state.vendorDeliveryNumber = payload;
    },
    setNewlyCreatedPurchaseOrder(state, payload) {
        state.newlyCreatedPurchaseOrder = payload;
    },
    setCurrentView(state, payload) {
        state.currentView = payload;
    },
    viewNewReceiptModal(state, payload) {
        state.showNewReceiptItemModal = payload;
    },
    viewEditReceiptModal(state, payload) {
        state.showEditReceiptItemModal = payload;
    },
    setEditingManufacturerSerialNumber(state, payload) {
        state.editingManufacturerSerialNumber = payload;
    },
    setEditingSystemTagNumber(state, payload) {
        state.editingSystemTagNumber = payload;
    },
    setEditingFinanceTagNumber(state, payload) {
        state.editingFinanceTagNumber = payload;
    },
    setEditingUnitPrice(state, payload) {
        state.editingUnitPrice = payload;
    },
    setEditingWarranty(state, payload) {
        state.editingWarranty = payload;
    },
    setEditingWarrantyType(state, payload) {
        state.editingWarrantyType = payload;
    },
    /**
     * Sets all the fields for when editing a receipt item.
     *
     */
    setEditingReceiptItem(state, payload) {
       state.editingReceiptItemId = payload.receiptItemId;
       state.editingManufacturerSerialNumber = payload.manufacturerSerialNumber;
       state.editingSystemTagNumber = payload.systemTagNumber;
       state.editingFinanceTagNumber = payload.financeTagNumber;
       state.editingUnitPrice = payload.unitPrice;
       state.editingWarranty = payload.warranty;
       state.editingWarrantyType = payload.warrantyType;
    },
}
