@extends('layouts.app', [
	'title' => $user->name . ' - ' . trans('words.role')
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
				<a href="{{ route('users.index') }}">
					<span>{{ __('words.users') }}</span>
				</a>
			</li>
			<li class="is-active">
				<a href="#">
					{{ $user->name }}
				</a>
			</li>
		</ul>
	</nav>
@endsection

@section('content')

		@can('edit-user-roles')
			<div class="columns is-multiline">
				<div class="column is-12">
					<h2 class="title is-4">{{ __('words.roles') }}</h2>
				</div>
				<div class="column is-4">
					@foreach($roles as $role)
						<toggle-role :user-id.number="{{ $user->id }}"
									 :role-id.number="{{ $role->id }}"
									 :enabled-prop.number="{{ $user->hasRole($role) ? 'true' : 'false' }}">
							{{ $role->name }}
						</toggle-role>
					@endforeach
				</div>
			</div>
		@endcan

@endsection
