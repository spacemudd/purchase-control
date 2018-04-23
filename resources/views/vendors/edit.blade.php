@extends('layouts.app', ['title' => $vendor->id . ' - ' . $vendor->name . ' - ' . trans('words.edit') . ' ' . trans('words.vendor')])

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
				<a href="{{ route('vendors.show', ['id' => $vendor->id]) }}">{{ $vendor->id }} - {{ $vendor->name }}</a>
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
							<label for="name" class="label">{{ trans('words.name') }}</label>

							<p class="control">
								<input id="code" type="text" class="input {{ $errors->has('name') ? ' is-danger' : '' }}" name="name"
									   value="{{ $vendor->name }}">

								@if ($errors->has('name'))
									<span class="help is-danger">
                                {{ $errors->first('name') }}
                            </span>
								@endif
							</p>
						</div>
					</div>


					<div class="column is-6">
						<datepicker
								label="{{ __('words.established-at') }}"
								value="{{ $vendor->established_at }}"
								name="established_at"
								required.number="1"
						>
						</datepicker>

						@if ($errors->has('established_at'))
							<span class="help is-danger">
								{{ $errors->first('established_at') }}
							</span>
						@endif
					</div>

					<div class="column is-6">
						<div class="field">
							<label for="address" class="label">{{ trans('words.address') }}</label>

							<p class="control">
								<input id="code" type="text" class="input {{ $errors->has('address') ? ' is-danger' : '' }}" name="address"
									   value="{{ $vendor->address }}">

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
                            <label for="email" class="label">{{ __('auth.email') }}</label>

                            <p class="control">
                                <input id="email" type="text" class="input {{ $errors->has('email') ? ' is-danger' : '' }}"
                                       name="email" value="{{ old('email') }}" >

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
							<label for="telephone_number" class="label">{{ __('words.telephone-number') }}</label>

							<p class="control">
								<input id="telephone_number" type="text" class="input {{ $errors->has('telephone_number') ? ' is-danger' : '' }}"
									   name="telephone_number" value="{{ old('telephone_number') }}" >

								@if ($errors->has('telephone_number'))
									<span class="help is-danger">
							            {{ $errors->first('telephone_number') }}
							        </span>
								@endif
							</p>
						</div>
					</div>

					<div class="column is-6">
						<div class="field">
							<label for="fax_number" class="label">{{ __('words.fax-number') }}</label>

							<p class="control">
								<input id="fax_number" type="text" class="input {{ $errors->has('fax_number') ? ' is-danger' : '' }}" name="fax_number"
									   value="{{ old('fax_number') }}" >

								@if ($errors->has('fax_number'))
									<span class="help is-danger">
										{{ $errors->first('fax_number') }}
									</span>
								@endif
							</p>
						</div>
					</div>

					<div class="column is-12">
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
						{{--
						<form method="post" action="{{ route('vendors.destroy', ['id' => $vendor->id]) }}">
							{{ csrf_field() }}
							<input type="hidden" name="_method" value="delete" class="input">
							<button class="button is-danger">
								{{ trans('words.delete') }}
							</button>
						</form>
						--}}
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
