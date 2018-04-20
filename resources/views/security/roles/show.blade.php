@extends('layouts.app', [
	'title' => $role->name . ' - ' . trans('words.role')
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
				<a href="{{ route('roles.index'	) }}">
					<span class="icon is-small"><i class="fa fa-user-circle-o"></i></span>
					<span>{{ trans('words.roles') }}</span>
				</a>
			</li>
			<li class="is-active">
				<a href="#">
					{{ $role->name }}
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
		@foreach($permissionTypes as $type => $permissions)

			<div class="column is-12">
				<h1 class="title">{{ __('words.' . str_before($type, '.')) }}</h1>

				<section class="section">
					<div class="columns is-multiline">
						@foreach($permissions as $permission)
							<div class="column is-4">
								<toggle-permission :role-id.number="{{ $role->id }}"
												   :enabled-prop.number="{{ $role->hasPermissionTo($permission->name) ? 'true' : 'false' }}"
												   permission-name="{{ $permission->name }}">
									{{ __('words.' . $permission->name) }}
								</toggle-permission>
							</div>
						@endforeach
					</div>
				</section>

			</div>

		@endforeach
	</div>

@endsection
