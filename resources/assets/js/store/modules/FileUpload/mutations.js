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

export default {
    setNewModal(state, payload)
    {
        state.showNewModal = payload;
    },

    /**
     *
     * @param state
     * @param name string
     */
    setResourceName(state, name)
    {
        state.resourceName = name;
    },

    /**
     *
     * @param state
     * @param id Number
     */
    setResourceId(state, id)
    {
        state.resourceId = id;
    }

}
