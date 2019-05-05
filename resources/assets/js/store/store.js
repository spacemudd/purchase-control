import Vue from 'vue';
import Vuex from 'vuex';
import { createVuexLoader } from 'vuex-loading'
import helpers from './../helpers';

const VuexLoading = createVuexLoader({
    // The Vuex module name, 'loading' by default.
    moduleName: 'loading',
    // The Vue component name, 'v-loading' by default.
    componentName: 'v-loading',
    // Vue component class name, 'v-loading' by default.
    className: 'v-loading',
});

Vue.use(Vuex);
Vue.use(VuexLoading);

import ResourceSidebar from './modules/ResourceSidebar/state';
import PurchaseOrder from './modules/PurchaseOrder/state';
import Employee from './modules/Employee/state';
import Department from './modules/Department/state';
import Vendor from './modules/Vendor/state';
import Manufacturer from './modules/Manufacturer/state';
import FileUpload from './modules/FileUpload/state';
import Project from './modules/Project/state';

import PurchaseRequisition from './modules/PurchaseRequisition/state';
import PurchaseRequisitionItem from './modules/PurchaseRequisitionItem/state';

import PurchaseOrderItem from './modules/PurchaseOrderItem/state';

import ItemCatalog from './modules/ItemCatalog/state';

import QSuppliers from './modules/QSupplier/state';

export const store = new Vuex.Store({
    plugins: [VuexLoading.Store],
    modules: {
        'ResourceSidebar': {
            namespaced: true,
            ...ResourceSidebar
        },
        'PurchaseOrder': {
            namespaced: true,
            ...PurchaseOrder
        },
        'Employee': {
            namespaced: true,
            ...Employee
        },
        'Department': {
          namespaced: true,
          ...Department
        },
        'Vendor': {
            namespaced: true,
            ...Vendor
        },
        'Project': {
            namespaced: true,
            ...Project,
        },
        'Manufacturer': {
            namespaced: true,
            ...Manufacturer
        },
        'FileUpload': {
            namespaced: true,
            ...FileUpload
        },
        'PurchaseRequisition': {namespaced:true, ...PurchaseRequisition},
        'PurchaseRequisitionItem': {namespaced:true, ...PurchaseRequisitionItem},
        'PurchaseOrderItem': {namespaced:true, ...PurchaseOrderItem},
        'itemCatalog': {namespaced:true, ...ItemCatalog},
        'QSuppliers': {namespaced:true, ...QSuppliers},
    },
    getters: helpers,
});

