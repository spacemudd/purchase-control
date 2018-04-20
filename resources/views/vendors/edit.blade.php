@extends('layouts.app', ['title' => $vendor->code . ' - ' . trans('words.edit') . ' ' . trans('words.vendor')])

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
				<a href="{{ route('vendors.show', ['id' => $vendor->id]) }}">{{ $vendor->code }}</a>
			</li>
			<li class="is-active">
				<a href="#">{{ trans('words.edit') }}</a>
			</li>
		</ul>
	</nav>
@endsection

@section('content')
	<div class="columns">
		<div class="column is-8 is-offset-2">
			<form class="form-horizontal form-groups-bordered" method="post" action="{{ route('vendors.update', ['id' => $vendor->id]) }}">
				{{ csrf_field() }}
				<input type="hidden" name="_method" value="PUT" class="input">

				<div class="columns is-multiline">

					<div class="column is-6">
						<div class="field">
							<label for="code" class="label">{{ trans('words.code') }} <span class="has-text-danger">*</span></label>

							<p class="control">
								<input id="code" type="text" class="input {{ $errors->has('code') ? ' is-danger' : '' }}" name="code"
									   value="{{ $vendor->code }}" required>

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
							<label for="description" class="label">{{ trans('words.description') }}</label>

							<p class="control">
								<input id="code" type="text" class="input {{ $errors->has('description') ? ' is-danger' : '' }}" name="description"
									   value="{{ $vendor->description }}">

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
							<label for="contact_person" class="label">{{ trans('words.contact-person') }}</label>

							<p class="control">
								<input id="code" type="text" class="input {{ $errors->has('contact_person') ? ' is-danger' : '' }}" name="contact_person"
									   value="{{ $vendor->contact_person }}">

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
							<label for="contact_details" class="label">{{ trans('words.contact-details') }}</label>

							<p class="control">
								<input id="code" type="text" class="input {{ $errors->has('contact_details') ? ' is-danger' : '' }}" name="contact_details"
									   value="{{ $vendor->contact_details }}">

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
							<label for="email" class="label">{{ trans('auth.email') }}</label>

							<p class="control">
								<input id="email" type="email" class="input {{ $errors->has('email') ? ' is-danger' : '' }}"
									   name="email"
									   value="{{ $vendor->email }}" >

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
									   value="{{ $vendor->website }}" >

								@if ($errors->has('website'))
									<span class="help is-danger">
                                {{ $errors->first('website') }}
                            </span>
								@endif
							</p>
						</div>
					</div>

					<div class="column is-6">
						<form method="post" action="{{ route('vendors.destroy', ['id' => $vendor->id]) }}">
							{{ csrf_field() }}
							<input type="hidden" name="_method" value="delete" class="input">
							<button class="button is-danger">
								{{ trans('words.delete') }}
							</button>
						</form>
					</div>

					<div class="column is-6 has-text-right">
						<a class="button is-text" href="{{ route('vendors.show', ['id' => $vendor->id]) }}">{{ __('words.cancel') }}</a>
						<button type="submit" class="button is-success">{{ trans('words.save') }}</button>
					</div>

				</div>
			</form>
		</div>
	</div>

@endsection
