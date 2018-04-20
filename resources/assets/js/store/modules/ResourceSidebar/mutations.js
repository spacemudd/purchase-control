export default {
    /**
     * Set to show the sidebar or not.
     *
     * @param state
     * @param payload bool
     */
    setShowNewResource(state, payload) {
        state.showResourceSidebar = payload;
    },
    setShowSettings(state, payload) {
        state.showSettingsSidebar = payload;
    },
}
