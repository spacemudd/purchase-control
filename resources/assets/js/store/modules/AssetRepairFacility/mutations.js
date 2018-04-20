export default {
    setCurrentView(state, payload) {
        state.currentView = payload;
    },

    /**
     * Loading a new asset for viewing.
     *
     * @param state
     * @param issuance
     */
    setAsset(state, asset) {
        // TODO - Separate these into variables.
        state.asset = asset;
    },

    setVendor(state, payload) {
        state.vendor = payload;
    },

    setAssetId(state, assetId) {
        state.assetId = assetId;
    },

    setRepairWorkOrder(state, payload) {
        state.createdRepairWorkOrder = payload;
    }

}
