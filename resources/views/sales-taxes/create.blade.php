@extends('layouts.app', ['title' => 'New Sales Taxes'])

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
				<a href="{{ route('sales-taxes.index') }}">
					<span class="icon is-small"><i class="fa fa-balance-scale"></i></span>
					<span>Sales Taxes</span>
				</a>
			</li>
			<li>
				<a href="#">
					New
				</a>
			</li>
		</ul>
	</nav>
@endsection

@section('content')

	<div class="columns">
		<div class="column is-6 is-offset-3">
			<form method="post" action="{{ route('sales-taxes.store') }}">
				{{ csrf_field() }}
				<div class="columns is-multiline">

					<div class="column is-6">
						<div class="field">
							<label for="tax_name" class="label">Tax Name <span class="has-text-danger">*</span></label>

							<p class="control">
								<input id="tax_name" type="text"
									   class="input {{ $errors->has('tax_name') ? ' is-danger' : '' }}"
									   name="tax_name" value="{{ old('tax_name') }}" required>

								@if ($errors->has('tax_name'))
									<span class="help is-danger">
							            {{ $errors->first('tax_name') }}
							        </span>
								@endif
							</p>
						</div>
					</div>

					<div class="column is-6">
						<div class="field">
							<label for="abbreviation" class="label">Abbreviation <span class="has-text-danger">*</span></label>

							<p class="control">
								<input id="abbreviation" type="text"
									   class="input {{ $errors->has('abbreviation') ? ' is-danger' : '' }}"
									   name="abbreviation" value="{{ old('abbreviation') }}" required>

								@if ($errors->has('abbreviation'))
									<span class="help is-danger">
							            {{ $errors->first('abbreviation') }}
							        </span>
								@endif
							</p>
						</div>
					</div>

					<div class="column is-6">
						<div class="field">
							<label for="current_tax_rate" class="label">Tax Rate <span class="has-text-danger">*</span></label>

							<p class="control">
								<input id="current_tax_rate" type="number"
									   class="input {{ $errors->has('current_tax_rate') ? ' is-danger' : '' }}"
									   name="current_tax_rate" value="{{ old('current_tax_rate') }}" required>

								<p class="help">The tax rate without %.</p>

								@if ($errors->has('current_tax_rate'))
									<span class="help is-danger">
							            {{ $errors->first('current_tax_rate') }}
							        </span>
								@endif
							</p>
						</div>
					</div>
					
					<div class="column is-6">
						<div class="field">
							<label for="currency" class="label">Currency</label>

							<p class="control">
								<div class="select is-fullwidth">
									<select tabindex="0" name="currency" class="{{ $errors->has('currency') ? ' is-danger' : '' }}" autofocus>
										@foreach($currencies as $currencyCode => $description)
											<option value="{{ $currencyCode }}">{{ $currencyCode }} - {{ $description }}</option>
										@endforeach
									</select>
								</div>

								@if ($errors->has('currency'))
									<span class="help is-danger">
							            {{ $errors->first('currency') }}
							        </span>
								@endif
							</p>
						</div>
					</div>

					<div class="column">
						<div class="field">
							<label for="description" class="label">Description</label>

							<p class="control">
								<input id="description" type="text"
									   class="input {{ $errors->has('description') ? ' is-danger' : '' }}"
									   name="description" value="{{ old('description') }}">

								@if ($errors->has('description'))
									<span class="help is-danger">
							            {{ $errors->first('description') }}
							        </span>
								@endif
							</p>
						</div>
					</div>

					<div class="column is-12 has-text-right">
						<a class="button is-text" href="{{ route('sales-taxes.index') }}">Cancel</a>
						<button type="submit" class="button is-primary">Save</button>
					</div>
				</div>

			</form>
		</div>
	</div>

@endsection
