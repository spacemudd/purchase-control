import Getters from './getters';
import Mutations from './mutations';
import Actions from './actions';

export default {

    state: {

        'currentPage': 'index',
        'currentView': 'create-work-order',

        'asset': null, // currently not used
        'assetId': null,

        /* When creating a new work order */
        'vendor': null,
        'createdRepairWorkOrder': null,

        'showNewEmployeeModal': false,
    },

    getters: Getters,
    mutations: Mutations,
    actions: Actions,
};
