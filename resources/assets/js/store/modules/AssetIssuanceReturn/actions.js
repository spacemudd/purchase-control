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

import { createActionHelpers } from 'vuex-loading';
import moment from 'moment';
const { startLoading, endLoading } = createActionHelpers({
    moduleName: 'loading'
});

export default {

    /**
     * Fill the store with the full details of the issuance return.
     *
     * @param context
     * @param issuanceId
     */
    getFullAssetIssuanceReturn(context, issuanceReturnId) {

        if(!issuanceReturnId) {
            alert('Missing issuance return ID when getting full asset issuance return information. ' + issuanceReturnId);
            return false;
        }

        startLoading(context.dispatch, 'GETTING_FULL_ASSET_ISSUANCE_RETURN');

        axios.get(this.getters.apiUrl + '/asset-issuances-returns/' + issuanceReturnId).then(response => {

            context.commit('setNewItemModal', false);

            context.commit('setFullAssetIssuanceReturn', response.data);

            endLoading(context.dispatch, 'GETTING_FULL_ASSET_ISSUANCE_RETURN');

        }).catch(error => {

            alert(error.response.data.message);

            endLoading(context.dispatch, 'GETTING_FULL_ASSET_ISSUANCE_RETURN');

        })
    },


    /**
     * Fill the store with the full details of the issuance (just items).
     *
     * @param context
     * @param issuanceReturnId
     */
    getFullAssetIssuanceReturnItems(context, issuanceReturnId) {

        if(!issuanceReturnId) {
            alert('Missing issuance return ID when getting full asset issuance return details.');
            return false;
        }

        startLoading(context.dispatch, 'GETTING_FULL_ASSET_ISSUANCE_RETURN_ITEMS');

        axios.get(this.getters.apiUrl + '/asset-issuances-returns/' + issuanceReturnId).then(response => {

            let items = response.data.items;

            context.commit('setFullAssetIssuanceReturnItems', items);

            endLoading(context.dispatch, 'GETTING_FULL_ASSET_ISSUANCE_RETURN_ITEMS');

        }).catch(error => {

            alert(error.response.data.message);

            endLoading(context.dispatch, 'GETTING_FULL_ASSET_ISSUANCE_RETURN_ITEMS');

        })
    },

    submitNewIssuanceReturn(context) {

        if(!this.getters['AssetIssuanceReturn/department']) {
            return alert('Please complete the department.');
        }
        if(!this.getters['AssetIssuanceReturn/employee']) {
            return alert('Please fill the employee.');
        }
        if(!this.getters['AssetIssuanceReturn/returnDate']) {
            return alert('Please fill the employee.');
        }

        startLoading(context.dispatch, 'SUBMITTING_NEW_ISSUANCE_RETURN');

        let newData = {
            department_id: this.getters['AssetIssuanceReturn/department'].id,
            employee_id: this.getters['AssetIssuanceReturn/employee'].id,
            return_date: moment(this.getters['AssetIssuanceReturn/returnDate']).format('DD/MM/YYYY'),
            remarks: this.getters['AssetIssuanceReturn/remarks'],
        };

        axios.post(this.getters.apiUrl + '/asset-issuances-returns/store', newData).then(response => {

            context.commit('setCurrentView', 'completed');

            window.location = this.getters.baseUrl + '/issuance-returns/' + response.data.id;

            // endLoading(context.dispatch, 'SUBMITTING_NEW_ISSUANCE_RETURN');

        }).catch(error => {

            alert(error.response.data.message);

            endLoading(context.dispatch, 'SUBMITTING_NEW_ISSUANCE_RETURN');

        })
    },

    /**
     * Send request to create a new issued item.
     *
     * @param context
     */
    submitNewItem(context) {
        let issuanceReturnId = this.getters['AssetIssuanceReturn/loadedIssuanceReturnSystemId'];
        let newReturnedAsset = this.getters['AssetIssuanceReturn/newReturnedAsset'];

        if( (! issuanceReturnId )  || (! newReturnedAsset) ) {
            return alert('Please complete the form.');
        }

        let newItem = {
            asset_issuance_return_id: issuanceReturnId,
            asset_id: newReturnedAsset.id,
        };

        startLoading(context.dispatch, 'LOADING_ASSET_RETURN_ITEMS');

        axios.post(this.getters.apiUrl + '/asset-issuances-returns/items/store', newItem).then(response => {

            context.dispatch('getFullAssetIssuanceReturnItems', issuanceReturnId);

            context.commit('setNewItemModal', false);

            endLoading(context.dispatch, 'LOADING_ASSET_RETURN_ITEMS');

        }).catch(error => {

            alert(error.response.data.message);

            endLoading(context.dispatch, 'LOADING_ASSET_RETURN_ITEMS');

        })

    },

}
