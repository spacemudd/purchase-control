import ResourceSidebarGetters from './getters';
import ResourceSidebarMutations from './mutations';

export default {
    state: {
        'showResourceSidebar': false,
        'showSettingsSidebar': false,
    },
    getters: ResourceSidebarGetters,
    mutations: ResourceSidebarMutations,
};
