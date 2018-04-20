import { createActionHelpers } from 'vuex-loading';
import moment from 'moment';
const { startLoading, endLoading } = createActionHelpers({
    moduleName: 'loading'
});

export default {

    getReferenceNumberOptions(context) {
        startLoading(context.dispatch, 'GETTING_REF_NUMBER_OPTIONS');

        axios.get(this.getters.apiUrl + '/reference-types').then(response => {

            context.commit('updateReferenceNumberOptions', response.data);

            endLoading(context.dispatch, 'GETTING_REF_NUMBER_OPTIONS');

        }).catch(error => {

            alert('Error occurred in getting reference number options.');

            endLoading(context.dispatch, 'GETTING_REF_NUMBER_OPTIONS');

        })
    },

    submitNewIssuance(context) {

        if(!this.getters['AssetIssuance/department']) {
            return alert('Please complete the form.');
        }
        if(!this.getters['AssetIssuance/employee']) {
            return alert('Please complete the form.');
        }

        startLoading(context.dispatch, 'SUBMITTING_NEW_ISSUANCE');

        let newData = {
            department_id: this.getters['AssetIssuance/department'].id,
            employee_id: this.getters['AssetIssuance/employee'].id,
            reference_type_id: this.getters['AssetIssuance/referenceNumberType'],
            reference_number: this.getters['AssetIssuance/referenceNumber'],
            issuance_date: moment(this.getters['AssetIssuance/issuanceDate']).format('DD/MM/YYYY'),
            details: this.getters['AssetIssuance/details'],
        };

        axios.post(this.getters.apiUrl + '/asset-issuances/store', newData).then(response => {
            context.commit('setCurrentView', 'completed');
            context.commit('updateNewlyCreatedIssuance', response.data);
            window.location = response.data.link;
            // endLoading(context.dispatch, 'SUBMITTING_NEW_ISSUANCE');
        }).catch(error => {
            alert(error.response.data.message);
            endLoading(context.dispatch, 'SUBMITTING_NEW_ISSUANCE');
        })
    },


    /**
     * Fill the store with the full details of the issuance.
     *
     * @param context
     * @param issuanceId
     */
    getFullAssetIssuance(context, issuanceId) {

        if(!issuanceId) {
            alert('Missing issuance ID when getting full asset issuance details.');
            return false;
        }

        startLoading(context.dispatch, 'GETTING_FULL_ASSET_ISSUANCE');

        axios.get(this.getters.apiUrl + '/asset-issuances/' + issuanceId).then(response => {

            context.commit('setFullAssetIssuance', response.data);

            endLoading(context.dispatch, 'GETTING_FULL_ASSET_ISSUANCE');

        }).catch(error => {

            alert(error.response.data.message);

            endLoading(context.dispatch, 'GETTING_FULL_ASSET_ISSUANCE');

        })
    },


    /**
     * Fill the store with the full details of the issuance (just items).
     *
     * @param context
     * @param issuanceId
     */
    getFullAssetIssuanceItems(context, issuanceId) {

        if(!issuanceId) {
            alert('Missing issuance ID when getting full asset issuance details.');
            return false;
        }

        startLoading(context.dispatch, 'GETTING_FULL_ASSET_ISSUANCE_ITEMS');

        axios.get(this.getters.apiUrl + '/asset-issuances/' + issuanceId).then(response => {

            let items = response.data.items;

            context.commit('setFullAssetIssuanceItems', items);

            endLoading(context.dispatch, 'GETTING_FULL_ASSET_ISSUANCE_ITEMS');

        }).catch(error => {

            alert(error.response.data.message);

            endLoading(context.dispatch, 'GETTING_FULL_ASSET_ISSUANCE_ITEMS');

        })
    },

    /**
     * Send request to create a new issued item.
     *
     * @param context
     */
    submitNewItem(context, returnAt) {

        let issuanceId = this.getters['AssetIssuance/loadedIssuanceSystemId'];
        let newIssuedAsset = this.getters['AssetIssuance/newIssuedAsset'];
        let newIssuedFor = this.getters['AssetIssuance/newIssuedFor'];

        if( (! issuanceId )  || (! newIssuedAsset) || (! newIssuedFor) ) {
            return alert('Please complete the form.');
        }


        let returnAtDate = null;
        if(newIssuedFor === 3 || newIssuedFor === '3') {
            returnAtDate = moment(returnAt).format('DD/MM/YYYY');
        } else {
            returnAtDate = null;
        }

        let newItem = {
            asset_issuance_id: issuanceId,
            asset_id: newIssuedAsset.id,
            issued_for_id: newIssuedFor,
            return_at: returnAtDate,
        };

        startLoading(context.dispatch, 'ADDING_ASSET_TO_ISSUANCE');

        axios.post(this.getters.apiUrl + '/asset-issuances/items/store', newItem).then(response => {

            context.commit('setNewItemModal', false);

            context.dispatch('getFullAssetIssuanceItems', issuanceId);

            endLoading(context.dispatch, 'ADDING_ASSET_TO_ISSUANCE');

        }).catch(error => {

            alert(error.response.data.message);

            endLoading(context.dispatch, 'ADDING_ASSET_TO_ISSUANCE');

        })

    },

    /**
     * Get all the options a user can select for 'issued for' field.
     *
     * @param context
     */
    getIssuedForOptions(context) {
        startLoading(context.dispatch, 'GETTING_ISSUED_FOR_OPTIONS');

        axios.get(this.getters.apiUrl + '/assets/issued-for/options').then(response => {

            context.commit('setIssuedForOptions', response.data);

            endLoading(context.dispatch, 'GETTING_ISSUED_FOR_OPTIONS');

        }).catch(error => {

            alert('Error in getting issued for options');

            endLoading(context.dispatch, 'GETTING_ISSUED_FOR_OPTIONS');
        })
    }

}
