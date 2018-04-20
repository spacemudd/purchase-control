@extends('layouts.app', ['title' => trans('words.edit') . ' ' . trans('words.assets')])

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
				<a href="{{ route('assets.index') }}">
					<span class="icon is-small"><i class="fa fa-barcode"></i></span>
					<span>{{ trans('words.assets') }}</span>
				</a>
			</li>
			<li>
				<a href="{{ route('assets.show', ['id' => $asset->id]) }}">{{ $asset->asset_template->code }} ({{  $asset->manufacturer_serial_number }})</a>
			</li>
			<li class="is-active"><a href="#">{{ __('words.edit') }}</a></li>
		</ul>
	</nav>
@endsection

@section('content')

	@if(session()->has('message'))
		<div class="columns">
			<div class="column is-6 is-offset-3">
				@component('components.alerts.basic', ['status' => (session()->has('status') == null) ? 'default' : session()->get('status')])
					{{ session()->get('message') }}
				@endcomponent
			</div>
		</div>
	@endif

	<div class="columns">
		<div class="column is-6 is-offset-3">
			<form class="columns is-multiline" method="post" action="{{ route('assets.update', ['id' => $asset->id]) }}">
				{{ csrf_field() }}
				<input type="hidden" name="_method" value="put">

				<div class="column is-12">
					<div class="field">
						<label for="manufacturer_serial_number" class="label">{{ __('words.manufacturer-serial-number') }}</label>

						<p class="control">
							<input id="manufacturer_serial_number" type="text"
								   class="input {{ $errors->has('manufacturer_serial_number') ? ' is-danger' : '' }}"
								   name="manufacturer_serial_number"
								   value="{{ $asset->manufacturer_serial_number }}" required>

							@if ($errors->has('manufacturer_serial_number'))
								<span class="help is-danger">
						            {{ $errors->first('manufacturer_serial_number') }}
						        </span>
							@endif
						</p>
					</div>
				</div>

				<div class="column is-6">
					<div class="field">
						<label for="system_tag_number" class="label">{{ __('words.system-tag-number') }}</label>

						<p class="control">
							<input id="system_tag_number" type="text"
								   class="input {{ $errors->has('system_tag_number') ? ' is-danger' : '' }}"
								   name="system_tag_number"
								   value="{{ $asset->system_tag_number }}">

							@if ($errors->has('system_tag_number'))
								<span class="help is-danger">
						            {{ $errors->first('system_tag_number') }}
						        </span>
							@endif
						</p>
					</div>
				</div>

				<div class="column is-6">
					<div class="field">
						<label for="finance_tag_number" class="label">{{ __('words.finance-tag-number') }}</label>

						<p class="control">
							<input id="finance_tag_number" type="text"
								   class="input {{ $errors->has('finance_tag_number') ? ' is-danger' : '' }}"
								   name="finance_tag_number"
								   value="{{ $asset->finance_tag_number }}">

							@if ($errors->has('finance_tag_number'))
								<span class="help is-danger">
						            {{ $errors->first('finance_tag_number') }}
						        </span>
							@endif
						</p>
					</div>
				</div>

				<div class="column is-6">
					<div class="field">
						<label for="rfid" class="label">{{ __('words.rfid-technology') }}</label>

						<p class="control">
							<input id="rfid" type="" class="input {{ $errors->has('rfid') ? ' is-danger' : '' }}"
								   name="rfid"
								   value="{{ $asset->rfid }}" >

							@if ($errors->has('rfid'))
								<span class="help is-danger">
						            {{ $errors->first('rfid') }}
						        </span>
							@endif
						</p>
					</div>
				</div>

				<div class="column is-6">
					<div class="field">
						<label for="unit_price" class="label">{{ __('words.unit-price') }}</label>

						<p class="control">
							<input id="unit_price" type="number"
								   step="0.01"
								   class="input {{ $errors->has('unit_price') ? ' is-danger' : '' }}" name="unit_price"
								   value="{{ $asset->unit_price }}" >

							@if ($errors->has('unit_price'))
								<span class="help is-danger">
					            {{ $errors->first('unit_price') }}
					        </span>
							@endif
						</p>
					</div>
				</div>

				<div class="column is-6">
					<div class="field">
						<label for="condition" class="label">Condition</label>

						<p class="control">
							<div class="select is-fullwidth">
								<select class="select" name="condition">
									<option value="good"{{ $asset->condition != 'good' ? '' : ' selected' }}>{{ trans('words.good') }}</option>
									<option value="damaged"{{ $asset->condition != 'damaged' ? '' : ' selected' }}>{{ trans('words.damaged') }}</option>
									<option value="defective"{{ $asset->condition != 'defective' ? '': ' selected' }}>{{ trans('words.defective') }}</option>
								</select>
							</div>

							@if ($errors->has('condition'))
								<span class="help is-danger">
						            {{ $errors->first('condition') }}
						        </span>
							@endif
						</p>
					</div>
				</div>

				<div class="column is-6">
					<div class="field">
						<label for="warranty_expiry" class="label">{{ __('words.warranty-expiry') }}</label>

						<p class="control">
							<input id="warranty_expiry" type="text"
								   class="input {{ $errors->has('warranty_expiry') ? ' is-danger' : '' }}"
								   name="warranty_expiry"
								   value="{{ $asset->warranty_expiry_human }}">

							<p class="help">{{ trans('statements.leave-blank-if-no-warranty') }}</p>
							@if ($errors->has('warranty_expiry'))
								<span class="help is-danger">
						            {{ $errors->first('warranty_expiry') }}
						        </span>
							@endif
						</p>
					</div>
				</div>

				<div class="column is-12">
					<div class="field-group has-text-right">
						<a class="button is-text" href="{{ route('assets.show', ['id' => $asset->id]) }}">{{ __('words.cancel') }}</a>
						<button class="button is-primary" type="submit">{{ __('words.save') }}</button>
					</div>
				</div>
			</form>
		</div>
	</div>

@endsection
