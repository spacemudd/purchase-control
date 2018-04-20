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


    // submitChangeStatus(context, changedStatus) {
    //     if(!changedStatus) {
    //         alert('Missing changed status.');
    //         return false;
    //     }
    //
    //     startLoading(context.dispatch, 'UPDATING_STATUS');
    //
    //     let asset = this.getters['Asset/asset'];
    //
    //     axios.post(this.getters.apiUrl + '/assets/status', {
    //         asset_id: asset.id,
    //         status: changedStatus
    //     }).then(response => {
    //         context.dispatch('getFullDetails', asset.id);
    //         context.commit('setChangedStatusModal', false);
    //         endLoading(context.dispatch, 'UPDATING_STATUS');
    //     }).catch(error => {
    //         alert(error.response.data.message);
    //         context.commit('setChangedStatusModal', false);
    //         endLoading(context.dispatch, 'UPDATING_STATUS');
    //     })
    // },

    submitReturnAsset(context) {

        let assetId = this.getters['Asset/asset'].id;
        let returnDateUnparsed = this.getters['Asset/returnDate'];
        let returnDate = moment(returnDateUnparsed).format('DD/MM/YYYY');

        let returnStatus = this.getters['Asset/returnStatus'];

        if( ! returnDate) {
            alert('Please fill the date.');
            return false;
        }

        if( ! returnStatus) {
            alert('Please fill the status');
            return false;
        }

        startLoading(context.dispatch, 'RETURNING_ASSET');

        axios.post(this.getters.apiUrl + '/assets/return', {
            asset_id: assetId,
            condition: returnStatus,
            return_date: returnDate,
        }).then(response => {
            context.commit('setCurrentView', 'return_completed');
            context.commit('setReturnConfirmationModel', response.data);
            endLoading(context.dispatch, 'RETURNING_ASSET');
        }).catch(response => {
            alert(response.data.message.response);
            endLoading(context.dispatch, 'RETURNING_ASSET');
        })

    }

}
