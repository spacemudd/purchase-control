@extends('layouts.app', ['title' => trans('words.create') . ' ' . trans('words.vendor')])


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
			<li class="is-active">
				<a href="#">{{ trans('words.new-vendor') }}</a>
			</li>
		</ul>
	</nav>
@endsection

@section('content')

	<form method="POST" action="{{ route('vendors.store') }}">
		{{ csrf_field() }}
		<div class="columns is-multiline">

			<div class="column is-6">
				<div class="field">
					<label for="code" class="label">{{ trans('words.code') }} <span class="has-text-warning">*</span></label>

					<p class="control">
						<input id="code" type="text" class="input {{ $errors->has('code') ? ' is-danger' : '' }}" name="code"
							   value="{{ old('code') }}" required>

						@if ($errors->has('code'))
							<span class="help is-danger">
                                {{ $errors->first('code') }}
                            </span>
						@endif
					</p>
				</div>
			</div>


			<div class="column is-6">
				<div class="field">
					<label for="description" class="label">{{ trans('words.description') }} <span class="has-text-warning">*</span></label>

					<p class="control">
						<input id="code" type="text" class="input {{ $errors->has('description') ? ' is-danger' : '' }}" name="description"
							   value="{{ old('description') }}" required>

						@if ($errors->has('description'))
							<span class="help is-danger">
                                {{ $errors->first('description') }}
                            </span>
						@endif
					</p>
				</div>
			</div>


			<div class="column is-6">
				<div class="field">
					<label for="contact_person" class="label">{{ trans('words.contact-person') }} <span class="has-text-warning">*</span></label>

					<p class="control">
						<input id="code" type="text" class="input {{ $errors->has('contact_person') ? ' is-danger' : '' }}" name="contact_person"
							   value="{{ old('contact_person') }}" required>

						@if ($errors->has('contact_person'))
							<span class="help is-danger">
                                {{ $errors->first('contact_person') }}
                            </span>
						@endif
					</p>
				</div>
			</div>

			<div class="column is-6">
				<div class="field">
					<label for="contact_details" class="label">{{ trans('words.contact-details') }} <span class="has-text-warning">*</span></label>

					<p class="control">
						<input id="contact_details" type="text" class="input {{ $errors->has('contact_details') ? ' is-danger' : '' }}" name="contact_details"
							   value="{{ old('contact_details') }}" required>

						@if ($errors->has('contact_details'))
							<span class="help is-danger">
                                {{ $errors->first('contact_details') }}
                            </span>
						@endif
					</p>
				</div>
			</div>

			<div class="column is-6">
				<div class="field">
					<label for="address" class="label">{{ trans('words.address') }} <span class="has-text-warning">*</span></label>

					<p class="control">
						<input id="address" type="text" class="input {{ $errors->has('address') ? ' is-danger' : '' }}" name="address"
							   value="{{ old('address') }}" required>

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
					<label for="email" class="label">{{ trans('auth.email') }}</label>

					<p class="control">
						<input id="email" type="email" class="input {{ $errors->has('email') ? ' is-danger' : '' }}"
							   name="email"
							   value="{{ old('email') }}" >

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
					<label for="website" class="label">{{ trans('words.website') }}</label>

					<p class="control">
						<input id="website" type="text" class="input {{ $errors->has('website') ? ' is-danger' : '' }}"
							   name="website"
							   value="{{ old('website') }}" >

						@if ($errors->has('website'))
							<span class="help is-danger">
                                {{ $errors->first('website') }}
                            </span>
						@endif
					</p>
				</div>
			</div>


			<div class="column is-6">
				<div class="field">
					<label for="" class="label">{{ trans('words.active') }}</label>

					<div class="control">
						<div class="select">
							<select name="active">
								<option value="1" {{ (old('active') == true)  ? 'selected' : '' }}>
									{{ trans('words.active') }}
								</option>
								<option value="0" {{ (old('active') == false)  ? 'selected' : '' }}>
									{{ trans('words.disabled') }}
								</option>
							</select>
						</div>

						@if ($errors->has('active'))
							<span class="help is-danger">
                                {{ $errors->first('active') }}
                            </span>
						@endif
					</div>
				</div>
			</div>

			<div class="column is-12">
				<div class="field is-grouped">
					<div class="control">
						<button type="submit" class="button is-success">{{ trans('words.save') }}</button>
					</div>
					<div class="control">
						<a class="button is-text" href="{{ url('/') }}">Cancel</a>
					</div>
				</div>
			</div>

		</div>

	</form>

@endsection
