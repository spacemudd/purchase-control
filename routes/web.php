<?php

Route::post('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('/', function() { return redirect()->route('login'); });

Route::prefix(Localization::setLocale())->middleware(['localeSessionRedirect', 'localizationRedirect', 'localeViewPath'])->group(function() {

	Route::middleware(['auth'])->group(function() {

        Route::get('/', 'DashboardController@index')->name('dashboard.index');

        Route::name('profile.')->prefix('profile')->group(function() {
            Route::name('inbox.')->prefix('inbox')->group(function() {
                Route::get('messages/{id}', 'Front\MessagesController@show')->name('messages.show');
            });
        });

        // Requests.
        Route::get('requests/by-status/{status_slug}', 'RequestsController@paginatedByStatus')->name('requests.by-status');
        Route::resource('requests', 'RequestsController');

        // Items.
        Route::get('items/browse', 'ItemController@browse')->name('items.browse');
        Route::resource('items', 'ItemController');

        // Purchase orders
        Route::resource('purchase-orders', 'PurchaseOrderController');
        Route::name('purchase-orders.')->prefix('purchase-orders')->group(function() {
            Route::get('draft', 'PurchaseOrderController@draft')->name('draft');
            Route::get('committed', 'PurchaseOrderController@committed')->name('committed');
            Route::get('void', 'PurchaseOrderController@void')->name('purchase-orders.void');
        });

        // Purchase Order Sub-PO
        Route::get('purchase-orders/{purchase_order_id}/sub-po/create', 'Back\PurchaseOrdersSubController@create')->name('purchase-orders.sub.create');

        // Employees.
        Route::resource('employees', 'EmployeeController', ['except' => ['create', 'store', 'delete']]);

        // Departments.
        Route::resource('departments', 'DepartmentController');

        // Manufacturers.
        Route::resource('manufacturers', 'ManufacturerController');

        // Vendors.
        Route::resource('vendors', 'VendorController');
        Route::prefix('vendor/{vendor_id}')->resource('vendor-representatives', 'VendorRepresentativesController', [
            'except' => ['index'],
        ]);

        // Items.
        Route::resource('item-templates', 'ItemTemplateController');

        // Roles.
        Route::resource('roles', 'RoleController');

        // Users.
        Route::name('users.index')->get('users', 'Back\UsersController@index');
        Route::name('users.show')->get('users/{id}', 'Back\UsersController@show');

        Route::get('search', 'SearchController@index')->name('search.index');
	});


	/**
	 * Authentication routes
	 */
	Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
	Route::post('login', 'Auth\LoginController@login');

	 Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
	 Route::post('register', 'Auth\RegisterController@register');

	// Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
	// Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
	// Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
	// Route::post('password/reset', 'Auth\ResetPasswordController@reset');
});


Route::prefix('api/v' . env('APP_API', '1'))->middleware('auth')->group(function() {

    Route::get('/', function() {
        $response = ['message' => 'Hello! - API version ' . env('APP_API', '1')];
        return response()->json($response, 200);
    });

    Route::post('audit/show', 'Api\AuditsController@show');

    Route::post('upload/store', 'Api\MediaController@store');
    Route::post('media/download', 'Api\MediaController@download');

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

    // Manufacturers.
    Route::get('manufacturers/paginated/{per_page}', 'Api\ManufacturerController@paginatedIndex');
    Route::get('manufacturers', 'Api\ManufacturerController@index');
    Route::post('manufacturers/store', 'Api\ManufacturerController@store');

    // Vendors.
    Route::get('vendors/paginated/{per_page}', 'Api\VendorController@paginatedIndex');
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

    // Requests.
    Route::post('requests/{id}/approve', 'Api\RequestsController@approve');
    Route::get('requests/{id}', 'Api\RequestsController@show');
    Route::post('requests', 'Api\RequestsController@store')->name('api.requests.store');

    // Request items.
    Route::post('requests/{request_id}/items', 'Api\RequestItemController@store')->name('requests.items');
    Route::delete('requests/{request_id}/items/{id}', 'Api\RequestItemController@delete')->name('requests.delete');

    // Items.
    Route::post('items', 'Api\ItemsController@store')->name('items.store');

    Route::prefix('search')->group(function() {
        Route::get('items', 'Api\ItemController@search');
        Route::get('purchase-orders', 'Api\PurchaseOrderController@search');
        Route::get('vendors', 'Api\VendorController@search')->name('api.search.vendor');
        Route::get('manufacturers', 'Api\ManufacturerController@search')->name('api.search.manufacturer');
        Route::get('employees', 'Api\EmployeeController@search')->name('api.search.employee');
        Route::get('departments', 'Api\DepartmentController@search')->name('api.search.department');
    });
});
