import { createActionHelpers } from 'vuex-loading';
import moment from 'moment';
const { startLoading, endLoading } = createActionHelpers({
    moduleName: 'loading'
});

export default {


    /**
     * Fill the store with the full details of the issuance.
     *
     * @param context
     * @param issuanceId
     */
    getFullDetails(context, assetId) {

        if(!assetId) {
            alert('Missing asset ID when getting full asset issuance details.');
            return false;
        }

        startLoading(context.dispatch, 'GETTING_FULL_DETAILS');

        axios.get(this.getters.apiUrl + '/assets/' + assetId).then(response => {

            context.commit('setFullAsset', response.data);

            endLoading(context.dispatch, 'GETTING_FULL_DETAILS');

        }).catch(error => {

            alert(error.response.data.message);

            endLoading(context.dispatch, 'GETTING_FULL_DETAILS');

        })
    },


    submitChangeStatus(context, changedStatus) {
        if(!changedStatus) {
            alert('Missing changed status.');
            return false;
        }

        startLoading(context.dispatch, 'UPDATING_STATUS');

        let asset = this.getters['Asset/asset'];

        axios.post(this.getters.apiUrl + '/assets/status', {
            asset_id: asset.id,
            status: changedStatus
        }).then(response => {
            context.dispatch('getFullDetails', asset.id);
            context.commit('setChangedStatusModal', false);
            endLoading(context.dispatch, 'UPDATING_STATUS');
        }).catch(error => {
            alert(error.response.data.message);
            context.commit('setChangedStatusModal', false);
            endLoading(context.dispatch, 'UPDATING_STATUS');
        })
    },

    submitNewWorkOrder(context, submissionDate) {

        let assetId = this.getters['AssetRepairFacility/assetId'];
        let vendorId = this.getters['AssetRepairFacility/vendor'].id;
        let submissionDateParsed = moment(submissionDate).format('DD/MM/YYYY');

        startLoading(context.dispatch, 'SENDING_WORK_ORDER');
        axios.post(this.getters.apiUrl + '/work-orders/repair/store', {
            asset_id: assetId,
            vendor_id: vendorId,
            repair_submission_date: submissionDateParsed,
        }).then(response => {
            context.commit('setCurrentView', 'waiting-po-service-to-be-received');
            context.commit('setRepairWorkOrder', response.data);
            endLoading(context.dispatch, 'SENDING_WORK_ORDER');
        }).catch(error => {
            alert(error.response.data.message);
            endLoading(context.dispatch, 'SENDING_WORK_ORDER');
        });

    },

}
