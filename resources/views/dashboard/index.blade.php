@extends('layouts.app', [
	'title' => trans('words.dashboard'),
	'activeMenu' => 'dashboard',
])

@section('header')
	<nav class="breadcrumb" aria-label="breadcrumbs">
		<ul>
			<li class="is-active"><a href="#" aria-current="page">{{ trans('words.dashboard') }}</a></li>
		</ul>
	</nav>
@endsection

@section('content')

	<div class="columns">
		<div class="column is-3">
			<div class="box ">
				<p>Stock</p>
				<p><b>{{ $stockCount }}</b></p>
			</div>
		</div>

		<div class="column is-3">
			<div class="box ">
				<p>RFQs</p>
				<p><b>{{ $rfqsCount }}</b></p>
			</div>
		</div>

		<div class="column is-3">
			<div class="box ">
				<p>POs</p>
				<p><b>{{ $purchaseOrdersCount }}</b></p>
			</div>
		</div>

		<div class="column is-3">
			<div class="box ">
				<p>Deliveries</p>
				<p><b>{{ $deliveriesCount }}</b></p>
			</div>
		</div>
	</div>


	<p style="margin-top: 5rem;"><span><i class="fa fa-exclamation-circle"></i></span> Stock room status</p>

	<div class="columns" style="margin-top: 20px;">
		<div class="column is-3">
			<div class="box 2 is-flex" style="justify-content:space-around;align-items:center">
				<div>
					<i class="fa fa-building-o fa-2x"></i>
				</div>
				<div class="">
					{{ $lowStockCount }}<br/>
					Low Stock
				</div>
			</div>
		</div>

		<div class="column is-3">
			<div class="box 2 is-flex" style="justify-content:space-around;align-items:center">
				<div>
					<i class="fa fa-building-o fa-2x"></i>
				</div>
				<div class="">
					{{ $lowStockCount }}<br/>
					Out of stock
				</div>
			</div>
		</div>
	</div>

	<p style="margin-top: 5rem;"><span><i class="fa fa-check-square-o"></i></span> Quick actions</p>

	<div class="columns" style="margin-top: 1rem;">
		<div class="column is-3">
			<a class="button is-warning is-fullwidth" style="min-height: 3rem;">
				<span class="icon"><i class="fa fa-shopping-cart"></i></span>
				<span>New Material Request</span>
			</a>
			<p style="font-size:10px;padding:2px">Start by new material request to enter items and materials to the system</p>
		</div>

		<div class="column is-3">
			<a class="button is-warning is-fullwidth" style="min-height: 3rem;">
				<span class="icon"><i class="fa fa-arrow-down"></i></span>
				<span>New Delivery</span>
			</a>
			<p style="font-size:10px;padding:2px">Record a new material delivery from the supplier.</p>
		</div>

		<div class="column is-3">
			<a class="button is-warning is-fullwidth" style="min-height: 3rem;">
				<span class="icon"><i class="fa fa-address-book-o"></i></span>
				<span>New Issuance</span>
			</a>
			<p style="font-size:10px;padding:2px">New issuance of material to an employee or department</p>
		</div>

		<div class="column is-3">
			<a class="button is-warning is-fullwidth" style="min-height: 3rem;">
				<span class="icon"><i class="fa fa-file-o"></i></span>
				<span>New Purchase Order</span>
			</a>
			<p style="font-size:10px;padding:2px">Create a purchase order for issuances</p>
		</div>
	</div>

@endsection
