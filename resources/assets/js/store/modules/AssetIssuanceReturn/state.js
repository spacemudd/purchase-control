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

import AssetIssuanceReturnGetters from './getters';
import AssetIssuanceReturnMutations from './mutations';
import AssetIssuanceReturnActions from './actions';

export default {

    state: {

        'currentView': 'form',

        /**
         * Issuance return details.
         */
        'showNewIssuanceReturnModal': false,
        'department': null,
        'employee': null,
        'returnDate': null,
        'remarks': null,

        /**
         * Loaded an asset issuance return.
         */
        'loadedIssuanceReturn': null,
        'loadedIssuanceReturnSystemId': null,
        'loadedIssuanceReturnCode': null,
        'loadedDepartment': null,
        'loadedEmployee': null,
        'loadedCreatedBy': null,
        'loadedStatus': null,
        'loadedIssuanceReturnDate': null,
        'loadedRemarks': null,
        'loadedCreatedAt': null,
        'loadedUpdatedAt': null,

        'loadedItems': null,

        /**
         * When creating a new 'returned' asset.
         */
        'showNewItemModal': false,
        'showEditModal': false,
        'newReturnedAsset': null,
    },

    getters: AssetIssuanceReturnGetters,
    mutations: AssetIssuanceReturnMutations,
    actions: AssetIssuanceReturnActions,
};
