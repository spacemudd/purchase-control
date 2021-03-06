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
		{{--@can('invite-users')--}}
			<div class="column is-6 is-offset-3 has-text-right">
				<a href="{{ route('users.invite') }}" class="button is-primary">Invite</a>
				<a href="{{ route('invite') }}" class="button is-primary">Show all invites</a>
			</div>
		{{--@endcan--}}
		<div class="column is-6 is-offset-3">
			<table class="table is-fullwidth">
				<thead>
					<tr>
						<th>{{ __('auth.username') }}</th>
						<th>{{ __('words.name') }}</th>
						<th>Role</th>
						<th>Updated at</th>
						<th class="has-text-right">{{ __('words.actions') }}</th>
					</tr>
				</thead>
				<tbody>
					@foreach($users as $user)
						<tr>
							<td>{{ $user->username }}</td>
							<td>{{ $user->name }}</td>
							<td>
								<ul>
								@foreach($user->roles as $role)
									<li>{{ $role->name }}</li>
								@endforeach
								</ul>
							</td>
							<td>{{ $user->updated_at }}</td>
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
