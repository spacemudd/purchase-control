export default {
    showNewIssuanceModal(state) {
        return state.showNewIssuanceModal;
    },
    currentView(state) {
        return state.currentView;
    },

    asset(state) {
        return state.asset;
    },
    showReturnAssetModal(state) {
        return state.showReturnAssetModal;
    },
    returnDate(state) {
        return state.returnDate;
    },
    returnStatus(state) {
        return state.returnStatus;
    },
    returnConfirmationModel(state) {
        return state.returnConfirmationModel;
    },

    showChangeStatusModal(state) {
        return state.changeStatusModal;
    },

    newChangedStatus(state) {
        return state.newChangedStatus;
    }
}
