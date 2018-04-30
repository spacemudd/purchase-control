/*
 * Copyright (c) 2018 - Clarastars, LLC - All Rights Reserved.
 *
 * Unauthorized copying of this file via any medium is strictly prohibited.
 * This file is a proprietary of Clarastars LLC and is confidential.
 *
 * https://clarastars.com - info@clarastars.com
 *
 */

import Getters from './getters';
import Mutations from './mutations';
import Actions from './actions';

export default {

    state: {
        request: null,
        requestItems: [],

        previewPdf: false,
    },

    getters: Getters,
    mutations: Mutations,
    actions: Actions,
};
