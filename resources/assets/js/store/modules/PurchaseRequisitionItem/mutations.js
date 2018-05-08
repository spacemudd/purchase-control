/*
 * Copyright (c) 2018 - Clarastars, LLC - All Rights Reserved.
 *
 * Unauthorized copying of this file via any medium is strictly prohibited.
 * This file is a proprietary of Clarastars LLC and is confidential.
 *
 * https://clarastars.com - info@clarastars.com
 *
 */

export default {
    /**
     * Purchase Requisition Item.
     *
     * @param state
     * @param payload
     */
    setItem(state, payload) {
        state.item = payload;
    },
    /**
     *
     * @param state
     * @param show bool
     */
    showModal(state, show) {
        state.showModal = show;
    }
}
