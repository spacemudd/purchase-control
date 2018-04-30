/*
 * Copyright (c) 2018 - Clarastars, LLC - All Rights Reserved.
 *
 * Unauthorized copying of this file via any medium is strictly prohibited.
 * This file is a proprietary of Clarastars LLC and is confidential.
 *
 * https://clarastars.com - info@clarastars.com
 *
 */

import { createActionHelpers } from 'vuex-loading';
const { startLoading, endLoading } = createActionHelpers({moduleName: 'loading'});

export default {
    getResource(context, resourceId) {
        startLoading(context.dispatch, 'GETTING_PURCHASE_REQUEST');
        axios.get(this.getters.apiUrl + '/purchase-requisitions/' + resourceId).then(response => {
            context.commit('request', response.data);
            endLoading(context.dispatch, 'GETTING_PURCHASE_REQUEST');
        }).catch(error => {
            alert(error.response.data.message);
            endLoading(context.dispatch, 'GETTING_PURCHASE_REQUEST');
        })
    }
}
