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
					@if($purchase_order->number)
						{{ $purchase_order->number }}
					@else
						{{ $purchase_order->id }}
					@endif
				</a>
			</li>
		</ul>
	</nav>
@endsection


@section('content')

	<purchase-order-show :purchase-order-id.number="{{ $purchase_order->id  }}"></purchase-order-show>

@endsection
