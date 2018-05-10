@extends('layouts.app', ['title' => trans('words.addresses')])

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
				<a href="{{ route('addresses.index') }}">
					<span class="icon is-small"><i class="fa fa-map-marker"></i></span>
					<span>{{ trans('words.addresses') }}</span>
				</a>
			</li>
		</ul>
	</nav>
@endsection

@section('content')

	<div class="columns">
		<div class="column is-4">
			<p class="title is-6">
				<b>{{ trans('words.inactive') }} {{ trans('words.addresses') }}</b>
			</p>

			<div class="notification is-warning">
				<p class="subtitle is-7">
					<b>{{ $deletedAddressesCounter }}</b>
				</p>
			</div>
		</div>

		<div class="column is-4">
			<p class="title is-6">
				<b>{{ trans('words.active') }} {{ trans('words.addresses') }}</b>
			</p>

			<div class="notification is-success">
				<p class="subtitle is-7">
					<b>{{ $activeAddressesCounter }}</b>
				</p>
			</div>
		</div>
	</div>

	@can('create-addresses')
		<div class="columns">
			<div class="column">
				<a href="{{ route('addresses.create') }}" class="button is-pulled-right is-primary">New Address</a>
			</div>
		</div>
	@endcan

	<div class="columns">
		<div class="column">
		<table class="table is-fullwidth">
			<thead>
			<tr>
				<th>Location</th>
				<th>Department</th>
				<th>Contact Name</th>
				<th>Phone</th>
				<th>Email</th>
				<th>Type</th>
				<th></th>
			</tr>
			</thead>
				<tbody>
				@foreach($addresses as $address)
						<tr>
							<td>{{ $address->location }}</td>
							<td>{{ $address->department }}</td>
							<td>{{ $address->contact_name }}</td>
							<td>{{ $address->phone }}</td>
							<td>{{ $address->email }}</td>
							<td>{{ $address->type_human }}</td>
							<td class="has-text-right">
								<a href="{{ route('addresses.show', ['id' => $address->id]) }}" class="button is-small is-text">
									View
								</a>
								{{--
								<a href="{{ route('addresses.edit', ['id' => $address->id]) }}" class="button is-small is-text">
									Edit
								</a>
								--}}
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
@endsection
