/*
 * FIRSTSOLUTIONS / CLARIMOUNT CONFIDENTIAL
 * __________________
 *
 * 2018 FirstSolutions Association (https://firstsolu.com)
 * All Rights Reserved.
 *
 * NOTICE:  All information contained herein is, and remains
 * the property of FirstSolutions Association and its suppliers,
 * if any.  The intellectual and technical concepts contained
 * herein are proprietary to FirstSolutions Associations
 * and its suppliers and may be covered by U.S. and Foreign Patents,
 * patents in process, and are protected by trade secret or copyright law.
 * Dissemination of this information or reproduction of this material
 * is strictly forbidden unless prior written permission is obtained
 * from FirstSolutions Association.
 */

export default {
    /**
     * Toggle the new issuance return modal on/off.
     *
     * @param state
     * @param payload boolean
     */
    setNewIssuanceReturnModalActive(state, payload) {
        state.showNewIssuanceReturnModal = payload;

        // Clear everything.
        state.department = null;
        state.employee = null;
        state.returnDate = new Date();
        state.currentView = 'form';
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
    updateReturnDate(state, date) {
        state.returnDate = date;
    },
    updateRemarks(state, remarks) {
        state.remarks = remarks;
    },
    updateNewlyCreatedIssuanceReturn(state, issuanceReturn) {
        state.newlyCreatedIssuanceReturn = issuanceReturn;
    },

    /**
     * Loading a new issuance return for viewing.
     *
     * @param state
     * @param issuance return
     */
    setFullAssetIssuanceReturn(state, issuance) {
        state.loadedIssuanceReturn = issuance;
        state.loadedIssuanceReturnSystemId = issuance.id;
        state.loadedIssuanceReturnCode = issuance.code;
        state.loadedDepartment = issuance.department;
        state.loadedEmployee = issuance.employee;
        state.loadedCreatedBy = issuance.created_by;
        state.loadedStatus = issuance.status;
        state.loadedIssuanceReturnDate = issuance.return_date_human;
        state.loadedCreatedAt = issuance.created_at;
        state.loadedUpdatedAt = issuance.updated_at;
        state.loadedRemarks = issuance.remarks;

        state.loadedItems = issuance.items;
    },
    setFullAssetIssuanceReturnItems(state, items) {
        state.loadedItems = items;
    },

    /**
     * Toggle 'new item return' modal.
     *
     * @param state
     * @param payload bool
     */
    setNewItemModal(state, payload) {
        state.newReturnedAsset = null;
        state.showNewItemModal = payload;
    },

    /**
     * Set the edit modal if visible or not.
     *
     * @param state
     * @param payload
     */
    setEditModal(state, payload) {
        state.showEditModal = payload;
    },

    /**
     *
     * @param state
     * @param asset the asset object
     */
    setNewReturnedAsset(state, asset)
    {
        state.newReturnedAsset = asset;
    }

}
