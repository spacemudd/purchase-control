@extends('layouts.app', ['title' => trans('words.create') . ' ' . trans('words.employee')])

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
                <a href="{{ route('employees.index') }}">
                    <span class="icon is-small"><i class="fa fa-user"></i></span>
                    <span>{{ trans('words.employees') }}</span>
                </a>
            </li>
            <li class="is-active">
                <a href="#">{{ trans('words.new-employee') }}</a>
            </li>
        </ul>
    </nav>
@endsection

@section('content')

    @if(count($departments))
        <form method="POST" action="{{ route('employees.store') }}">
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
						<label for="name" class="label">{{ trans('words.name') }} <span class="has-text-warning">*</span></label>

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
						<label for="department_id" class="label">{{ trans('words.department') }}</label>

						<div class="control">
							<div class="select is-fullwidth">
								<select name="department_id">
									<option></option>
									@foreach($departments as $department)
										<option value="{{ $department->id }}">
											{{ $department->id }} - {{ $department->description }} {{ $department->groupsDisplayInput }}
										</option>
									@endforeach
								</select>
							</div>

							@if ($errors->has('department_id'))
								<span class="help is-danger">
									{{ $errors->first('department_id') }}
								</span>
							@endif
						</div>
					</div>
				</div>

				<div class="column is-6">
					<div class="field">
						<label for="staff_type_id" class="label">{{ trans('words.staff-type') }}</label>

						<p class="control">
						<div class="select is-fullwidth">
							<select name="staff_type_id">
								<option></option>
								@foreach($types as $type)
									<option value="{{ $type->id }}">{{ $type->title }}</option>
								@endforeach
							</select>
						</div>

						@if ($errors->has('staff_type_id'))
							<span class="help is-danger">
									{{ $errors->first('staff_type_id') }}
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
						<label for="contact_number" class="label">{{ trans('words.contact-number') }}</label>

						<p class="control">
							<input id="contact_number" type="text" class="input {{ $errors->has('contact_number') ? ' is-danger' : '' }}"
								   name="contact_number"
								   value="{{ old('contact_number') }}" >

							@if ($errors->has('contact_number'))
								<span class="help is-danger">
									{{ $errors->first('contact_number') }}
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
									<option value="1">
										{{ trans('words.active') }}
									</option>
									<option value="0">
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
							<button type="submit" class="button is-success">{{ trans('words.new-employee') }}</button>
						</div>
						<div class="control">
							<a class="button is-text" href="{{ url('/') }}">Cancel</a>
						</div>
					</div>
				</div>

			</div>

        </form>
    @else

        <h1 class="text-center">{{ trans('messages.must-create-department') }}

    @endif
@endsection
