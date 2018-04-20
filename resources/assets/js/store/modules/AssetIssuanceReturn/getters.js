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
    showNewIssuanceReturnModal(state) {
        return state.showNewIssuanceReturnModal;
    },
    currentView(state) {
        return state.currentView;
    },
    department(state) {
        return state.department;
    },
    employee(state) {
        return state.employee;
    },
    returnDate(state) {
        return state.returnDate;
    },
    remarks(state) {
        return state.remarks;
    },

    // Loaded.
    loadedIssuanceReturn(state) {
        return state.loadedIssuanceReturn;
    },
    loadedIssuanceReturnSystemId(state) {
        return state.loadedIssuanceReturnSystemId;
    },
    loadedIssuanceReturnCode(state) {
        return state.loadedIssuanceReturnCode;
    },
    loadedStatus(state) {
        return state.loadedStatus;
    },
    loadedIssuanceReturnDate(state) {
        return state.loadedIssuanceReturnDate;
    },
    loadedDepartment(state) {
        return state.loadedDepartment;
    },
    loadedDepartmentDisplayName(state) {
        if(state.loadedDepartment) {
            return state.loadedDepartment.code + ' - ' + state.loadedDepartment.description;
        }
        return state.loadedDepartment;
    },
    loadedEmployeeDisplayName(state) {
        if(state.loadedEmployee) {
            return state.loadedEmployee.code + ' - ' + state.loadedEmployee.name;
        }
    },
    loadedEmployee(state) {
        if(state.loadedEmployee) {
            return state.loadedEmployee;
        }
    },
    loadedItems(state) {
        return state.loadedItems;
    },
    loadedRemarks(state) {
        return state.loadedRemarks;
    },

    // New issued item.
    showNewItemModal(state) {
        return state.showNewItemModal;
    },
    showEditModal(state) {
        return state.showEditModal;
    },
    newReturnedAsset(state) {
        return state.newReturnedAsset;
    },
}
