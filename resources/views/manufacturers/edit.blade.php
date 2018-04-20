@extends('layouts.app', ['title' => trans('words.edit') . ' ' . trans('words.manufacturers')])

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
				<a href="{{ route('manufacturers.index') }}">
					<span class="icon is-small"><i class="fa fa-cubes"></i></span>
					<span>{{ trans('words.manufacturers') }}</span>
				</a>
			</li>
            <li>
                <a href="{{ route('manufacturers.show', ['id' => $manufacturer->id]) }}">
                    {{ $manufacturer->code }} - {{ $manufacturer->description }}
                </a>
            </li>
			<li class="is-active">
				<a href="#">{{ __('words.edit') }}</a>
			</li>
		</ul>
	</nav>
@endsection

@section('content')

    <div class="level">
        <div class="level-left">

        </div>
        <div class="level-right">
            <form method="POST" action="{{ route('manufacturers.destroy', ['id' => $manufacturer->id]) }}">
                {{ csrf_field() }}
                <input type="hidden" name="_method" value="delete" class="input">
                <button class="button is-danger is-small">
                    {{ trans('words.delete') }}
                </button>
            </form>
        </div>
    </div>


    <form method="POST" action="{{ route('manufacturers.update', ['id' => $manufacturer->id]) }}">
        {{ csrf_field() }}
        <input type="hidden" name="_method" value="PUT" class="input">


        <div class="columns is-multiline">

            <div class="column is-6">
                <div class="field">
                    <label for="code" class="label">{{ trans('words.code') }}</label>

                    <p class="control">
                        <input id="code" type="text" class="input {{ $errors->has('code') ? ' is-danger' : '' }}"
                               name="code"
                               value="{{ $manufacturer->code }}" required>

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
                        <input id="description" type="text"
                               class="input {{ $errors->has('description') ? ' is-danger' : '' }}"
                               name="description"
                               value="{{ $manufacturer->description }}" required>

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
                    <label for="active" class="label">{{ trans('words.active') }}</label>

                    <div class="control">
                        <div class="select">
                            <select name="active">
                                <option value="1" {{ $manufacturer->active ? 'selected' : '' }}>
                                    {{ trans('words.active') }}
                                </option>
                                <option value="0" {{ $manufacturer->active  ? '' : 'selected' }}>
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
