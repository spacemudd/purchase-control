import { createActionHelpers } from 'vuex-loading';
const { startLoading, endLoading } = createActionHelpers({
    moduleName: 'loading'
});

export default {
    newDepartment(context) {
        let form = this.getters['Department/form'];

        startLoading(context.dispatch, 'CREATING_DEPARTMENT');
        axios.post(this.getters.apiUrl + '/departments', form).then(response => {
            context.commit('showNewModal', false);
            endLoading(context.dispatch, 'CREATING_DEPARTMENT');
        }).catch(error => {
            alert(error.response.data.message);
            endLoading(context.dispatch, 'CREATING_DEPARTMENT');
        });
    },
}
