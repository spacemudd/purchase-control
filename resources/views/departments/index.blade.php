@extends('layouts.app', ['title' => trans('words.departments')])

@section('header')
	<nav class="breadcrumb" aria-label="breadcrumbs">
		<ul>
			<li>
				<a href="{{ route('dashboard.index') }}" aria-current="page">
					<span class="icon is-small"><i class="fa fa-inbox"></i></span>
					<span>{{ trans('words.dashboard') }}</span>
				</a>
			</li>
			<li class="is-active">
				<a href="{{ route('departments.index') }}">
					<span class="icon is-small"><i class="fa fa-building"></i></span>
					<span>{{ trans('words.departments') }}</span>
				</a>
			</li>
		</ul>
	</nav>
@endsection

@section('content')

	<div class="columns">
		<div class="column is-4">
			<p class="title is-6">
				<b>{{ trans('words.inactive') }} {{ trans('words.departments') }}</b>
			</p>

			<div class="notification is-warning">
				<p class="subtitle is-7">
					<b>{{ $inactiveDepartments }}</b>
				</p>
			</div>
		</div>

		<div class="column is-4">
			<p class="title is-6">
				<b>{{ trans('words.active') }} {{ trans('words.departments') }}</b>
			</p>

			<div class="notification is-success">
				<p class="subtitle is-7">
					<b>{{ $activeDepartments }}</b>
				</p>
			</div>
		</div>
	</div>

	<departments>
		<div slot="options">
			@can('create-departments')
				<a class="button is-primary pull-right" href="{{ route('departments.create') }}">
					{{ __('words.new-department') }}
				</a>
			@endcan
		</div>
	</departments>

@endsection
