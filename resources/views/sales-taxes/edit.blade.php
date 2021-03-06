@extends('layouts.app', ['title' => $address->location . ' - Edit Address'])


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
				<a href="{{ route('addresses.index') }}">
					<span class="icon is-small"><i class="fa fa-map-marker"></i></span>
					<span>{{ trans('words.addresses') }}</span>
				</a>
			</li>
			<li>
				<a href="{{ route('addresses.show', ['id' => $address->id]) }}">
					{{ $address->location }}
				</a>
			</li>
			<li class="is-active">
				<a href="#">Edit</a>
			</li>
		</ul>
	</nav>
@endsection

@section('content')

	<div class="columns">
		<div class="column is-6 is-offset-3">
			<form method="post" action="{{ route('addresses.store') }}">
				{{ csrf_field() }}
				<div class="columns is-multiline">
					<div class="column is-12">
						<div class="field">
							<label for="type" class="label">Type</label>

							<div class="control">
								<div class="select">
									<select name="type" id="type">
										<option value="0"{{ $address->type == 0 ? ' selected' : '' }}>Shipping Address</option>
										<option value="1"{{ $address->type == 1 ? ' selected' : '' }}>Billing Address</option>
									</select>
								</div>

								@if ($errors->has('type'))
									<span class="help is-danger">
							            {{ $errors->first('type') }}
							        </span>
								@endif
							</div>
						</div>
					</div>
					<div class="column is-12">
						<div class="field">
							<label for="location" class="label">Location <span class="has-text-danger">*</span></label>

							<p class="control">
						<textarea id="location"
								  name="location"
								  class="textarea{{ $errors->has('location') ? ' is-danger' : '' }}"
								  required
						>{{ $address->location }}</textarea>

								@if ($errors->has('location'))
									<span class="help is-danger">
								{{ $errors->first('location') }}
							</span>
								@endif
							</p>
						</div>
					</div>

					<div class="column is-6">
						<div class="field">
							<label for="department" class="label">Department</label>

							<p class="control">
								<input id="department" type="text"
									   class="input {{ $errors->has('department') ? ' is-danger' : '' }}" name="department"
									   value="{{ $address->department }}">

								@if ($errors->has('department'))
									<span class="help is-danger">
					            {{ $errors->first('department') }}
					        </span>
								@endif
							</p>
						</div>
					</div>

					<div class="column is-6">
						<div class="field">
							<label for="contact_name" class="label">Contact Name</label>

							<p class="control">
								<input id="contact_name" type="text"
									   class="input {{ $errors->has('contact_name') ? ' is-danger' : '' }}" name="contact_name"
									   value="{{ $address->contact_name }}">

								@if ($errors->has('contact_name'))
									<span class="help is-danger">
					            {{ $errors->first('contact_name') }}
					        </span>
								@endif
							</p>
						</div>
					</div>

					<div class="column is-6">
						<div class="field">
							<label for="phone" class="label">Phone</label>

							<p class="control">
								<input id="phone" type="text" class="input {{ $errors->has('phone') ? ' is-danger' : '' }}"
									   name="phone"
									   value="{{ $address->phone }}">

								@if ($errors->has('phone'))
									<span class="help is-danger">
					            {{ $errors->first('phone') }}
					        </span>
								@endif
							</p>
						</div>
					</div>

					<div class="column is-6">
						<div class="field">
							<label for="email" class="label">Email</label>

							<p class="control">
								<input id="email" type="email" class="input {{ $errors->has('email') ? ' is-danger' : '' }}"
									   name="email"
									   value="{{ $address->email }}">

								@if ($errors->has('email'))
									<span class="help is-danger">
					            {{ $errors->first('email') }}
					        </span>
								@endif
							</p>
						</div>
					</div>

					<div class="column is-12 has-text-right">
						<a class="button is-text" href="{{ route('addresses.index') }}">Cancel</a>
						<button type="submit" class="button is-primary">Save</button>
					</div>
				</div>

			</form>
		</div>
	</div>

@endsection
