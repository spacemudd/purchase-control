import AssetGetters from './getters';
import AssetMutations from './mutations';
import AssetActions from './actions';

export default {

    state: {

        'currentView': 'form',

        'asset': null,

        /* Changing status */
        'changeStatusModal': null,
        'newChangedStatus': null,

        /* Sending asset for repairing */
        'newRepairRemarks': null,

        /* Changing location */
        'changeLocationModal': null,

    },

    getters: AssetGetters,
    mutations: AssetMutations,
    actions: AssetActions,
};
