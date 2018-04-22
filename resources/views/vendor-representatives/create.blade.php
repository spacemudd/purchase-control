@extends('layouts.app', ['title' => trans('words.create') . ' ' . trans('words.representative')])


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
				<a href="{{ route('vendors.show', ['id' => $vendor->id]) }}">
					{{ $vendor->id }} - {{ $vendor->name }}
				</a>
			</li>
			<li class="is-active">
				<a href="#">{{ trans('words.new-representative') }}</a>
			</li>
		</ul>
	</nav>
@endsection

@section('content')

	<div class="columns">
		<div class="column is-6 is-offset-3">
			<p class="title is-spaced">New representative form</p>
			<p class="subtitle">{{ $vendor->name }}</p>

			<form method="post" action="{{ route('vendor-representatives.store', ['vendor_id' => $vendor->id]) }}">
				{{ csrf_field() }}
				<input type="hidden" name="vendor_id" value="{{ $vendor->id }}">

				<div class="columns is-multiline">
					<div class="column is-6">
						<div class="field">
							<label for="name" class="label">{{ __('words.name') }} <span class="has-text-danger">*</span></label>

							<p class="control">
								<input id="name" type="text" class="input {{ $errors->has('name') ? ' is-danger' : '' }}"
									   name="name"
									   value="{{ old('name') }}" required>

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
							<label for="email" class="label">{{ __('auth.email') }}</label>

							<p class="control">
								<input id="email" type="email"
									   class="input {{ $errors->has('email') ? ' is-danger' : '' }}"
									   name="email" value="{{ old('email') }}">

								@if ($errors->has('email'))
									<span class="help is-danger">
							            {{ $errors->first('email') }}
							        </span>
								@endif
							</p>
						</div>
					</div>

					<div class="column is-6">
						<div class="field">
							<label for="number" class="label">{{ __('words.number') }}</label>

							<p class="control">
								<input id="number" type="text"
									   class="input {{ $errors->has('number') ? ' is-danger' : '' }}"
									   name="number" value="{{ old('number') }}">

								@if ($errors->has('number'))
									<span class="help is-danger">
							            {{ $errors->first('number') }}
							        </span>
								@endif
							</p>
						</div>
					</div>

					<div class="column is-6">
						<div class="field">
							<label for="position" class="label">{{ __('words.position') }}</label>

							<p class="control">
								<input id="position" type="text"
									   class="input {{ $errors->has('position') ? ' is-danger' : '' }}"
									   name="position" value="{{ old('position') }}">

								@if ($errors->has('position'))
									<span class="help is-danger">
							            {{ $errors->first('position') }}
							        </span>
								@endif
							</p>
						</div>
					</div>

					<div class="column is-12 has-text-right">
						<a class="button is-text" href="{{ route('vendors.show', ['id' => $vendor->id]) }}">{{ __('words.cancel') }}</a>
						<button type="submit" class="button is-primary">{{ trans('words.save') }}</button>
					</div>
				</div>
			</form>
		</div>
	</div>
@endsection
