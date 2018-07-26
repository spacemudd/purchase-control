@extends('layouts.app', [
	'title' => trans('words.purchase-orders')
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
				<a href="{{ route('purchase-orders.index') }}">
					<span class="icon is-small"><i class="fa fa-shopping-cart"></i></span>
					<span>{{ trans('words.purchase-orders') }}</span>
				</a>
			</li>
		</ul>
	</nav>
@endsection

@section('content')

	<div class="columns">
			<div class="column is-4">
				<p class="title is-6">
					{{ trans('words.draft') }} {{ trans('words.purchase-orders') }}
				</p>

				<a href="{{ route('purchase-orders.draft') }}">
					<div class="notification is-warning">
						<p class="subtitle is-7">
							<b>{{ $draftCounter }}</b>
						</p>
					</div>
				</a>
			</div>

			<div class="column is-4">
					<p class="title is-6">
						{{ trans('words.committed') }} {{ trans('words.purchase-orders') }}
					</p>

				<a href="{{ route('purchase-orders.committed') }}">
					<div class="notification is-success">
						<p class="subtitle is-7">
							<b>{{ $committedCounter }}</b>
						</p>
					</div>
				</a>
			</div>


			<div class="column is-4">
					<p class="title is-6">
						{{ trans('words.void') }} {{ trans('words.purchase-orders') }}
					</p>

				<a href="{{ route('purchase-orders.void') }}">
					<div class="notification is-danger">
						<p class="subtitle is-7">
							<b>{{ $voidCounter }}</b>
						</p>
					</div>
				</a>
			</div>
	</div>

	@can('create-purchase-orders')
		<div class="columns">
			<div class="column is-12 has-text-right">
				<a class="button is-primary" href="{{ route('purchase-orders.create') }}">New Purchase Order</a>
			</div>
		</div>
	@endcan

	{{--
	@can('view-purchase-orders')
		<div class="columns">
			<div class="column">
				<table class="table is-fullwidth is-narrow is-size-7">
					<colgroup>
						<col style="width:5%;">
						<col style="width:15%;">
						<col style="width:25%;">
						<col style="width:25%;">
						<col style="width:15%;">
						<col style="width:15%;">
					</colgroup>
					<thead>
					<tr>
						<th>ID</th>
						<th>Purchase Order Number</th>
						<th>Department</th>
						<th>Employee</th>
						<th>Created by</th>
						<th>Updated at</th>
						<th></th>
					</tr>
					</thead>
					<tbody>
					@if($data->total() == 0)
						<tr>
							<td class="has-text-centered" style="line-height:50px;" colspan="6">No data to display</td>
						</tr>
					@endif
					@foreach($data as $record)
						<tr>
							<td>
								<a href="{{ $record->link }}">{{ $record->id }}</a>
							</td>
							<td>
								<a href="{{ $record->link }}">{{ $record->number }}</a>
							</td>
							<td>{{ optional($record->cost_center)->display_name }}</td>
							<td>{{ optional($record->requested_by_employee)->display_name }}</td>
							<td>{{ $record->updated_at }}</td>
							<td>{{ optional($record->created_by)->display_name }}</td>
							<td class="has-text-right"><a href="{{ $record->link }}" class="button is-small">Show</a></td>
						</tr>
					@endforeach
					</tbody>
				</table>

				{{ $data->links('vendor.pagination.bulma') }}
			</div>
		</div>
	@endcan
	--}}

@endsection
