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
        state.returnDate = null;
        state.currentView = 'form';
    },
    setCurrentView(state, payload) {
        state.currentView = payload;
    },

    /**
     * Loading a new asset for viewing.
     *
     * @param state
     * @param issuance
     */
    setFullAsset(state, asset) {
        // TODO - Separate these into variables.
        state.asset = asset;
    },

    setReturnAssetModal(state, payload) {
        state.showReturnAssetModal = payload;
    },

    updateReturnDate(state, payload) {
        state.returnDate = payload;
    },

    updateReturnStatus(state, payload) {
        state.returnStatus = payload;
    },

    setReturnConfirmationModel(state, payload) {
        state.returnConfirmationModel = payload;
    },

    setChangedStatusModal(state, payload) {
        state.changeStatusModal = payload;
    },

    setChangedStatus(state, payload) {
        state.newChangedStatus = payload;
    },

    setChangeLocationModal(state, payload) {
        state.changeLocationModal = payload;
    },

}
