@extends('layouts.app', [
	'title' => $purchase_order->id . ' - ' .
			   trans('words.purchase-order')
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
				<a href="{{ route('purchase-orders.index') }}">
					<span class="icon is-small"><i class="fa fa-shopping-cart"></i></span>
					<span>{{ trans('words.purchase-orders') }}</span>
				</a>
			</li>

			<li class="is-active">
				<a href="{{ route('purchase-orders.show', ['id' => $purchase_order->id]) }}">
					{{ $purchase_order->number ? $purchase_order->number : $purchase_order->id }}
				</a>
			</li>
		</ul>
	</nav>
@endsection


@section('content')

	{{--<purchase-order-show :purchase-order-id.number="{{ $purchase_order->id  }}"></purchase-order-show>--}}

	<div class="columns">
		<div class="column is-8">
			<div class="box">
				<div class="columns">
					<div class="column is-6">
						{{-- todo: notification/subscription bell --}}
						<h1 class="title">
							@if($purchase_order->number)
								{{ $purchase_order->number }}
							@else
								<span class="has-text-grey-lighter">Draft</span>
							@endif
						</h1>
						<p class="subtitle is-6">
							Purchase Order Number
							@if(!$purchase_order->number)
								<b-tooltip label="Generated when saved"><span class="icon is-small"><i class="fa fa-question-circle"></i></span></b-tooltip>
							@endif
						</p>
					</div>

					{{-- Options --}}
					<div class="column has-text-right">

					</div>
				</div>

				<hr>

				{{-- Resource' table info --}}
				<div class="columns">
					<div class="column is-6">
						<table class="table is-size-7 is-fullwidth">
							<colgroup>
								<col width="50%">
							</colgroup>
							<tbody>
								<tr>
									<td><strong>Date</strong></td>
									<td>
										<datetime-token :id.number="{{ $purchase_order->id }}"
														name="date"
														value="{{ $purchase_order->date_string }}"
														:highlighted="true"
														placeholder="PURCHASE ORDER DATE"
														url="{{ route('purchase-orders.tokens', ['id' => $purchase_order->id]) }}"
										></datetime-token>
									</td>
								</tr>
								<tr>
									<td><strong>Supplier</strong></td>
									<td>
										<edit-supplier-token name="vendor_id"
															 value="{{  $purchase_order->vendor_json_display_name }}"
															 :highlighted="true"
															 placeholder="SUPPLIER ID"
															 url="{{ route('purchase-orders.tokens', ['id' => $purchase_order->id]) }}"
										></edit-supplier-token>
									</td>
								</tr>
								<tr>
									<td><strong>Delivery Date</strong> <b-tooltip label="To supply the items not later than..."><span class="icon is-small"><i class="fa fa-question-circle"></i></span></b-tooltip></td>
									<td>
										<delivery-date-token :id.number="{{ $purchase_order->id }}"
														name="delivery_date"
														value="{{ $purchase_order->delivery_date_string }}"
														:highlighted="true"
														placeholder="DELIVERY DATE DATE"
														url="{{ route('purchase-orders.tokens', ['id' => $purchase_order->id]) }}"
										></delivery-date-token>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="column is-6">
						<table class="table is-size-7 is-fullwidth">
							<colgroup>
								<col width="50%">
							</colgroup>
							<tbody>
								<tr>
									<td><strong>Created by</strong></td>
									<td>
										@if($purchase_order->created_by)
											{{ optional($purchase_order->created_by)->username }} - {{ optional($purchase_order->created_by)->name }}
										@endif
									</td>
								</tr>
								<tr>
									<td>
										<strong>Status</strong>
									</td>
									<td class="is-capitalized">
										{{ $purchase_order->status_name }}
										@if($purchase_order->isDraft)
											<span class="circle is-warning"></span>
										@endif
										@if($purchase_order->isSaved)
											<span class="circle is-warning"></span>
										@endif
										@if($purchase_order->isApproved)
											<span class="circle is-success"></span>
										@endif
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>

		{{-- Resource side info --}}
		<div class="column">
			{{-- Notes section --}}
			<notes-container url="{{ route('api.purchase-orders.notes', ['id' => $purchase_order->id]) }}"
							 resource-id.number="{{ $purchase_order->id }}"
			>
			</notes-container>

			{{-- Uploads section --}}
			<uploads-container url="{{ route('api.purchase-orders.uploads', ['id' => $purchase_order->id]) }}"
							   resource-id.number="{{ $purchase_order->id }}"
			>
			</uploads-container>
		</div>

	</div>

	{{-- Delivery & Terms --}}
	<div class="columns">
		<div class="column is-8">
			<h2 class="title is-5 has-text-weight-light">Delivery &amp; Terms</h2>
			<div class="box">
				@foreach($purchaseTermsTypes as $type)
					<div class="columns">
						<div class="column is-4">
							<p class="title is-7">{{ $type->name }}</p>
						</div>
						<div class="column">
							@foreach($type->terms()->get() as $term)
								<ul>
									<toggle-purchase-term :term-id.number="{{ $term->id }}"
														  :po-id.number="{{ $purchase_order->id }}"
														  :enabled-prop.number="{{ $purchase_order->terms->contains($term->id) ? 'true' : 'false' }}"
									>
										{{ $term->value }}
									</toggle-purchase-term>
								</ul>
							@endforeach
						</div>
					</div>
					<hr>
				@endforeach
			</div>
		</div>

	</div>

	{{-- Items --}}
	<div class="columns">
		<div class="column is-8">
            <div class="columns">
                <div class="column">
                    <h2 class="title is-5 has-text-weight-light">Items</h2>
                </div>
                <div class="column has-text-right">
                    <new-po-item-from-pr-button :po-id.number="{{ $purchase_order->id }}"></new-po-item-from-pr-button>
                </div>
            </div>

            {{-- Items --}}
            <div class="box">
                <table class="table is-fullwidth">
                <thead>
                <tr>
                	<th>#</th>
                    <th>Code</th>
                    <th>Description</th>
                    <th>Unit Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                    <th>Tax</th>
                    <th>Total</th>
                </tr>
                </thead>
                	<tbody>
                			<tr>
                				<td></td>
                			</tr>
                	</tbody>
                </table>
            </div>
		</div>
	</div>

@endsection
