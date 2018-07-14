
require('./bootstrap');

window.Vue = require('vue');

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

Vue.component('avatar', require('vue-avatar/src/Avatar'));
Vue.component('confirm-modal', require('./components/Modals/ConfirmModal/ConfirmModal.vue'));

Vue.component('importing-ad-groups', require('./components/ImportingAdGroups/ImportingAdGroups.vue'));
Vue.component('toggle-permission', require('./components/TogglePermission/TogglePermission.vue'));
Vue.component('toggle-role', require('./components/ToggleRole/ToggleRole.vue'));
Vue.component('departments', require('./components/Departments/Browse/Browse.vue'));
Vue.component('department-show', require('./components/Departments/Show/Show.vue'));

// Employees
    Vue.component('employees',require('./components/Employees/Browse/Browse.vue'));
    Vue.component('employee-show', require('./components/Employees/Show/Show'));
    Vue.component('create-employee', require('./components/Employees/Create/Create.vue'));
    Vue.component('create-employee-modal', require('./components/Employees/Create/Modal.vue'));

Vue.component('manufacturers', require('./components/Manufacturers/Browse/Browse.vue'));
Vue.component('new-manufacturer', require('./components/Manufacturers/CreateModal/Modal.vue'));
// Vue.component('assets', require('./components/Assets/index/index.vue'));

Vue.component('vendors', require('./components/vendors/Browse/Browse.vue'));
Vue.component('new-vendor', require('./components/vendors/CreateModal/Modal.vue'));
Vue.component('vendor-manufacturers-card', require('./components/VendorManufacturersCard/VendorManufacturersCard'));

Vue.component('purchase-orders', require('./components/PurchaseOrders/Browse/Browse.vue'));
Vue.component('purchase-order-show', require('./components/PurchaseOrders/Show/Show.vue'));
Vue.component('purchase-order-items', require('./components/PurchaseOrders/Items/Browse/Browse.vue'));
Vue.component('new-purchase-order', require('./components/PurchaseOrders/NewPurchaseOrderModal/Modal.vue'));
Vue.component('edit-purchase-order-modal', require('./components/PurchaseOrders/EditPurchaseOrderModal/Modal.vue'));
// Vue.component('asset-issuances', require('./components/AssetIssuances/Browse/Browse.vue'));
// Vue.component('new-asset-issuance', require('./components/AssetIssuances/NewIssuanceModal/NewModal.vue'));
// Vue.component('asset-issuance-items', require('./components/AssetIssuances/Items/Browse/Browse.vue'));
// Vue.component('asset-issuance-show', require('./components/AssetIssuances/Show/Show.vue'));

Vue.component('purchase-order-item-new-modal', require('./components/PurchaseOrderItemNewModal/PurchaseOrdertemNewModal'));

// Asset returns.
// Vue.component('asset-issuances-returns', require('./components/AssetIssuancesReturns/Browse/Browse.vue'));
// Vue.component('new-asset-issuance-return', require('./components/AssetIssuancesReturns/NewIssuanceReturnModal/NewModal.vue'));
// Vue.component('asset-issuance-return-show', require('./components/AssetIssuancesReturns/Show/Show.vue'));

// Vue.component('asset-management', require('./components/AssetManagement/Browse/Browse.vue'));
// Vue.component('asset-requiring-repair', require('./components/AssetsRequiringRepair/Browse/Browse.vue'));
// Vue.component('repair-return-wizard', require('./components/AssetsRequiringRepair/Wizard/Wizard.vue'));
// Vue.component('return-from-repair-wizard', require('./components/AssetsRequiringRepair/ReturnWizard/ReturnWizard.vue'));
// Vue.component('asset-issuances-reports', require('./components/AssetIssuancesReports/Browse/Browse.vue'));
// Vue.component('asset-stock-reports', require('./components/AssetStockReports/Browse/Browse.vue'));
// Vue.component('verify-mobile-number', require('./components/VerifyMobileNumber/VerifyMobileNumber.vue'));
// Vue.component('create-asset-issuance-item', require('./components/AssetIssuances/Items/Create/Create.vue'));
// Vue.component('return-confirmation-index', require('./components/ReturnConfirmation/Index/Index.vue'));
// Vue.component('return-confirmation-show', require('./components/ReturnConfirmation/Show/Show.vue'));
// Vue.component('user-activities', require('./components/UserActivities/Browse/UserActivityBrowse.vue'));
// Vue.component('export-report', require('./components/ExportSelectedColumns/ExportSelectedColumns.vue'));
Vue.component('simple-search-edit', require('./components/SimpleSearchEdit/SimpleSearchEdit.vue'));
Vue.component('simple-search-return-id', require('./components/SimpleSearch/ReturnId/ReturnId.vue'));
// Vue.component('asset-template-create', require('./components/AssetTemplates/Create/Create.vue'));
Vue.component('simple-search-show', require('./components/SimpleSearchShow/SimpleSearchShow.vue'));
// Vue.component('asset-issuance-item-revamp', require('./components/AssetIssuances/Items/CreateRevamp/Create.vue'));
// Vue.component('asset-templates-status-barchart', require('./components/AssetTemplates/StatusBarchart/Graph.vue'));
Vue.component('new-resource-sidebar', require('./components/Sidebar/NewResourceSidebar/NewResourceSidebar.vue'));
Vue.component('simple-search', require('./components/SimpleSearch/ReturnIdRevamp/ReturnIdRevamp.vue'));
Vue.component('loading-screen', require('./components/LoadingAnimations/SvgBrand/SvgBrand.vue'));
// Vue.component('asset-show', require('./components/Assets/show/show.vue'));
Vue.component('settings-sidebar-menu', require('./components/Sidebar/SettingsSidebarMenu/Settings.vue'));
// Vue.component('new-issuance', require('./components/AssetIssuances/NewIssuanceModal/NewModal.vue'));
// Vue.component('new-issuance-return', require('./components/AssetIssuancesReturns/NewIssuanceReturnModal/NewModal.vue'));
// Vue.component('location-codes-index', require('./components/LocationCodes/Index.vue'));
// Vue.component('location-codes-show', require('./components/Locations/Show/Show.vue'));
Vue.component('department-groups-index', require('./components/DepartmentGroups/Index/Index'));
// Vue.component('asset-template-show', require('./components/AssetTemplates/Show/Show'));
// Vue.component('reports-page', require('./components/Reports/Index.vue'));

// Vue.component('manage-lists', require('./components/ManageLists/Manage.vue'));
// Vue.component('manage-configurations-lists', require('./components/ManageConfigurationsLists/Manage.vue'));

Vue.component('personal-user-list', require('./components/PersonalUserList/List.vue'));

// Vue.component('system-preferences', require('./components/SystemPreferences/Browse/Browse.vue'));

Vue.component('file-upload-button', require('./components/UploadFile/Button.vue'));
Vue.component('view-all-files-button', require('./components/UploadFile/ViewAllButton.vue'));

/**
 * Search components.
 */
Vue.component('advanced-search', require('./components/AdvancedSearch/AdvancedSearch.vue'));
Vue.component('employees-results', require('./components/Employees/DropdownPaginatedSearchResult/Result.vue'));
Vue.component('purchase-orders-results', require('./components/PurchaseOrders/DropdownPaginatedSearchResult/Result.vue'));
// Vue.component('asset-issuances-results', require('./components/AssetIssuances/DropdownPaginatedSearchResult/Result.vue'));
// Vue.component('asset-issuances-returns-results', require('./components/AssetIssuancesReturns/DropdownPaginatedSearchResult/Result.vue'));

/**
 * Sidebar components.
 */
Vue.component('sidebar-links-group', require('./components/Sidebar/SidebarLinksGroup/Group.vue'));
Vue.component('sidebar-sub-link', require('./components/Sidebar/SidebarLinksGroup/SubGroupLink.vue'));

Vue.component('signature-upload-file', require('./components/SignatureUploadFile/SignatureUploadFile.vue'));
Vue.component('download-media-button', require('./components/DownloadMediaButton/DownloadMediaButton.vue'));

Vue.component('inbox-navbar-button', require('./components/InboxNavbar/InboxNavbarButton.vue'));
Vue.component('inbox-navbar-item', require('./components/InboxNavbar/InboxItem.vue'));

// Vue.component('stock-status-report-page', require('./components/Reports/Stock/StatusPage'));
// Vue.component('stock-item-fuse-search', require('./components/StockItemFuseSearch/StockItemFuseSearch.vue'));
// Vue.component('build-stock-by-item-report', require('./components/StockByItemReport/StockByItemReport.vue'));

Vue.component('item-template-form', require('./components/ItemTemplateForm/ItemTemplateForm'));

Vue.component('address-field-token', require('./components/AddressFieldToken/AddressFieldToken'));
Vue.component('choose-address-modal', require('./components/AddressFieldToken/ChooseAddressModal'));

Vue.component('icon', require('./components/Brand/icon.vue'));

// Login dipper.
Vue.component('login-dipper', require('./../../../packages/spacelantern/login-dipper/src/login-dipper.vue'));

Vue.component('attachments', require('./components/Attachments/Attachments.vue'));
Vue.component('scan-documents', require('./components/ScanDocuments/ScanDocuments.vue'));

Vue.component('audit-trail', require('./components/AuditTrail/AuditTrail.vue'));

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

Vue.component('create-request-form', require('./components/CreateRequestForm/CreateRequestForm.vue'));
Vue.component('request-container', require('./components/Requests/RequestContainer.vue'));
Vue.component('request-header', require('./components/Requests/RequestHeader.vue'));
Vue.component('resource-header', require('./components/ResourceHeader/ResourceHeader.vue'));
Vue.component('request-items', require('./components/RequestItems/RequestItems.vue'));
Vue.component('request-item-new-modal', require('./components/RequestItemNewModal/RequestItemNewModal.vue'));
Vue.component('approve-confirmation', require('./components/ApproveConfirmation/ApproveConfirmation.vue'));
Vue.component('autocomplete-select', require('./components/AutocompleteSelect/AutocompleteSelect.vue'));
Vue.component('datepicker', require('./components/Datepicker/Datepicker.vue'));
Vue.component('clipboard', require('./components/CopyToClipboard/CopyToClipboard'));
Vue.component('select-employee', require('./components/SelectEmployee/SelectEmployee'));
Vue.component('select-department', require('./components/SelectDepartment/SelectDepartment'));
Vue.component('purchase-requisition-items', require('./components/PurchaseRequisitionItems/PurchaseRequisitionItems'));
Vue.component('new-item-requisition-modal', require('./components/NewItemRequisitionModal/NewItemRequisitionModal'));
Vue.component('select-item-template', require('./components/SelectItemTemplate/SelectItemTemplate'));
Vue.component('delete-prompt', require('./components/DeletePrompt/DeletePrompt'));
Vue.component('notes-container', require('./components/NotesContainer/NotesContainer'));
Vue.component('uploads-container', require('./components/UploadsContainer/UploadsContainer'));
Vue.component('new-upload-modal', require('./components/NewUploadFile/NewUploadFile'))
Vue.component('preview-pdf', require('./components/PreviewPdf/PreviewPdf'));
Vue.component('toggle-preview-requisition', require('./components/TogglePreviewRequisition/TogglePreviewRequisition'));
Vue.component('create-approver-page', require('./components/CreateApproverPage/CreateApproverPage'));
Vue.component('delete-dialog', require('./components/DeleteDialog/DeleteDialog'));
Vue.component('approve-requisition', require('./components/ApproveRequisition/ApproveRequisition'));
Vue.component('approve-requisition-modal', require('./components/ApproveRequisitionModal/ApproveRequisitionModal'));
Vue.component('edit-requisition-remarks', require('./components/EditRequisitionRemarks/EditRequsitionRemarks'));
Vue.component('pr-item-to-po-modal', require('./components/PrItemToPoModal/PrItemToPoModal'));
Vue.component('select-purchase-orders', require('./components/SelectPurchaseOrder/SelectPurchaseOrder'));
Vue.component('select-vendors', require('./components/SelectVendors/SelectVendors'));
Vue.component('select-address', require('./components/SelectAddress/SelectAddress'));
Vue.component('datetime-token', require('./components/DatetimeToken/DatetimeToken'));
Vue.component('edit-supplier-token', require('./components/EditSupplierToken/EditSupplierToken'));
Vue.component('delivery-date-token', require('./components/DeliveryDateToken/DeliveryDateToken'));
Vue.component('toggle-purchase-term', require('./components/TogglePurchaseTerm/TogglePurchaseTerm'));
Vue.component('new-po-item-from-pr-button', require('./components/NewPoItemFromPrButton/NewPoItemFromPrButton'));
Vue.component('new-po-item-modal', require('./components/NewPoItemModal/NewPoItemModal'));
Vue.component('select-purchase-requisition', require('./components/SelectPurchaseRequsition/SelectPurchaseRequisition'));
Vue.component('purchase-order-items', require('./components/PurchaseOrderItems/PurchaseOrderItems'));
Vue.component('toggle-preview-purchase-order', require('./components/TogglePreviewPurchaseOrder/TogglePreviewPurchaseOrder'));
Vue.component('preview-pdf-container', require('./components/PreviewPdfContainer/PreviewPdfContainer'));
Vue.component('purchase-requisition-simple-items', require('./components/PurchaseRequisitionSimpleItems/PurchaseRequisitionSimpleItems'));
Vue.component('edit-pr-recommended-by-token', require('./components/EditPrRecommendedByToken/EditPrRecommendedByToken'));
Vue.component('edit-pr-itam-approved-by-token', require('./components/EditPrItamApprovedByToken/EditPrItamApprovedByToken'));
Vue.component('edit-pr-financial-approved-by-token', require('./components/EditPrFinancialApprovedByToken/EditPrFinancialApprovedByToken'));
Vue.component('edit-pr-checked-by-token', require('./components/EditPrCheckedByToken/EditPrCheckedByToken'));

Vue.component('other-terms-token', require('./components/OtherTermsToken/OtherTermsToken'));

Vue.component('edit-employee-token', require('./components/EditEmployeeToken/EditEmployeeToken'));
Vue.component('edit-department-token', require('./components/EditDepartmentToken/EditDepartmentToken'));

Vue.component('toggle-default-purchase-term', require('./components/ToggleDefaultPurchaseTerm/ToggleDefaultPurchaseTerm'));

/**
 * API/App settings
 */
const apiVersion = '1';

// if(process.env.NODE_ENV === 'production') {
     var envUrl = window.location.origin + '/PMS';
// } else {
//    var envUrl = window.location.origin;
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

            console.log({
                message: '[Root] CollapsedMenubar cookie is already initialized',
                raw: this.$cookie.get('collapsed-menubar'),
                cookie: menubarCookie
            })

        } else {
            this.$cookie.set('collapsed-menubar', false);
            console.log('[Root] CollapsedMenubar cookie was set to false on mount');
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
