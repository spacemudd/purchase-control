@extends('layouts.app', ['title' => trans('words.create') . ' ' . trans('words.departments')])


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
				<a href="{{ route('departments.index') }}">
					<span class="icon is-small"><i class="fa fa-building"></i></span>
					<span>{{ trans('words.departments') }}</span>
				</a>
			</li>
			<li class="is-active">
				<a href="#">{{ trans('words.new-department') }}</a>
			</li>
		</ul>
	</nav>
@endsection


@section('content')

    <div class="columns">
        <div class="column is-8 is-offset-2">
            <form method="POST" action="{{ route('departments.store') }}">
                {{ csrf_field() }}
                <div class="columns is-multiline">

                    <div class="column is-6">
                        <div class="field">
                            <label for="code" class="label">{{ trans('words.code') }} <span class="has-text-danger">*</span></label>

                            <p class="control">
                                <input id="code" type="text" class="input {{ $errors->has('code') ? ' is-danger' : '' }}"
                                       name="code"
                                       value="{{ old('code') }}" required>

                            <p class="help">An identity of the department, e.g. 001.</p>
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
                            <label for="description" class="label">{{ trans('words.description') }} <span class="has-text-danger">*</span></label>

                            <p class="control">
                                <input id="description" type="text"
                                       class="input {{ $errors->has('description') ? ' is-danger' : '' }}"
                                       name="description"
                                       value="{{ old('description') }}" required>

                            <p class="help">Displayed with the code, e.g. in reports and forms.</p>
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
                            <label for="head_department" class="label">{{ trans('words.head-of-department') }}</label>

                            <p class="control">
                                <input id="head_department" type=""
                                       class="input {{ $errors->has('head_department') ? ' is-danger' : '' }}"
                                       name="head_department"
                                       value="{{ old('head_department') }}">

                                @if ($errors->has('head_department'))
                                    <span class="help is-danger">
                                        {{ $errors->first('head_department') }}
                                    </span>
                                @endif
                            </p>
                        </div>

                    </div>

                    <div class="column is-12">
                        <div class="field has-text-right">
                            <a class="button is-text" href="{{ route('departments.index') }}">{{ __('words.cancel') }}</a>
                            <button type="submit" class="button is-primary">{{ trans('words.save') }}</button>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
@endsection
