@extends('layouts.app', [
	'title' => trans('words.roles')
])

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
                <a href="{{ route('roles.index') }}">
                    <span class="icon is-small"><i class="fa fa-user-circle-o"></i></span>
                    <span>{{ trans('words.roles') }}</span>
                </a>
            </li>
            <li>
                <a href="#">{{ __('words.new-role') }}</a>
            </li>
        </ul>
    </nav>
@endsection

@section('content')

    <div class="section">
        <div class="columns">
            <div class="column is-6 is-offset-3">
                <h1 class="title is-3 has-text-weight-bold">
                    {{ __('words.new-role') }}
                </h1>
                <form action="{{ route('roles.store') }}" method="post">
                    {{ csrf_field() }}
                    <div class="field">
                        <label for="name" class="label">{{ __('words.name') }}</label>

                        <div class="control">
                            <input id="name"
                                   type="text"
                                   class="input {{ $errors->has('name') ? ' is-danger' : '' }}"
                                   name="name"
                                   value="{{ old('name') }}" required>

                            @if ($errors->has('name'))
                                <span class="help is-danger">
                    	            {{ $errors->first('name') }}
                    	        </span>
                            @endif
                        </div>
                    </div>

                    <div class="field has-text-right">
                        <a href="{{ route('roles.index') }}" class="button is-text">Cancel</a>
                        <button type="submit" class="button is-primary">{{ __('words.new-role') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
