@extends('layouts.app', ['title' => 'Sales Taxes'])

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
				<a href="{{ route('sales-taxes.index') }}">
					<span class="icon is-small"><i class="fa fa-balance-scale"></i></span>
					<span>Sales Taxes</span>
				</a>
			</li>
		</ul>
	</nav>
@endsection

@section('content')
	@can('create-sales-taxes')
		<div class="columns">
			<div class="column has-text-right">
				<a href="{{ route('sales-taxes.create') }}" class="button is-primary">New Sales Tax</a>
			</div>
		</div>
	@endcan
	<div class="columns">
		<div class="column is-4">
			<div class="panel">
				<div class="panel-heading">
						Sales Taxes
				</div>
				@can('create-sales-taxes')
				@if(!$salesTaxes->count())
					<div class="panel-block">
						<div style="width:100%;" class="is-full has-text-centered is-fullwidth is-italic">
							<p class="has-text-grey">No sales taxes.</p><br/>
							<a class="is-size-6" href="{{ route('sales-taxes.create') }}">New Sales Tax</a>
						</div>
					</div>
				@endif
				@endcan
				@foreach($salesTaxes as $saleTax)
					<div class="panel-block">
						<a href="{{ route('sales-taxes.show', ['id' => $saleTax->id]) }}">{{ $saleTax->display_name }}</a>
					</div>
				@endforeach
			</div>
		</div>
	</div>
@endsection
