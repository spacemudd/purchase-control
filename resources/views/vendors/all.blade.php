@extends('layouts.app', ['title' => trans('words.vendors')])

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
				<a href="{{ route('vendors.index') }}">
					<span class="icon is-small"><i class="fa fa-truck"></i></span>
					<span>{{ trans('words.vendors') }}</span>
				</a>
			</li>
			<li class="is-active">
				<a href="#">{{ __('words.all') }}</a>
			</li>
		</ul>
	</nav>
@endsection

@section('content')

	<div class="columns">
		<div class="column is-12">
			<table class="table is-fullwidth is-size-7">
			<thead>
				<tr>
					<th>{{ __('words.code') }}</th>
					<th>{{ __('words.name') }}</th>
					<th>{{ __('words.telephone-number') }}</th>
					<th>{{ __('words.fax-number') }}</th>
					<th>{{ __('auth.email') }}</th>
					<th>{{ __('words.website') }}</th>
					<th>{{ __('words.representatives') }}</th>
					<th>{{ __('words.updated-at') }}</th>
					<th></th>
				</tr>
			</thead>
				<tbody>
					@foreach($vendors as $vendor)
						<tr>
							<td><a href="{{ route('vendors.show', ['id' => $vendor->id]) }}">{{ $vendor->id }}</a></td>
							<td>{{ $vendor->name }}</td>
							<td>{{ $vendor->telephone_number }}</td>
							<td>{{ $vendor->fax_number }}</td>
							<td><a href="mailto:{{ $vendor->email }}">{{ $vendor->email }}</a></td>
							<td><a target="_blank" rel="noopener noreferrer" href="{{ $vendor->website_link }}">{{ $vendor->website }}</a></td>
							<td>{{ $vendor->reps->count() }}</td>
							<td>{{ $vendor->updated_at }}</td>
							<td class="has-text-right">
								<a href="{{ route('vendors.show', ['id' => $vendor->id]) }}" class="button is-small has-icon">
									<span class="icon is-small"><i class="fa fa-eye"></i></span>
									<span>{{ __('words.show') }}</span>
								</a>
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>

@endsection
