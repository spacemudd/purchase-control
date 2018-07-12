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

	@can('create-addresses')
		<div class="columns">
			<div class="column">
				<a href="{{ route('addresses.create') }}" class="button is-pulled-right is-primary">New Address</a>
			</div>
		</div>
	@endcan

	<div class="columns">
		<div class="column is-5">
			<p class="title is-5">Shipping Addresses</p>
			<table class="table is-bordered is-fullwidth">
				<colgroup>
					<col style="width:50%;">
				</colgroup>
				<tbody>
				@foreach($shippingAddresses as $shippingAddress)
					<tr>
						<td>
							<table class="table is-borderless is-xs-narrow is-fullwidth">
								<colgroup>
									<col style="width:50%;">
									<col>
									<col width="80px">
								</colgroup>
								<tbody>
									<tr>
										<td>Location</td>
										<td class="has-text-right">{{ $shippingAddress->location }}</td>
										<td class="has-text-right">
											<a href="{{ route('addresses.show', ['id' => $shippingAddress->id]) }}"
											   class="is-small is-primary">
												Show
											</a>
										</td>
									</tr>
									<tr>
										<td>Department</td>
										<td class="has-text-right">{{ $shippingAddress->department }}</td>
									</tr>
									<tr>
										<td>Contact Name</td>
										<td class="has-text-right">{{ $shippingAddress->contact_name }}</td>
									</tr>
									<tr>
										<td>Phone</td>
										<td class="has-text-right">{{ $shippingAddress->phone }}</td>
									</tr>
									<tr>
										<td>Email</td>
										<td class="has-text-right">{{ $shippingAddress->email }}</td>
									</tr>
								</tbody>
							</table>
						</td>
				</tr>
				@endforeach
				</tbody>
			</table>
		</div>
		<div class="column is-5 is-offset-2">
			<p class="title is-5">Billing Addresses</p>
			<table class="table is-bordered is-fullwidth">
				<colgroup>
					<col style="width:50%;">
				</colgroup>
				<tbody>
				@foreach($billingAddresses as $billingAddress)
					<tr>
						<td>
							<table class="table is-borderless is-xs-narrow is-fullwidth">
								<colgroup>
									<col style="width:50%;">
									<col>
									<col width="80px">
								</colgroup>
								<tbody>
								<tr>
									<td>Location</td>
									<td class="has-text-right">{{ $billingAddress->location }}</td>
									<td class="has-text-right">
										<a href="{{ route('addresses.show', ['id' => $shippingAddress->id]) }}"
										   class="is-small is-primary">
											Show
										</a>
									</td>
								</tr>
								<tr>
									<td>Department</td>
									<td class="has-text-right">{{ $billingAddress->department }}</td>
								</tr>
								<tr>
									<td>Contact Name</td>
									<td class="has-text-right">{{ $billingAddress->contact_name }}</td>
								</tr>
								<tr>
									<td>Phone</td>
									<td class="has-text-right">{{ $billingAddress->phone }}</td>
								</tr>
								<tr>
									<td>Email</td>
									<td class="has-text-right">{{ $billingAddress->email }}</td>
								</tr>
								</tbody>
							</table>
						</td>
				</tr>
				@endforeach
				</tbody>
			</table>
		</div>
	</div>
@endsection
