@extends('layouts.app')

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
                <a href="{{ route('purchase-orders.show', ['id' => $purchaseOrder->id]) }}">
                    @if($purchaseOrder->number)
                        {{ $purchaseOrder->number }}
                    @else
                        {{ $purchaseOrder->id }}
                    @endif
                </a>
            </li>
            <li class="is-active">
                <a href="#">Sub Purchase Orders</a>
            </li>
        </ul>
    </nav>
@endsection

@section('content')

    <section class="hero">
        <div class="hero-body">
            <div class="container has-text-centered">
                <h1 class="title is-5">Sub Purchase Orders</h1>
            </div>
        </div>
    </section>
    <div class="columns">
        <div class="column is-12">
            <table class="table is-fullwidth is-narrow is-size-7">
                <colgroup>
                    <col style="width:1%">
                    <col style="width:5%">
                    <col style="width:20%">
                    <col style="width:5%">
                    <col style="width:5%">
                    <col style="width:5%">
                    <col style="width:1%">
                </colgroup>
                <thead>
                <tr>
                    <th>System ID</th>
                    <th>Number</th>
                    <th>Supplier</th>
                    <th>Created by</th>
                    <th>Updated at</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @if( ! $purchaseOrder->items()->count())
                    <tr>
                        <td class="has-text-centered" style="line-height:50px;" colspan="6">No data to display</td>
                    </tr>
                @endif
                @foreach($purchaseOrder->sub_purchase_orders as $subPo)
                    <tr>
                        <td>
                            <a href="{{ route('purchase-orders.sub.show', ['purchase_order_id' => $purchaseOrder->id, 'id' => $subPo->id]) }}">{{ $subPo->id }}</a>
                        </td>
                        <td>{{ $subPo->number }}</td>
                        <td>{{ optional($subPo->vendor)->name }}</td>
                        <td>{{ optional($subPo->created_by)->username . ' - ' . optional($subPo->created_by)->name }}</td>
                        <td>{{ $subPo->updated_at }}</td>
                        <td>
                            <a href="{{ route('purchase-orders.sub.show', ['purchase_order_id' => $purchaseOrder->id, 'id' => $subPo->id]) }}"
                               class="button is-small is-primary">View</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

@stop
