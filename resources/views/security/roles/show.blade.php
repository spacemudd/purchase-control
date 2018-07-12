@extends('layouts.app', [
	'title' => $role->name . ' - ' . trans('words.role')
])

@section('header')
	<nav class="breadcrumb" aria-label="breadcrumbs">
		<ul>
			<li>
				<a href="{{ route('dashboard.index') }}" aria-current="page">
					<span class="icon is-small"><i class="fa fa-inbox"></i></span>
					<span>{{ trans('words.dashboard') }}</span>
				</a>
			</li>
			<li>
				<a href="{{ route('roles.index'	) }}">
					<span class="icon is-small"><i class="fa fa-user-circle-o"></i></span>
					<span>{{ trans('words.roles') }}</span>
				</a>
			</li>
			<li class="is-active">
				<a href="#">
					{{ $role->name }}
				</a>
			</li>
		</ul>
	</nav>
@endsection

@section('content')
	@if(session()->has('message'))
		@component('components.alerts.basic', ['status' => (session()->has('status') == null) ? 'default' : session()->get('status')])
			{{ session()->get('message') }}
		@endcomponent
	@endif

	<div class="columns">
		<div class="column">

			<p>Assign permissions to the role <strong>{{ $role->name }}</strong>.</p>
		</div>
	</div>

	<div class="columns is-multiline">
		<div class="column is-12">
			<div class="panel">
				<p class="panel-heading">
					Roles
				</p>
				<div class="panel-block">
					<toggle-permission :role-id.number="{{ $role->id }}"
									   :enabled-prop.number="{{ $role->hasPermissionTo('new-role') ? 'true' : 'false' }}"
									   permission-name="new-role">
						New Role
					</toggle-permission>
				</div>

				<div class="panel-block">
					<toggle-permission :role-id.number="{{ $role->id }}"
									   :enabled-prop.number="{{ $role->hasPermissionTo('edit-roles') ? 'true' : 'false' }}"
									   permission-name="edit-roles">
						Edit Roles
					</toggle-permission>
				</div>

				<div class="panel-block">
					<toggle-permission :role-id.number="{{ $role->id }}"
									   :enabled-prop.number="{{ $role->hasPermissionTo('show-users') ? 'true' : 'false' }}"
									   permission-name="show-users">
						Show Users <span class="help">Allow user to see all users registered.</span>
					</toggle-permission>
				</div>

				<div class="panel-block">
					<toggle-permission :role-id.number="{{ $role->id }}"
									   :enabled-prop.number="{{ $role->hasPermissionTo('edit-user-roles') ? 'true' : 'false' }}"
									   permission-name="edit-user-roles">
						Edit User Roles <span class="help">Allow user to edit another user's roles</span>
					</toggle-permission>
				</div>

				<div class="panel-block">
					<toggle-permission :role-id.number="{{ $role->id }}"
									   :enabled-prop.number="{{ $role->hasPermissionTo('invite-users') ? 'true' : 'false' }}"
									   permission-name="invite-users">
						Invite User
					</toggle-permission>
				</div>
			</div>
		</div>

		<div class="column is-12">
			<div class="panel">
				<p class="panel-heading">
					Vendors
				</p>
				<div class="panel-block">
					<toggle-permission :role-id.number="{{ $role->id }}"
									   :enabled-prop.number="{{ $role->hasPermissionTo('create-vendor') ? 'true' : 'false' }}"
									   permission-name="create-vendor">
						Create Vendor
					</toggle-permission>
				</div>
				<div class="panel-block">
					<toggle-permission :role-id.number="{{ $role->id }}"
									   :enabled-prop.number="{{ $role->hasPermissionTo('view-vendor') ? 'true' : 'false' }}"
									   permission-name="view-vendor">
						View Vendor
					</toggle-permission>
				</div>
				<div class="panel-block">
					<toggle-permission :role-id.number="{{ $role->id }}"
									   :enabled-prop.number="{{ $role->hasPermissionTo('update-vendor') ? 'true' : 'false' }}"
									   permission-name="update-vendor">
						Update Vendor
					</toggle-permission>
				</div>
				<div class="panel-block">
					<toggle-permission :role-id.number="{{ $role->id }}"
									   :enabled-prop.number="{{ $role->hasPermissionTo('delete-vendor') ? 'true' : 'false' }}"
									   permission-name="delete-vendor">
						Delete Vendor
					</toggle-permission>
				</div>
				<div class="panel-block">
					<toggle-permission :role-id.number="{{ $role->id }}"
									   :enabled-prop.number="{{ $role->hasPermissionTo('create-vendor-representative') ? 'true' : 'false' }}"
									   permission-name="create-vendor-representative">
						Create vendor representative
					</toggle-permission>
				</div>
				<div class="panel-block">
					<toggle-permission :role-id.number="{{ $role->id }}"
									   :enabled-prop.number="{{ $role->hasPermissionTo('create-vendor-bank') ? 'true' : 'false' }}"
									   permission-name="create-vendor-bank">
						Create vendor bank
					</toggle-permission>
				</div>
				<div class="panel-block">
					<toggle-permission :role-id.number="{{ $role->id }}"
									   :enabled-prop.number="{{ $role->hasPermissionTo('delete-vendor-bank') ? 'true' : 'false' }}"
									   permission-name="delete-vendor-bank">
						Delete vendor bank
					</toggle-permission>
				</div>
			</div>
		</div>

		<div class="column is-12">
			<div class="panel">
				<p class="panel-heading">
					Manufacturers
				</p>
				<div class="panel-block">
					<toggle-permission :role-id.number="{{ $role->id }}"
									   :enabled-prop.number="{{ $role->hasPermissionTo('view-manufacturers') ? 'true' : 'false' }}"
									   permission-name="view-manufacturers">
						View manufacturers
					</toggle-permission>
				</div>
				<div class="panel-block">
					<toggle-permission :role-id.number="{{ $role->id }}"
									   :enabled-prop.number="{{ $role->hasPermissionTo('create-manufacturers') ? 'true' : 'false' }}"
									   permission-name="create-manufacturers">
						Create manufacturer
					</toggle-permission>
				</div>
				<div class="panel-block">
					<toggle-permission :role-id.number="{{ $role->id }}"
									   :enabled-prop.number="{{ $role->hasPermissionTo('update-manufacturers') ? 'true' : 'false' }}"
									   permission-name="update-manufacturers">
						Update manufacturer
					</toggle-permission>
				</div>
				<div class="panel-block">
					<toggle-permission :role-id.number="{{ $role->id }}"
									   :enabled-prop.number="{{ $role->hasPermissionTo('delete-manufacturers') ? 'true' : 'false' }}"
									   permission-name="delete-manufacturers">
						Delete manufacturer
					</toggle-permission>
				</div>
			</div>
		</div>

		<div class="column is-12">
			<div class="panel">
				<p class="panel-heading">
					Categories
				</p>
				<div class="panel-block">
					<toggle-permission :role-id.number="{{ $role->id }}"
									   :enabled-prop.number="{{ $role->hasPermissionTo('view-categories') ? 'true' : 'false' }}"
									   permission-name="view-categories">
						View categories
					</toggle-permission>
				</div>
				<div class="panel-block">
					<toggle-permission :role-id.number="{{ $role->id }}"
									   :enabled-prop.number="{{ $role->hasPermissionTo('create-categories') ? 'true' : 'false' }}"
									   permission-name="create-categories">
						Create categories
					</toggle-permission>
				</div>
				<div class="panel-block">
					<toggle-permission :role-id.number="{{ $role->id }}"
									   :enabled-prop.number="{{ $role->hasPermissionTo('update-categories') ? 'true' : 'false' }}"
									   permission-name="update-categories">
						Update categories
					</toggle-permission>
				</div>
				<div class="panel-block">
					<toggle-permission :role-id.number="{{ $role->id }}"
									   :enabled-prop.number="{{ $role->hasPermissionTo('delete-categories') ? 'true' : 'false' }}"
									   permission-name="delete-categories">
						Delete categories
					</toggle-permission>
				</div>
			</div>
		</div>

		<div class="column is-12">
			<div class="panel">
				<p class="panel-heading">
					Item Catalog
				</p>
				<div class="panel-block">
					<toggle-permission :role-id.number="{{ $role->id }}"
									   :enabled-prop.number="{{ $role->hasPermissionTo('view-item-templates') ? 'true' : 'false' }}"
									   permission-name="view-item-templates">
						View Item Catalog
					</toggle-permission>
				</div>
				<div class="panel-block">
					<toggle-permission :role-id.number="{{ $role->id }}"
									   :enabled-prop.number="{{ $role->hasPermissionTo('create-item-templates') ? 'true' : 'false' }}"
									   permission-name="create-item-templates">
						Create Item Catalog
					</toggle-permission>
				</div>
				<div class="panel-block">
					<toggle-permission :role-id.number="{{ $role->id }}"
									   :enabled-prop.number="{{ $role->hasPermissionTo('update-item-templates') ? 'true' : 'false' }}"
									   permission-name="update-item-templates">
						Update Item Catalog
					</toggle-permission>
				</div>
				<div class="panel-block">
					<toggle-permission :role-id.number="{{ $role->id }}"
									   :enabled-prop.number="{{ $role->hasPermissionTo('delete-item-templates') ? 'true' : 'false' }}"
									   permission-name="delete-item-templates">
						Delete Item Catalog
					</toggle-permission>
				</div>
			</div>
		</div>

		<div class="column is-12">
			<div class="panel">
				<p class="panel-heading">
					Departments
				</p>
				<div class="panel-block">
					<toggle-permission :role-id.number="{{ $role->id }}"
									   :enabled-prop.number="{{ $role->hasPermissionTo('view-departments') ? 'true' : 'false' }}"
									   permission-name="view-departments">
						View departments
					</toggle-permission>
				</div>
				<div class="panel-block">
					<toggle-permission :role-id.number="{{ $role->id }}"
									   :enabled-prop.number="{{ $role->hasPermissionTo('create-departments') ? 'true' : 'false' }}"
									   permission-name="create-departments">
						Create departments
					</toggle-permission>
				</div>
				<div class="panel-block">
					<toggle-permission :role-id.number="{{ $role->id }}"
									   :enabled-prop.number="{{ $role->hasPermissionTo('update-departments') ? 'true' : 'false' }}"
									   permission-name="update-departments">
						Update departments
					</toggle-permission>
				</div>
				<div class="panel-block">
					<toggle-permission :role-id.number="{{ $role->id }}"
									   :enabled-prop.number="{{ $role->hasPermissionTo('delete-departments') ? 'true' : 'false' }}"
									   permission-name="delete-departments">
						Delete departments
					</toggle-permission>
				</div>
			</div>
		</div>

		<div class="column is-12">
			<div class="panel">
				<p class="panel-heading">
					Employees
				</p>
				<div class="panel-block">
					<toggle-permission :role-id.number="{{ $role->id }}"
									   :enabled-prop.number="{{ $role->hasPermissionTo('view-employees') ? 'true' : 'false' }}"
									   permission-name="view-employees">
						View employees
					</toggle-permission>
				</div>
				<div class="panel-block">
					<toggle-permission :role-id.number="{{ $role->id }}"
									   :enabled-prop.number="{{ $role->hasPermissionTo('create-employees') ? 'true' : 'false' }}"
									   permission-name="create-employees">
						Create employees
					</toggle-permission>
				</div>
				<div class="panel-block">
					<toggle-permission :role-id.number="{{ $role->id }}"
									   :enabled-prop.number="{{ $role->hasPermissionTo('update-employees') ? 'true' : 'false' }}"
									   permission-name="update-employees">
						Update employees
					</toggle-permission>
				</div>
				<div class="panel-block">
					<toggle-permission :role-id.number="{{ $role->id }}"
									   :enabled-prop.number="{{ $role->hasPermissionTo('delete-employees') ? 'true' : 'false' }}"
									   permission-name="delete-employees">
						Delete employees
					</toggle-permission>
				</div>
			</div>
		</div>

		<div class="column is-12">
			<div class="panel">
				<p class="panel-heading">
					Sales Taxes
				</p>
				@foreach([
				    'view-sales-taxes',
				    'create-sales-taxes',
				] as $permission)
					<div class="panel-block">
						<toggle-permission :role-id.number="{{ $role->id }}"
										   :enabled-prop.number="{{ $role->hasPermissionTo($permission) ? 'true' : 'false' }}"
										   permission-name="{{ $permission }}">
							<span class="is-capitalized">{{ str_replace('-', ' ', $permission) }}</span>
						</toggle-permission>
					</div>
				@endforeach
			</div>
		</div>

		<div class="column is-12">
			<div class="panel">
				<p class="panel-heading">
					Purchase orders
				</p>
				@foreach([
				    'view-purchase-orders',
					'create-purchase-orders',
					'update-purchase-orders',
					'delete-purchase-orders',
					'approve-purchase-orders',
					'reject-purchase-orders',
					'upload-purchase-orders',
					'void-purchase-orders',
					'create-po-terms',
					'view-po-terms',
					'edit-po-terms',
					'delete-po-terms',
				] as $permission)
					<div class="panel-block">
						<toggle-permission :role-id.number="{{ $role->id }}"
										   :enabled-prop.number="{{ $role->hasPermissionTo($permission) ? 'true' : 'false' }}"
										   permission-name="{{ $permission }}">
							<span class="is-capitalized">{{ str_replace('-', ' ', $permission) }}</span>
						</toggle-permission>
					</div>
				@endforeach
			</div>
		</div>
	</div>

	{{--
	<div class="columns is-multiline">
		@foreach($permissionTypes as $type => $permissions)

			<div class="column is-12">
				<h1 class="title">{{ __('words.' . str_before($type, '.')) }}</h1>

				<section class="section">
					<div class="columns is-multiline">
						@foreach($permissions as $permission)
							<div class="column is-12">
								<toggle-permission :role-id.number="{{ $role->id }}"
												   :enabled-prop.number="{{ $role->hasPermissionTo($permission->name) ? 'true' : 'false' }}"
												   permission-name="{{ $permission->name }}">
									{{ __('words.' . $permission->name) }}
								</toggle-permission>
							</div>
						@endforeach
					</div>
				</section>

			</div>

		@endforeach
	</div>
	--}}

@endsection
