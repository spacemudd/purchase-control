@extends('layouts.app', ['title' => trans('words.create') . ' ' . trans('words.purchase-order')])

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
				<a href="{{ route('purchase-orders.index') }}">
					<span class="icon is-small"><i class="fa fa-shopping-cart"></i></span>
					<span>{{ trans('words.purchase-orders') }}</span>
				</a>
			</li>
			<li class="is-active">
				<a href="#">{{ trans('words.new-purchase-order') }}</a>
			</li>
		</ul>
	</nav>
@endsection

@section('content')
	<form action="{{ route('purchase-orders.store') }}" method="post">
		{{ csrf_field() }}
		{{-- Currency --}}
		<div class="columns">
			<div class="column is-3">
				<p class="title is-5">Purchase Currency</p>
			</div>
			<div class="column is-4">
				<div class="field">
					<label for="currency" class="label">Currency</label>

					<div class="control">
						<div class="select is-fullwidth">
							<select tabindex="0" name="currency" class="{{ $errors->has('currency') ? ' is-danger' : '' }}" autofocus>
								<option value=""></option>
								@foreach($currencies as $currencyCode => $description)
									<option value="{{ $currencyCode }}"{{ $currencyCode === 'SAR' ? ' selected' : '' }}>{{ $currencyCode }} - {{ $description }}</option>
								@endforeach
							</select>
						</div>

						@if ($errors->has('currency'))
							<span class="help is-danger">
								{{ $errors->first('currency') }}
							</span>
						@endif
					</div>
				</div>
				<hr>
			</div>
		</div>

		{{-- Requested for --}}
		<div class="columns">
			<div class="column is-3"><p class="title is-5">Request Information</p></div>
			<div class="column is-4">
				<div class="field">
					<label for="supplier_id" class="label">Employee (Requested by)</label>

					<div class="control">
						<select-employee name="requested_by_employee_id"
										 url="{{ route('api.search.employee') }}">
						</select-employee>
						<button type="button" class="is-small button is-text" @click="$store.commit('Employee/setNewEmployeeModal', true)">
							New
						</button>
					</div>
				</div>
				<div class="field">
					<label for="supplier_id" class="label">Employee (Requested for)</label>

					<div class="control">
						<select-employee name="requested_for_employee_id"
										 url="{{ route('api.search.employee') }}"
										 tabindex="1"
						>
						</select-employee>
						<button type="button" class="is-small button is-text" @click="$store.commit('Employee/setNewEmployeeModal', true)">
							New
						</button>
					</div>
				</div>
				<div class="field">
					<label for="supplier_id" class="label">Charged Cost Center</label>

					<div class="control">9
						<select-department name="cost_center_id"
										   url="{{ route('api.search.department') }}">
						</select-department>
						<p class="help">Search by department code or name</p>
						<button type="button" class="is-small button is-text" @click="$store.commit('Department/showNewModal', true)">
							New
						</button>
					</div>
				</div>
				<hr>
			</div>
		</div>

		{{-- Suppliers --}}
		<div class="columns">
			<div class="column is-3">
				<p class="title is-5">Supplier</p>
			</div>
			<div class="column is-4">
				<div class="field">
					<label for="supplier_id" class="label">Supplier</label>

					<div class="control">
						<select-vendors name="vendor_id"
										url="{{ route('api.search.vendor') }}">
						</select-vendors>

						<span class="help">Search by code or name</span>

						<button type="button" class="is-small button is-text" @click="$store.commit('Vendor/setNewModal', true)">
							New
						</button>

						@if ($errors->has('supplier_id'))
							<span class="help is-danger">
								{{ $errors->first('supplier_id') }}
							</span>
						@endif
					</div>
				</div>
			</div>
		</div>

		{{-- Project --}}
		<div class="columns">
			<div class="column is-3">
				<p class="title is-5">Project</p>
			</div>
			<div class="column is-4">
				<div class="field">
					<label for="project_id" class="label">Project Code</label>

					<div class="control">
						<select-projects name="project_id"
										url="{{ route('api.search.projects') }}">
						</select-projects>

						<button type="button" class="is-small button is-text" @click="$store.commit('Project/setNewModal', true)">
							New
						</button>

						@if ($errors->has('project_id'))
							<span class="help is-danger">
								{{ $errors->first('project_id') }}
							</span>
						@endif
					</div>
				</div>
			</div>
		</div>

		 {{--Shipping Address --}}
		{{--
		<div class="columns">
			<div class="column is-3">
				<p class="title is-5">Shipping Address</p>
			</div>
			<div class="column is-4">
				<div class="field">
					<label for="shipping_address_id" class="label">Shipping Address</label>

					<p class="control">
						<select-address name="shipping_address_id"
										url="{{ route('api.search.shipping-addresses') }}">
						</select-address>

						@if ($errors->has('shipping_address_id'))
							<span class="help is-danger">
								{{ $errors->first('shipping_address_id') }}
							</span>
						@endif
					</p>
				</div>
				<hr>
			</div>
		</div>
		--}}

		{{--Billing Address --}}
		{{--
		<div class="columns">
			<div class="column is-3">
				<p class="title is-5">Billing Address</p>
			</div>
			<div class="column is-4">
				<div class="field">
					<label for="billing_address_id" class="label">Billing Address</label>

					<p class="control">
						<select-address name="billing_address_id"
										url="{{ route('api.search.billing-addresses') }}">
						</select-address>

						@if ($errors->has('billing_address_id'))
							<span class="help is-danger">
								{{ $errors->first('billing_address_id') }}
							</span>
						@endif
					</p>
				</div>
				<hr>
			</div>
		</div>
		--}}

		{{-- -- Form done. --}}
		<div class="column is-7 has-text-right">
			<a class="button is-text" href="{{ route('purchase-orders.index') }}">Cancel</a>
			<button type="submit" class="button is-primary">Save</button>
		</div>
	</form>
@endsection
