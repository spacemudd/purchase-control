import AssetIssuanceGetters from './getters';
import AssetIssuanceMutations from './mutations';
import AssetIssuanceActions from './actions';

export default {

    state: {

        'currentView': 'form',

        /**
         * When creating a new issuance / requirements of a new issuance modal's fields.
         */
        'showNewIssuanceModal': false,
        'department': null,
        'employee': null,
        'referenceNumberOptions': null, // <select> options for the user, async called from db.
        'referenceNumberType': null,
        'referenceNumber': null,
        'issuanceDate': null,
        'details': null,
        'newlyCreatedIssuance': null,

        /**
         * Loaded an asset issuance.
         */
        'loadedIssuance': null,
        'loadedIssuanceSystemId': null,
        'loadedIssuanceReferenceNumber': null,
        'loadedCreatedAt': null,
        'loadedCreator': null,
        'loadedDepartment': null,
        'loadedEmployee': null,
        'loadedIssuanceDate': null,
        'loadedItems': null,
        'loadedReferenceType': null,
        'loadedReferenceNumber': null,
        'loadedDetails': null,
        'loadedStatus': null,
        'loadedUpdatedAt': null,

        /**
         * When editing an issuance.
         */
        'showEditIssuanceModal': false,

        /**
         * When creating a new issued asset.
         */
        'showNewItemModal': false,
        'newIssuedAsset': null, // App\Models\Asset, selected one to be inserted into the issued db.
        'issuedForOptions': null, // <select> options for the user, async called from db.
        'newIssuedFor': null,

    },

    getters: AssetIssuanceGetters,
    mutations: AssetIssuanceMutations,
    actions: AssetIssuanceActions,
};
