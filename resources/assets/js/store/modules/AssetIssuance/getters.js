export default {
    showNewIssuanceModal(state) {
        return state.showNewIssuanceModal;
    },
    showEditIssuanceModal(state) {
        return state.showEditIssuanceModal;
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
    referenceNumberOptions(state) {
        return state.referenceNumberOptions;
    },
    referenceNumberType(state) {
        return state.referenceNumberType;
    },
    referenceNumber(state) {
        return state.referenceNumber;
    },
    issuanceDate(state) {
        return state.issuanceDate;
    },
    details(state) {
        return state.details;
    },
    newlyCreatedIssuance(state) {
        return state.newlyCreatedIssuance;
    },

    // Loaded.
    loadedIssuanceSystemId(state) {
        return state.loadedIssuanceSystemId;
    },
    loadedIssuanceReferenceNumber(state) {
        return state.loadedIssuanceReferenceNumber;
    },
    loadedStatus(state) {
        return state.loadedStatus;
    },
    loadedIssuanceDate(state) {
        return state.loadedIssuanceDate;
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
    loadedDetails(state) {
        return state.loadedDetails;
    },
    loadedReferenceType(state) {
        return state.loadedReferenceType;
    },
    loadedReferenceNumber(state) {
        return state.loadedReferenceNumber;
    },
    loadedIssuance(state) {
        return state.loadedIssuance;
    },
    loadedItems(state) {
        return state.loadedItems;
    },

    // New issued item.
    showNewItemModal(state) {
        return state.showNewItemModal;
    },
    newIssuedAsset(state) {
        return state.newIssuedAsset;
    },
    issuedForOptions(state) {
        return state.issuedForOptions;
    },
    newIssuedFor(state) {
        return state.newIssuedFor;
    },
}
