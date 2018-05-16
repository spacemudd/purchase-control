@extends('layouts.app', ['title' => $subPurchaseOrder->display_name . '- Sub Purchase Order'])

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
            <li>
                <a href="{{ route('purchase-orders.show', ['id' => $subPurchaseOrder->purchase_order_main_id]) }}">
                    {{ $subPurchaseOrder->main_purchase_order->number }}
                </a>
            </li>
            <li>
                <a href="{{ route('purchase-orders.sub.index', ['purchase_order_id' => $subPurchaseOrder->purchase_order_main_id]) }}">
                    Sub Purchase Orders
                </a>
            </li>
            <li>
                <a href="{{ route('purchase-orders.sub.show', ['purchase_order_id' => $subPurchaseOrder->purchase_order_main_id, 'id' => $subPurchaseOrder->id]) }}">
                    {{ $subPurchaseOrder->display_name }}
                </a>
            </li>
        </ul>
    </nav>
@endsection

@section('content')



@stop
