<?php

Route::post('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('/', function() { return redirect()->route('login'); });

Route::prefix(Localization::setLocale())->middleware(['localeSessionRedirect', 'localizationRedirect', 'localeViewPath'])->group(function() {

    Route::get('accept/{token}', 'InvitesController@accept')->name('invite.accept');
    Route::post('accept', 'InvitesController@processAccept')->name('invite.process-accept');

	Route::middleware(['auth'])->group(function() {

        Route::get('/', 'DashboardController@index')->name('dashboard.index');

        Route::name('profile.')->prefix('profile')->group(function() {
            Route::name('inbox.')->prefix('inbox')->group(function() {
                Route::get('messages/{id}', 'Front\MessagesController@show')->name('messages.show');
            });
        });

        // Invitation System.
        Route::get('invite', 'InvitesController@index')->name('invite');
        Route::delete('invite/{id}', 'InvitesController@delete')->name('invite.destroy');
        Route::post('invite', 'InvitesController@process')->name('invite.process');

        Route::get('users/invite', 'Back\UsersController@invite')->name('users.invite');

        // Purchase Requisitions.
        Route::get('purchase-requisitions/{id}/pdf', 'PurchaseRequisitionsController@pdf')->name('purchase-requisitions.pdf');
        Route::post('purchase-requisitions/{id}/save', 'PurchaseRequisitionsController@save')->name('purchase-requisitions.save');
        Route::get('purchase-requisitions/by-status/{status_slug}', 'PurchaseRequisitionsController@paginatedByStatus')->name('purchase-requisitions.by-status');
        Route::resource('purchase-requisitions', 'PurchaseRequisitionsController');

        // Items.
        Route::get('items/browse', 'ItemController@browse')->name('items.browse');
        Route::resource('items', 'ItemController');

        // Purchase orders
        Route::resource('purchase-orders', 'PurchaseOrderController');
        Route::name('purchase-orders.')->prefix('purchase-orders')->group(function() {
            Route::get('draft', 'PurchaseOrderController@draft')->name('draft');
            Route::get('committed', 'PurchaseOrderController@committed')->name('committed');
            Route::get('void', 'PurchaseOrderController@void')->name('void');
        });

        // Purchase Order Sub-PO
        Route::get('purchase-orders/{purchase_order_id}/sub-po/create', 'Back\PurchaseOrdersSubController@create')->name('purchase-orders.sub.create');

        // Employees.
        Route::resource('employees', 'EmployeeController', ['except' => ['create', 'store', 'delete']]);

        // Departments.
        Route::resource('departments', 'DepartmentController');

        // Manufacturers.
        Route::get('manufacturers/all', 'ManufacturerController@all')->name('manufacturers.all');
        Route::resource('manufacturers', 'ManufacturerController');

        // Vendors.
        Route::get('vendors/all', 'VendorController@all')->name('vendors.all');
        Route::resource('vendors', 'VendorController');
        Route::resource('vendor/{vendor_id}/vendor-representatives', 'VendorRepresentativesController', [
            'except' => ['index'],
        ]);

        // Vendors bank.
        Route::resource('vendor/{vendor_id}/vendor-bank', 'VendorBankController', [
            'except' => ['index'],
        ]);

        // Items.
        Route::resource('item-templates', 'ItemTemplateController');

        // Roles.
        Route::resource('roles', 'RoleController');

        // Users.
        Route::name('users.index')->get('users', 'Back\UsersController@index');
        Route::name('users.show')->get('users/{id}', 'Back\UsersController@show');

        Route::get('approvers', 'ApproversController@index')->name('approvers.index');
        Route::get('approvers/create', 'ApproversController@create')->name('approvers.create');
        Route::get('approvers/{id}', 'ApproversController@show')->name('approvers.show');
        Route::get('approvers/{id}/edit', 'ApproversController@edit')->name('approvers.edit');
        Route::put('approvers/{id}', 'ApproversController@update')->name('approvers.update');

        // Addresses.
        Route::prefix('settings')->group(function() {
            Route::resource('addresses', 'AddressesController');
        });

        Route::get('search', 'SearchController@index')->name('search.index');
	});


	/**
	 * Authentication routes
	 */
	Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
	Route::post('login', 'Auth\LoginController@login');

	 Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
	 Route::post('register', 'Auth\RegisterController@register');

	// Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkPurchase RequisitionForm')->name('password.request');
	// Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
	// Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
	// Route::post('password/reset', 'Auth\ResetPasswordController@reset');
});


Route::prefix('api/v' . env('APP_API', '1'))->middleware('auth')->group(function() {

    //Route::get('/', function() {
    //    $response = ['message' => 'Hello! - API version ' . env('APP_API', '1')];
    //    return response()->json($response, 200);
    //});

    Route::post('audit/show', 'Api\AuditsController@show');

    Route::post('upload/store', 'Api\MediaController@store');
    Route::post('media/download', 'Api\MediaController@download');
    Route::delete('media/{id}', 'Api\MediaController@delete');

    Route::prefix('profile')->group(function() {
        Route::get('inbox', 'Api\ProfileController@inbox');
        Route::get('inbox/get-unread-messages-counts', 'Api\ProfileController@unreadMessagesCounts');
        Route::get('inbox/clear-unread-messages-counts', 'Api\ProfileController@clearUnreadMessagesCounts');
    });

    // Roles.
    Route::post('roles/attach-permission', 'Api\RoleController@attachPermission');
    Route::post('roles/detach-permission', 'Api\RoleController@detachPermission');
    Route::post('users/attach-role', 'Back\UsersController@attachRole');
    Route::post('users/detach-role', 'Back\UsersController@detachRole');

    // Permissions.
    Route::get('permissions-list', 'Api\PermissionsController@list');

    // Departments.
    Route::get('departments', 'Api\DepartmentController@index');
    Route::post('departments/show', 'Api\DepartmentController@show');

    // Employees.
    Route::get('employees/paginated/{per_page}', 'Api\EmployeeController@paginatedIndex');
    Route::get('employees', 'Api\EmployeeController@index');
    Route::post('employees/show', 'Api\EmployeeController@show');
    Route::post('employees/store', 'Api\EmployeeController@store');

    Route::get('employees/types', 'Api\EmployeeController@staffTypes');

    // Approvers.
    Route::post('approvers', 'Api\ApproversController@store')->name('api.approvers.store');
    Route::delete('approvers/{id}', 'Api\ApproversController@delete')->name('api.approvers.destroy');

    // Manufacturers.
    Route::get('manufacturers/paginated/{per_page}', 'Api\ManufacturerController@paginatedIndex');
    Route::get('manufacturers', 'Api\ManufacturerController@index');
    Route::post('manufacturers/store', 'Api\ManufacturerController@store');

    // Vendors.
    Route::get('vendors/paginated/{per_page}', 'Api\VendorController@paginatedIndex');
    Route::get('vendors/{id}', 'Api\VendorController@show');
    Route::post('vendors/{id}/update-associated-manufacturers', 'Api\VendorController@updateAssociatedManufacturers');
    Route::get('vendors', 'Api\VendorController@index');
    Route::post('vendors/store', 'Api\VendorController@store');

    // PO
    Route::put('purchase-orders/{id}/update', 'Api\PurchaseOrderController@update');
    Route::post('purchase-orders/show', 'Api\PurchaseOrderController@show');
    Route::post('purchase-orders', 'Api\PurchaseOrderController@store');
    Route::get('purchase-orders/paginated/{per_page}', 'Api\PurchaseOrderController@paginatedIndex');
    Route::get('purchase-orders', 'Api\PurchaseOrderController@index');
    Route::post('purchase-orders/commit', 'Api\PurchaseOrderController@commit');
    Route::post('purchase-orders/void', 'Api\PurchaseOrderController@void');
    Route::post('purchase-orders/attachments', 'Api\PurchaseOrderController@attachments');
    Route::post('purchase-orders/download-attachment', 'Api\PurchaseOrderController@downloadAttachment');

    // PO Items
    Route::post('purchase-orders/items/partial-edit', 'Api\PurchaseOrderItemController@partialUpdate');
    Route::post('purchase-orders/{purchase_order_id}/items', 'Api\PurchaseOrderItemController@store');
    Route::get('procedures/purchase-orders/{purchase_order_id}/items', 'Api\PurchaseOrderItemController@index');
    Route::delete('procedures/purchase-orders/{purchase_order_id}/items/{item_id}/delete', 'Api\PurchaseOrderItemController@delete');
    Route::post('procedures/purchase-orders/{purchase_order_id}/items/{item_id}/received', 'Api\PurchaseOrderItemController@attemptToReceiveItem');

    // PO Service Items
    Route::post('purchase-orders/service-items', 'Api\PurchaseOrderItemController@showServicesItems');
    Route::post('purchase-orders/service-items/receive', 'Api\PurchaseOrderItemController@receiveServiceItem');

    Route::post('purchase-orders/service-items/store', 'Api\PurchaseOrderItemController@storeServiceLine');
    Route::delete('purchase-orders/service-items/delete', 'Api\PurchaseOrderItemController@deleteServiceLine');

    // Purchase Requisitions.
    Route::post('purchase-requisitions/{id}/send-to-purchasing', 'Api\PurchaseRequisitionsController@sendToPurchasing')->name('api-purchase-requisitions.send-to-purchasing');
    Route::get('purchase-requisitions/{id}', 'Api\PurchaseRequisitionsController@show');
    Route::post('purchase-requisitions', 'Api\PurchaseRequisitionsController@store')->name('api.purchase-requisitions.store');
    Route::post('purchase-requisitions/{id}/subscribe', 'Api\PurchaseRequisitionsController@subscribe')->name('api.purchase-requisitions.subscribe');
    Route::post('purchase-requisitions/{id}/unsubscribe', 'Api\PurchaseRequisitionsController@unsubscribe')->name('api.purchase-requisitions.unsubscribe');
    Route::post('purchase-requisitions/{id}/approve', 'Api\PurchaseRequisitionsController@approve')->name('api.purchase-requisitions.approve');
    Route::put('purchase-requisitions/{id}/purpose', 'Api\PurchaseRequisitionsController@updatePurpose')->name('api.purchase-requisitions.purpose');

    // Notes.
    Route::post('purchase-requisition/notes', 'Api\PurchaseRequisitionNotes@store')->name('api.purchase-requisition.notes');
    Route::get('purchase-requisition/notes', 'Api\PurchaseRequisitionNotes@index')->name('api.purchase-requisition.notes');

    // Purchase Requisition uploads.
    Route::post('purchase-requisition/uploads', 'Api\PurchaseRequisitionUploads@store')->name('api.purchase-requisition.uploads');
    Route::get('purchase-requisition/uploads', 'Api\PurchaseRequisitionUploads@index')->name('api.purchase-requisition.uploads');
    Route::get('purchase-requisition/uploads/{id}/download', 'Api\PurchaseRequisitionUploads@download')->name('api.purchase-requisition.download');

    // Purchase Requisition items.
    Route::get('purchase-requisitions/{purchase_requisition_id}/items', 'Api\PurchaseRequisitionItemsController@underRequisition')->name('purchase-requisitions.items');
    Route::post('purchase-requisitions/{purchase_requisition_id}/items', 'Api\PurchaseRequisitionItemsController@store')->name('purchase-requisitions.items');
    Route::delete('purchase-requisitions/{purchase_requisition_id}/items/{id}', 'Api\PurchaseRequisitionItemsController@delete')->name('purchase-requisitions.delete');

    Route::post('purchase-requisition-items', 'Api\PurchaseRequisitionItemsController@store')->name('api.purchase-requisitions.store');
    Route::delete('purchase-requisition-items/{id}', 'Api\PurchaseRequisitionItemsController@delete')->name('api.purchase-requisitions.delete');

    // Items.
    Route::post('items', 'Api\ItemsController@store')->name('items.store');

    Route::prefix('search')->group(function() {
        Route::get('items', 'Api\ItemController@search');
        Route::get('item-templates', 'Api\ItemTemplateController@search')->name('api.search.item-templates');
        Route::get('purchase-orders', 'Api\PurchaseOrderController@search');
        Route::get('vendors', 'Api\VendorController@search')->name('api.search.vendor');
        Route::get('manufacturers', 'Api\ManufacturerController@search')->name('api.search.manufacturer');
        Route::get('employees', 'Api\EmployeeController@search')->name('api.search.employee');
        Route::get('approvers/create', 'Api\ApproversController@searchWithoutApproverInformation');
        Route::get('approvers', 'Api\ApproversController@search')->name('api.search.approvers');
        Route::get('departments', 'Api\DepartmentController@search')->name('api.search.department');
        Route::get('shipping-addresses', 'Api\AddressesController@searchShippingAddresses')->name('api.search.shipping-addresses');
        Route::get('billing-addresses', 'Api\AddressesController@searchBillingAddresses')->name('api.search.billing-addresses');
    });
});
