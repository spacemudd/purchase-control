@extends('layouts.app', ['title' => $vendorBank->vendor->id . ' - ' . $vendorBank->vendor->name . ' - ' . trans('words.edit') . ' ' . trans('words.vendor')])

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
				<a href="{{ route('vendors.index') }}">
					<span class="icon is-small"><i class="fa fa-truck"></i></span>
					<span>{{ trans('words.vendors') }}</span>
				</a>
			</li>
			<li>
				<a href="{{ route('vendors.show', ['id' => $vendorBank->vendor->id]) }}">{{ $vendorBank->vendor->id }} - {{ $vendorBank->vendor->name }}</a>
			</li>
			<li class="is-active">
				<a href="#">{{ trans('words.edit') }} Bank Information</a>
			</li>
		</ul>
	</nav>
@endsection

@section('content')
	<div class="columns">
		<div class="column is-8 is-offset-2">
			<form class="form-horizontal form-groups-bordered" method="post" action="{{ route('vendor-bank.update', ['vendor_id' => $vendorBank->vendor->id, 'id' => $vendorBank->id]) }}">
				{{ csrf_field() }}
				<input type="hidden" name="_method" value="put" class="input">
				<input type="hidden" name="vendor_id" value="{{ $vendorBank->vendor->id }}">

				<div class="columns is-multiline">

					<div class="column is-6">
						<div class="field">
							<label for="name" class="label">{{ __('words.bank-name') }} <span class="has-text-danger">*</span></label>

							<p class="control">
								<input id="name" type="text" class="input {{ $errors->has('name') ? ' is-danger' : '' }}"
									   name="name"
									   value="{{ $vendorBank->name }}" required>

								@if ($errors->has('name'))
									<span class="help is-danger">
									{{ $errors->first('name') }}
								</span>
								@endif
							</p>
						</div>
					</div>

					<div class="column is-6">
						<div class="field">
							<label for="address" class="label">{{ __('words.address') }}</label>

							<p class="control">
								<input id="address" type="text"
									   class="input {{ $errors->has('address') ? ' is-danger' : '' }}"
									   name="address" value="{{ $vendorBank->address }}">

								@if ($errors->has('address'))
									<span class="help is-danger">
									{{ $errors->first('address') }}
								</span>
								@endif
							</p>
						</div>
					</div>

					<div class="column is-6">
						<div class="field">
							<label for="beneficiary_name" class="label">{{ __('words.beneficiary-name') }}</label>

							<p class="control">
								<input id="beneficiary_name" type="text"
									   class="input {{ $errors->has('beneficiary_name') ? ' is-danger' : '' }}"
									   name="beneficiary_name" value="{{ $vendorBank->beneficiary_name }}">

								@if ($errors->has('beneficiary_name'))
									<span class="help is-danger">
									{{ $errors->first('beneficiary_name') }}
								</span>
								@endif
							</p>
						</div>
					</div>

					<div class="column is-6">
						<div class="field">
							<label for="account_number" class="label">{{ __('words.account-number') }}</label>

							<p class="control">
								<input id="account_number" type="text"
									   class="input {{ $errors->has('account_number') ? ' is-danger' : '' }}"
									   name="account_number" value="{{ $vendorBank->account_number }}">

								@if ($errors->has('account_number'))
									<span class="help is-danger">
									{{ $errors->first('account_number') }}
								</span>
								@endif
							</p>
						</div>
					</div>

					<div class="column is-6">
						<div class="field">
							<label for="iban_code" class="label">IBAN Code</label>

							<p class="control">
								<input id="iban_code" type="text"
									   class="input {{ $errors->has('iban_code') ? ' is-danger' : '' }}"
									   name="iban_code" value="{{ $vendorBank->iban_code }}">

								@if ($errors->has('iban_code'))
									<span class="help is-danger">
									{{ $errors->first('iban_code') }}
								</span>
								@endif
							</p>
						</div>
					</div>

					<div class="column is-6">
						<div class="field">
							<label for="swift_code" class="label">Swift Code</label>

							<p class="control">
								<input id="swift_code" type="text"
									   class="input {{ $errors->has('swift_code') ? ' is-danger' : '' }}"
									   name="swift_code" value="{{ $vendorBank->swift_code }}">

								@if ($errors->has('swift_code'))
									<span class="help is-danger">
									{{ $errors->first('swift_code') }}
								</span>
								@endif
							</p>
						</div>
					</div>

					<div class="column is-6">
						<div class="field">
							<label for="sort_code" class="label">Sort Code</label>

							<p class="control">
								<input id="sort_code" type="text"
									   class="input {{ $errors->has('sort_code') ? ' is-danger' : '' }}"
									   name="sort_code" value="{{ $vendorBank->sort_code }}">

								@if ($errors->has('sort_code'))
									<span class="help is-danger">
									{{ $errors->first('sort_code') }}
								</span>
								@endif
							</p>
						</div>
					</div>

					<div class="column is-6">
						<div class="field">
							<label for="currency" class="label">Currency</label>

							<div class="control">
								<div class="select is-fullwidth">
									<select name="currency{{ $errors->has('currency') ? ' is-danger' : '' }}">
										<option value=""></option>
										@foreach($currencies as $currencyCode => $description)
											<option value="{{ $currencyCode }}"{{ $vendorBank->currency === $currencyCode ? ' selected' : '' }}>{{ $currencyCode }} - {{ $description }}</option>
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
					</div>

					<div class="column is-12 has-text-right">
						<a class="button is-text" href="{{ route('vendors.show', ['id' => $vendorBank->vendor->id]) }}">{{ __('words.cancel') }}</a>
						<button type="submit" class="button is-success">{{ trans('words.save') }}</button>
					</div>

				</div>
			</form>
		</div>
	</div>

@endsection
