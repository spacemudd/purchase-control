
require('./bootstrap');

window.Vue = require('vue');
//import Vue from 'vue';

window.Fuse = require('fuse.js');

/**
 * Localization
 */
import VueInternalization from 'vue-i18n';
import Locales from './vue-i18n-locales.generated.js';
Vue.use(VueInternalization);
Object.keys(Locales).forEach(function (lang) {
    Vue.locale(lang, Locales[lang]);
});

import { store } from './store/store';
import { mapGetters } from 'vuex';

import moment from 'moment';

import vSelect from 'vue-select';
Vue.use(vSelect);

var VueCookie = require('vue-cookie');
Vue.use(VueCookie);

import VeeValidate from 'vee-validate';
Vue.use(VeeValidate);

import MaskedInput from 'vue-masked-input'
Vue.use(MaskedInput);

import vClickOutside from 'v-click-outside'
Vue.use(vClickOutside);

import Buefy from 'buefy';
Vue.use(Buefy, {
    defaultIconPack: 'fa',
    defaultDateFormatter: (date) => {
        return moment(date).format('DD/MM/YYYY');
    },
    defaultDateParser: (date) => {
        return moment(date, 'DD/MM/YYYY').toDate();
    }
});

import Notify from 'notifyjs';

Vue.component('avatar', require('vue-avatar/src/Avatar').default);
Vue.component('confirm-modal', require('./components/Modals/ConfirmModal/ConfirmModal.vue').default);

Vue.component('importing-ad-groups', require('./components/ImportingAdGroups/ImportingAdGroups.vue').default);
Vue.component('toggle-permission', require('./components/TogglePermission/TogglePermission.vue').default);
Vue.component('toggle-role', require('./components/ToggleRole/ToggleRole.vue').default);
Vue.component('departments', require('./components/Departments/Browse/Browse.vue').default);
Vue.component('department-show', require('./components/Departments/Show/Show.vue').default);

// Employees
    Vue.component('employees',require('./components/Employees/Browse/Browse.vue').default);
    Vue.component('employee-show', require('./components/Employees/Show/Show').default);
    Vue.component('create-employee', require('./components/Employees/Create/Create.vue').default);
    Vue.component('create-employee-modal', require('./components/Employees/Create/Modal.vue').default);

// Departments
Vue.component('create-department', require('./components/Departments/Create/Create.vue').default);
Vue.component('create-department-modal', require('./components/Departments/Create/Modal.vue').default);

Vue.component('manufacturers', require('./components/Manufacturers/Browse/Browse.vue').default);
Vue.component('new-manufacturer', require('./components/Manufacturers/CreateModal/Modal.vue').default);
// Vue.component('assets', require('./components/Assets/index/index.vue').default);

Vue.component('vendors', require('./components/vendors/Browse/Browse.vue').default);
Vue.component('new-vendor', require('./components/vendors/CreateModal/Modal.vue').default);
Vue.component('vendor-manufacturers-card', require('./components/VendorManufacturersCard/VendorManufacturersCard').default);

Vue.component('purchase-orders', require('./components/PurchaseOrders/Browse/Browse.vue').default);
Vue.component('purchase-order-show', require('./components/PurchaseOrders/Show/Show.vue').default);
Vue.component('purchase-order-items', require('./components/PurchaseOrders/Items/Browse/Browse.vue').default);
Vue.component('new-purchase-order', require('./components/PurchaseOrders/NewPurchaseOrderModal/Modal.vue').default);
Vue.component('edit-purchase-order-modal', require('./components/PurchaseOrders/EditPurchaseOrderModal/Modal.vue').default);
// Vue.component('asset-issuances', require('./components/AssetIssuances/Browse/Browse.vue').default);
// Vue.component('new-asset-issuance', require('./components/AssetIssuances/NewIssuanceModal/NewModal.vue').default);
// Vue.component('asset-issuance-items', require('./components/AssetIssuances/Items/Browse/Browse.vue').default);
// Vue.component('asset-issuance-show', require('./components/AssetIssuances/Show/Show.vue').default);

Vue.component('purchase-order-item-new-modal', require('./components/PurchaseOrderItemNewModal/PurchaseOrdertemNewModal').default);

// Asset returns.
// Vue.component('asset-issuances-returns', require('./components/AssetIssuancesReturns/Browse/Browse.vue').default);
// Vue.component('new-asset-issuance-return', require('./components/AssetIssuancesReturns/NewIssuanceReturnModal/NewModal.vue').default);
// Vue.component('asset-issuance-return-show', require('./components/AssetIssuancesReturns/Show/Show.vue').default);

// Vue.component('asset-management', require('./components/AssetManagement/Browse/Browse.vue').default);
// Vue.component('asset-requiring-repair', require('./components/AssetsRequiringRepair/Browse/Browse.vue').default);
// Vue.component('repair-return-wizard', require('./components/AssetsRequiringRepair/Wizard/Wizard.vue').default);
// Vue.component('return-from-repair-wizard', require('./components/AssetsRequiringRepair/ReturnWizard/ReturnWizard.vue').default);
// Vue.component('asset-issuances-reports', require('./components/AssetIssuancesReports/Browse/Browse.vue').default);
// Vue.component('asset-stock-reports', require('./components/AssetStockReports/Browse/Browse.vue').default);
// Vue.component('verify-mobile-number', require('./components/VerifyMobileNumber/VerifyMobileNumber.vue').default);
// Vue.component('create-asset-issuance-item', require('./components/AssetIssuances/Items/Create/Create.vue').default);
// Vue.component('return-confirmation-index', require('./components/ReturnConfirmation/Index/Index.vue').default);
// Vue.component('return-confirmation-show', require('./components/ReturnConfirmation/Show/Show.vue').default);
// Vue.component('user-activities', require('./components/UserActivities/Browse/UserActivityBrowse.vue').default);
// Vue.component('export-report', require('./components/ExportSelectedColumns/ExportSelectedColumns.vue').default);
Vue.component('simple-search-edit', require('./components/SimpleSearchEdit/SimpleSearchEdit.vue').default);
Vue.component('simple-search-return-id', require('./components/SimpleSearch/ReturnId/ReturnId.vue').default);
// Vue.component('asset-template-create', require('./components/AssetTemplates/Create/Create.vue').default);
Vue.component('simple-search-show', require('./components/SimpleSearchShow/SimpleSearchShow.vue').default);
// Vue.component('asset-issuance-item-revamp', require('./components/AssetIssuances/Items/CreateRevamp/Create.vue').default);
// Vue.component('asset-templates-status-barchart', require('./components/AssetTemplates/StatusBarchart/Graph.vue').default);
Vue.component('new-resource-sidebar', require('./components/Sidebar/NewResourceSidebar/NewResourceSidebar.vue').default);
Vue.component('simple-search', require('./components/SimpleSearch/ReturnIdRevamp/ReturnIdRevamp.vue').default);
Vue.component('loading-screen', require('./components/LoadingAnimations/SvgBrand/SvgBrand.vue').default);
// Vue.component('asset-show', require('./components/Assets/show/show.vue').default);
Vue.component('settings-sidebar-menu', require('./components/Sidebar/SettingsSidebarMenu/Settings.vue').default);
// Vue.component('new-issuance', require('./components/AssetIssuances/NewIssuanceModal/NewModal.vue').default);
// Vue.component('new-issuance-return', require('./components/AssetIssuancesReturns/NewIssuanceReturnModal/NewModal.vue').default);
// Vue.component('location-codes-index', require('./components/LocationCodes/Index.vue').default);
// Vue.component('location-codes-show', require('./components/Locations/Show/Show.vue').default);
Vue.component('department-groups-index', require('./components/DepartmentGroups/Index/Index').default);
// Vue.component('asset-template-show', require('./components/AssetTemplates/Show/Show').default);
// Vue.component('reports-page', require('./components/Reports/Index.vue').default);

// Vue.component('manage-lists', require('./components/ManageLists/Manage.vue').default);
// Vue.component('manage-configurations-lists', require('./components/ManageConfigurationsLists/Manage.vue').default);

Vue.component('personal-user-list', require('./components/PersonalUserList/List.vue').default);

// Vue.component('system-preferences', require('./components/SystemPreferences/Browse/Browse.vue').default);

Vue.component('file-upload-button', require('./components/UploadFile/Button.vue').default);
Vue.component('view-all-files-button', require('./components/UploadFile/ViewAllButton.vue').default);

/**
 * Search components.
 */
Vue.component('advanced-search', require('./components/AdvancedSearch/AdvancedSearch.vue').default);
Vue.component('employees-results', require('./components/Employees/DropdownPaginatedSearchResult/Result.vue').default);
Vue.component('purchase-orders-results', require('./components/PurchaseOrders/DropdownPaginatedSearchResult/Result.vue').default);
// Vue.component('asset-issuances-results', require('./components/AssetIssuances/DropdownPaginatedSearchResult/Result.vue').default);
// Vue.component('asset-issuances-returns-results', require('./components/AssetIssuancesReturns/DropdownPaginatedSearchResult/Result.vue').default);

/**
 * Sidebar components.
 */
Vue.component('sidebar-links-group', require('./components/Sidebar/SidebarLinksGroup/Group.vue').default);
Vue.component('sidebar-sub-link', require('./components/Sidebar/SidebarLinksGroup/SubGroupLink.vue').default);

Vue.component('signature-upload-file', require('./components/SignatureUploadFile/SignatureUploadFile.vue').default);
Vue.component('download-media-button', require('./components/DownloadMediaButton/DownloadMediaButton.vue').default);

Vue.component('inbox-navbar-button', require('./components/InboxNavbar/InboxNavbarButton.vue').default);
Vue.component('inbox-navbar-item', require('./components/InboxNavbar/InboxItem.vue').default);

// Vue.component('stock-status-report-page', require('./components/Reports/Stock/StatusPage').default);
// Vue.component('stock-item-fuse-search', require('./components/StockItemFuseSearch/StockItemFuseSearch.vue').default);
// Vue.component('build-stock-by-item-report', require('./components/StockByItemReport/StockByItemReport.vue').default);

Vue.component('item-template-form', require('./components/ItemTemplateForm/ItemTemplateForm').default);

Vue.component('address-field-token', require('./components/AddressFieldToken/AddressFieldToken').default);
Vue.component('choose-address-modal', require('./components/AddressFieldToken/ChooseAddressModal').default);

Vue.component('icon', require('./components/Brand/icon.vue').default);

// Login dipper.
Vue.component('login-dipper', require('./../../../packages/spacelantern/login-dipper/src/login-dipper.vue').default);

Vue.component('attachments', require('./components/Attachments/Attachments.vue').default);
Vue.component('scan-documents', require('./components/ScanDocuments/ScanDocuments.vue').default);

Vue.component('audit-trail', require('./components/AuditTrail/AuditTrail.vue').default);

Vue.component(
    'passport-clients',
    require('./components/passport/Clients.vue')
);

Vue.component(
    'passport-authorized-clients',
    require('./components/passport/AuthorizedClients.vue')
);

Vue.component(
    'passport-personal-access-tokens',
    require('./components/passport/PersonalAccessTokens.vue')
);

Vue.component('create-request-form', require('./components/CreateRequestForm/CreateRequestForm.vue').default);
Vue.component('request-container', require('./components/Requests/RequestContainer.vue').default);
Vue.component('request-header', require('./components/Requests/RequestHeader.vue').default);
Vue.component('resource-header', require('./components/ResourceHeader/ResourceHeader.vue').default);
Vue.component('request-items', require('./components/RequestItems/RequestItems.vue').default);
Vue.component('request-item-new-modal', require('./components/RequestItemNewModal/RequestItemNewModal.vue').default);
Vue.component('approve-confirmation', require('./components/ApproveConfirmation/ApproveConfirmation.vue').default);
Vue.component('autocomplete-select', require('./components/AutocompleteSelect/AutocompleteSelect.vue').default);
Vue.component('datepicker', require('./components/Datepicker/Datepicker.vue').default);
Vue.component('clipboard', require('./components/CopyToClipboard/CopyToClipboard').default);
Vue.component('select-employee', require('./components/SelectEmployee/SelectEmployee').default);
Vue.component('select-department', require('./components/SelectDepartment/SelectDepartment').default);
Vue.component('purchase-requisition-items', require('./components/PurchaseRequisitionItems/PurchaseRequisitionItems').default);
Vue.component('new-item-requisition-modal', require('./components/NewItemRequisitionModal/NewItemRequisitionModal').default);
Vue.component('select-item-template', require('./components/SelectItemTemplate/SelectItemTemplate').default);
Vue.component('delete-prompt', require('./components/DeletePrompt/DeletePrompt').default);
Vue.component('notes-container', require('./components/NotesContainer/NotesContainer').default);
Vue.component('uploads-container', require('./components/UploadsContainer/UploadsContainer').default);
Vue.component('new-upload-modal', require('./components/NewUploadFile/NewUploadFile'))
Vue.component('preview-pdf', require('./components/PreviewPdf/PreviewPdf').default);
Vue.component('toggle-preview-requisition', require('./components/TogglePreviewRequisition/TogglePreviewRequisition').default);
Vue.component('create-approver-page', require('./components/CreateApproverPage/CreateApproverPage').default);
Vue.component('delete-dialog', require('./components/DeleteDialog/DeleteDialog').default);
Vue.component('approve-requisition', require('./components/ApproveRequisition/ApproveRequisition').default);
Vue.component('approve-requisition-modal', require('./components/ApproveRequisitionModal/ApproveRequisitionModal').default);
Vue.component('edit-requisition-remarks', require('./components/EditRequisitionRemarks/EditRequsitionRemarks').default);
Vue.component('pr-item-to-po-modal', require('./components/PrItemToPoModal/PrItemToPoModal').default);
Vue.component('select-purchase-orders', require('./components/SelectPurchaseOrder/SelectPurchaseOrder').default);
Vue.component('select-vendors', require('./components/SelectVendors/SelectVendors').default);
Vue.component('select-address', require('./components/SelectAddress/SelectAddress').default);
Vue.component('datetime-token', require('./components/DatetimeToken/DatetimeToken').default);
Vue.component('edit-supplier-token', require('./components/EditSupplierToken/EditSupplierToken').default);
Vue.component('delivery-date-token', require('./components/DeliveryDateToken/DeliveryDateToken').default);
Vue.component('toggle-purchase-term', require('./components/TogglePurchaseTerm/TogglePurchaseTerm').default);
Vue.component('new-po-item-from-pr-button', require('./components/NewPoItemFromPrButton/NewPoItemFromPrButton').default);
Vue.component('new-po-item-modal', require('./components/NewPoItemModal/NewPoItemModal').default);
Vue.component('select-purchase-requisition', require('./components/SelectPurchaseRequsition/SelectPurchaseRequisition').default);
Vue.component('purchase-order-items', require('./components/PurchaseOrderItems/PurchaseOrderItems').default);
Vue.component('toggle-preview-purchase-order', require('./components/TogglePreviewPurchaseOrder/TogglePreviewPurchaseOrder').default);
Vue.component('preview-pdf-container', require('./components/PreviewPdfContainer/PreviewPdfContainer').default);
Vue.component('purchase-requisition-simple-items', require('./components/PurchaseRequisitionSimpleItems/PurchaseRequisitionSimpleItems').default);
Vue.component('edit-pr-recommended-by-token', require('./components/EditPrRecommendedByToken/EditPrRecommendedByToken').default);
Vue.component('edit-pr-itam-approved-by-token', require('./components/EditPrItamApprovedByToken/EditPrItamApprovedByToken').default);
Vue.component('edit-pr-financial-approved-by-token', require('./components/EditPrFinancialApprovedByToken/EditPrFinancialApprovedByToken').default);
Vue.component('edit-pr-checked-by-token', require('./components/EditPrCheckedByToken/EditPrCheckedByToken').default);

Vue.component('other-terms-token', require('./components/OtherTermsToken/OtherTermsToken').default);

Vue.component('edit-employee-token', require('./components/EditEmployeeToken/EditEmployeeToken').default);
Vue.component('edit-department-token', require('./components/EditDepartmentToken/EditDepartmentToken').default);

Vue.component('toggle-default-purchase-term', require('./components/ToggleDefaultPurchaseTerm/ToggleDefaultPurchaseTerm').default);

Vue.component('item-catalog-create-modal', require('./components/ItemCatalogCreateModal/ItemCatalogCreateModal').default);

Vue.component('string-token', require('./components/StringToken/StringToken').default);
Vue.component('item-catalog-create-modal-form', require('./components/ItemCatalogCreateModal/ItemCatalogCreateModalForm').default);

Vue.component('select-projects', require('./components/SelectProjects/SelectProjects').default);

Vue.component('projects-create-modal', require('./components/ProjectsCreateModal/Modal').default);
Vue.component('projects-create-modal-form', require('./components/ProjectsCreateModal/ProjectsCreateModalForm').default);


// @see https://stackoverflow.com/questions/49517519/failed-to-mount-component-template-or-render-function-not-defined-vue-dynamic
Vue.component('material-request-items-container', require('./components/MaterialRequestItemsContainer/MaterialRequestItemsContainer.vue').default);
Vue.component('quotations-items-container', require('./components/QuotationItemsContainer/QuotationItemsContainer.vue').default);
Vue.component('select-material-request-items', require('./components/SelectMaterialRequestItem/SelectMaterialRequestItem.vue').default);
Vue.component('manage-quotation-suppliers-page', require('./components/ManageQuotationSuppliersPage/ManageQuotationSuppliersPage').default);

// Job orders
Vue.component('job-order-form', require('./components/JobOrders/Form.vue').default);

/**
 * API/App settings
 */
const apiVersion = '1';

// if(process.env.NODE_ENV === 'production') {
//      var envUrl = window.location.origin + '/PMS';
// } else {
   var envUrl = window.location.origin;
// }

Vue.mixin({
    methods: {
        baseUrl() {
            return envUrl;
        },
        apiUrl() {
            return envUrl + "/api/v" + apiVersion;
        },
        scannerUrl() {
            return 'https://localhost:8087';
        },
        getPermissions() {
            return permissions;
        },
        getTrans(key) {
            return this.$t(key)
        },
        startLoading(param) {
            this.$startLoading(param);
        },
        endLoading(param) {
            this.$endLoading(param);
        }
    }
});

const app = new Vue({
    el: '#app',
    store,
    computed: {
        ...mapGetters('loading', [
            'isLoading',
            'anyLoading',
        ]),
    },
    data: {
        showNav: false,
        menubarCollapsed: false,
        topMenuCollapsed: false,
        viewLanguageSwitch: false,
        proceduresCollapsed: false,
        reportsCollapsed: false,
        securityCollapsed: false,
        settingsCollapsed: false,
        showNotesAndFilesSidebar: false,
    },
    components: {
        'v-select': vSelect,
        'masked-input': MaskedInput,
    },
    mounted() {

        /**
        console.log('')
        console.log('')
        console.log('')
        console.log('Author - Shafiq al-Shaar <hello@getshafiq.com>')
        console.log('Project - ANB / Clarastars - Inventory Resource Management')
        console.log('')
        console.log('')
        console.log('')
         **/

        /**
         * Set vue's lang value depending on the <html lang="?"> value
         */
        if(document.querySelector("html").hasAttribute('lang')) {
            Vue.config.lang = document.querySelector("html").lang
        } else {
            Vue.config.lang = 'en';
        }

        // Menubar cookie
        let menubarCookie = this.$cookie.get('collapsed-menubar');
        if(menubarCookie) {
            if(menubarCookie === 'true') {
                this.menubarCollapsed = true;
            } else {
                this.menubarCollapsed = false;
            }

            // console.log({
            //     message: '[Root] CollapsedMenubar cookie is already initialized',
            //     raw: this.$cookie.get('collapsed-menubar'),
            //     cookie: menubarCookie
            // })

        } else {
            this.$cookie.set('collapsed-menubar', false);
            // console.log('[Root] CollapsedMenubar cookie was set to false on mount');
        }
    },
    methods: {
        toggleSidebar() {
            this.menubarCollapsed = ! this.menubarCollapsed;
            this.$cookie.set('collapsed-menubar', this.menubarCollapsed);
            console.log({message:'Setting cookie status as', value: this.menubarCollapsed})
        },
        showNewResourceSidebar() {
            this.$store.commit('ResourceSidebar/setShowNewResource', true);
        },
        showSettingsSidebar() {
            this.$store.commit('ResourceSidebar/setShowSettings', true);
        }
    },
});
