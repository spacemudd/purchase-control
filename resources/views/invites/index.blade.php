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
				<a href="{{ route('invite') }}">
					<span>Invite</span>
				</a>
			</li>
		</ul>
	</nav>
@endsection

@section('content')
	<div class="columns is-multiline">
		<div class="column is-6 is-offset-3">
			<table class="table is-fullwidth">
				<thead>
					<tr>
						<th>ID</th>
						<th>{{ __('auth.username') }}</th>
						<th>{{ __('words.name') }}</th>
						<th>{{ __('words.created-at') }}</th>
						<th>Invite Link</th>
						<th>Delete</th>
					</tr>
				</thead>
				<tbody>
					@foreach($invites as $invite)
						<tr>
							<td>{{ $invite->id }}</td>
							<td>{{ $invite->username }}</td>
							<td>{{ $invite->name }}</td>
							<td>{{ $invite->created_at }}</td>
							<td><clipboard text="{{ route('invite.accept', ['token' => $invite->token]) }}"></clipboard></td>
							<td>
								<form action="{{ route('invite.destroy', ['id' => $invite->id]) }}" method="post">
									{{ csrf_field() }}
									<input type="hidden" name="_method" value="delete">
									<button class="button is-text">Delete</button>
								</form>
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
@endsection
