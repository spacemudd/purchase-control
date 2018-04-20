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
			<li class="is-active">
				<a href="{{ route('roles.index') }}">
					<span class="icon is-small"><i class="fa fa-user-circle-o"></i></span>
					<span>{{ trans('words.roles') }}</span>
				</a>
			</li>
		</ul>
	</nav>
@endsection

@section('content')
	@if(session()->has('message'))
		@component('components.alerts.basic', ['status' => (session()->has('status') == null) ? 'default' : session()->get('status')])
			{{ session()->get('message') }}
		@endcomponent
	@endif

	<div class="columns is-multiline">
		{{--@can('new-role')--}}
			<div class="column is-12 has-text-right">
				<a href="{{ route('roles.create') }}" class="button is-primary">{{ __('words.new-role') }}</a>
			</div>
		{{--@endcan--}}
		<div class="column is-6 is-offset-3">
			<table class="table is-fullwidth">
				<thead>
					<tr>
						<th>{{ trans('words.name') }}</th>
						<th class="has-text-right">{{ trans('words.actions') }}</th>
					</tr>
				</thead>
				<tbody>
					@foreach($roles as $role)
						<tr>
							<td>{{ $role->name }}</td>
							<td class="has-text-right">
								@can('edit-roles')
									<a class="button is-primary is-small" href="{{ route('roles.show', ['id' => $role->id]) }}">
										<span class="icon is-small"><i class="fa fa-pencil"></i></span>
										<span>{{ trans('words.edit') }}</span>
									</a>
								@endcan
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
@endsection
