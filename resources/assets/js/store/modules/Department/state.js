import Getters from './getters';
import Mutations from './mutations';
import Actions from './actions';

export default {

    state: {
      showNewModal: false,
      form: {
        code: '',
        description: '',
        head_department: '',
      },
    },

    getters: Getters,
    mutations: Mutations,
    actions: Actions,
};
