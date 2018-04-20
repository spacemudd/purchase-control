export default {
    /**
     * Toggle the new issuance modal on/off.
     *
     * @param state
     * @param payload boolean
     */
    setNewIssuanceModalActive(state, payload) {
        state.showNewIssuanceModal = payload;

        // Clear everything.
        state.department = null;
        state.employee = null;
        state.referenceNumberType = null;
        state.referenceNumber = null;
        state.issuanceDate = new Date();
        state.currentView = 'form';
    },

    /**
     * Toggle the edit issuance modal on/off.
     *
     * @param state
     * @param payload boolean
     */
    setEditIssuanceModalActive(state, payload) {
        state.showEditIssuanceModal = payload;
    },
    setCurrentView(state, payload) {
        state.currentView = payload;
    },
    updateDepartment(state, department) {
        state.department = department;
    },
    updateEmployee(state, employee) {
        state.employee = employee;
    },
    updateIssuanceDate(state, date) {
        state.issuanceDate = date;
    },
    updateReferenceNumberOptions(state, payload) {
        state.referenceNumberOptions = payload;
    },
    updateReferenceNumberType(state, number) {
        state.referenceNumberType = number;
    },
    updateReferenceNumber(state, number) {
        state.referenceNumber = number;
    },
    updateIssuanceDate(state, date) {
        state.issuanceDate = date;
    },
    updateDetails(state, details) {
        state.details = details;
    },
    updateNewlyCreatedIssuance(state, issuance) {
        state.newlyCreatedIssuance = issuance;
    },

    /**
     * Loading a new issuance for viewing.
     *
     * @param state
     * @param issuance
     */
    setFullAssetIssuance(state, issuance) {
        state.loadedIssuanceSystemId = issuance.id;
        state.loadedIssuanceReferenceNumber = issuance.ref_id;
        state.loadedCreatedAt = issuance.created_at;
        state.loadedCreator = issuance.creator;
        state.loadedDepartment = issuance.department;
        state.loadedEmployee = issuance.employee;
        state.loadedIssuanceDate = issuance.issuance_date_human;
        state.loadedItems = issuance.items;
        state.loadedReferenceType = issuance.reference;
        state.loadedReferenceNumber = issuance.reference_number;
        state.loadedStatus = issuance.status;
        state.loadedUpdatedAt = issuance.updated_at;
        state.loadedDetails = issuance.details;

        state.loadedIssuance = issuance;
    },
    setFullAssetIssuanceItems(state, items) {
        state.loadedItems = items;
    },
    /**
     * Toggle new item to issue modal.
     *
     * @param state
     * @param payload
     */
    setNewItemModal(state, payload) {
        state.newIssuedAsset = null;
        state.newIssuedFor = null;
        state.showNewItemModal = payload;
    },
    /**
     * Set the to-be-issued asset.
     *
     * @param state
     * @param asset
     */
    setNewIssuedAsset(state, asset) {
        state.newIssuedAsset = asset;
    },
    setNewIssuedFor(state, issuedFor) {
        state.newIssuedFor = issuedFor;
    },
    setIssuedForOptions(state, options) {
        state.issuedForOptions = options;
    }

}
