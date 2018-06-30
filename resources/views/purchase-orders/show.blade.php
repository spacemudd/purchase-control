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
                        <toggle-preview-purchase-order></toggle-preview-purchase-order>

						@if(!$purchase_order->is_draft)
								<a href="{{ route('purchase-orders.sub.create', ['id' => $purchase_order->id]) }}"
								   class="button is-small">
									New Sub-PO
								</a>
						@endif

						@can('delete-purchase-orders')
							@if($purchase_order->is_draft)
								<form class="button is-danger is-small" action="{{ route('purchase-orders.destroy', ['id' => $purchase_order->id]) }}" method="post">
									{{ csrf_field() }}
									<input type="hidden" name="_method" value="delete">
									<button type="submit" class="button is-danger is-small">Delete</button>
								</form>
							@endif
						@endcan

                        @can('create-purchase-orders')
                            @if($purchase_order->is_draft)
                                <form class="button is-warning is-small" action="{{ route('purchase-orders.save', ['id' => $purchase_order->id]) }}" method="post">
                                    {{ csrf_field() }}
                                    <button type="submit" class="button is-warning is-small">Save</button>
                                </form>
                            @endif
                        @endcan
					</div>
				</div>

                <preview-pdf-container url="{{ route('purchase-orders.pdf', ['id' => $purchase_order->id]) }}"
                                       show-type="PurchaseOrder/previewPdf"
                ></preview-pdf-container>

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
														:highlighted="{{ $purchase_order->is_draft ? 'true' : 'false' }}"
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
                                                             :highlighted="{{ $purchase_order->is_draft ? 'true' : 'false' }}"
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
                                                       :highlighted="{{ $purchase_order->is_draft ? 'true' : 'false' }}"
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
								<tr>
									<td><strong>Billing Address</strong></td>
									<td>
										<address-field-token :id.number="{{ $purchase_order->id }}"
															 name="billing_address_id"
															 value="{{ optional($purchase_order->billing_address_json)->location }}"
															 :highlighted="{{ $purchase_order->is_draft ? 'true' : 'false' }}"
															 placeholder="BILLING ADDRESS"
															 url="{{ route('purchase-orders.tokens', ['id' => $purchase_order->id]) }}"
															 search-url="{{ route('api.search.billing-addresses') }}"
															 :is-billing="true"
															 :can-edit="{{ $purchase_order->is_draft ? 'true' : 'false' }}"
										></address-field-token>
									</td>
								</tr>
								<tr>
									<td><strong>Shipping Address</strong></td>
									<td>
										<address-field-token :id.number="{{ $purchase_order->id }}"
															 name="shipping_address_id"
															 value="{{ optional($purchase_order->shipping_address_json)->location }}"
															 :highlighted="{{ $purchase_order->is_draft ? 'true' : 'false' }}"
															 placeholder="SHIPPING ADDRESS"
															 url="{{ route('purchase-orders.tokens', ['id' => $purchase_order->id]) }}"
															 search-url="{{ route('api.search.shipping-addresses') }}"
															 :can-edit="{{ $purchase_order->is_draft ? 'true' : 'false' }}"
										></address-field-token>
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
        <div class="column is-4">
            <h2 class="title is-5 has-text-weight-light">Delivery &amp; Terms</h2>
        </div>
    </div>
    <div class="columns">
        <div class="column is-8">
            <div class="box">
                @foreach($purchaseTermsTypes as $key => $type)
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
                                                          :can-toggle="{{ $purchase_order->is_draft ? 'true' : 'false' }}"
                                    >
                                        {{ $term->value }}
                                    </toggle-purchase-term>
                                </ul>
                            @endforeach
                        </div>
                    </div>
                    @if(!($key === count($purchaseTermsTypes) - 1))
                        <hr>
                    @endif
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
                    @if($purchase_order->is_draft)
                        <new-po-item-from-pr-button :po-id.number="{{ $purchase_order->id }}"></new-po-item-from-pr-button>
                    @endif
                </div>
            </div>

            {{-- Items --}}
            <div class="box">
                <purchase-order-items :po-id.number="{{ $purchase_order->id }}"></purchase-order-items>
            </div>
		</div>
	</div>

@endsection
