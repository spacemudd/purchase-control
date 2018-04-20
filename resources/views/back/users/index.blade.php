@extends('layouts.app', [
	'title' => __('words.users')
])

@section('header')
	<nav class="breadcrumb" aria-label="breadcrumbs">
		<ul>
			<li>
				<a href="{{ route('dashboard.index') }}" aria-current="page">
					<span class="icon is-small"><i class="fa fa-inbox"></i></span>
					<span>{{ __('words.dashboard') }}</span>
				</a>
			</li>
			<li class="is-active">
				<a href="{{ route('users.index') }}">
					<span>{{ __('words.users') }}</span>
				</a>
			</li>
		</ul>
	</nav>
@endsection

@section('content')
	<div class="columns is-multiline">
		{{--@can('new-role')--}}
			{{--<div class="column is-12 has-text-right">--}}
				{{--<a href="{{ route('roles.create') }}" class="button is-primary">{{ __('words.new-role') }}</a>--}}
			{{--</div>--}}
		{{--@endcan--}}
		<div class="column is-6 is-offset-3">
			<table class="table is-fullwidth">
				<thead>
					<tr>
						<th>{{ __('auth.username') }}</th>
						<th>{{ __('words.name') }}</th>
						<th>{{ __('words.created-at') }}</th>
						<th class="has-text-right">{{ __('words.actions') }}</th>
					</tr>
				</thead>
				<tbody>
					@foreach($users as $user)
						<tr>
							<td>{{ $user->username }}</td>
							<td>{{ $user->name }}</td>
							<td>{{ $user->created_at }}</td>
							<td class="has-text-right">
								@can('edit-roles')
									<a class="button is-primary is-small" href="{{ route('users.show', ['id' => $user->id]) }}">
										<span class="icon is-small"><i class="fa fa-pencil"></i></span>
										<span>{{ __('words.edit') }}</span>
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
