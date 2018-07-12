@extends('layouts.app', [
	'title' => 'Draft Purchase Orders'
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
                <a href="#">
                    Draft
                </a>
            </li>
        </ul>
    </nav>
@endsection

@section('content')
    <section class="hero">
        <div class="hero-body">
            <div class="container has-text-centered">
                <h1 class="title is-5">Draft</h1>
                <h2 class="subtitle is-7">Purchase Orders</h2>
            </div>
        </div>
    </section>
    <div class="columns">
        <div class="column is-12">
            <table class="table is-fullwidth is-narrow is-size-7">
                <thead>
                <tr>
                    <th>System ID</th>
                    <th>Supplier</th>
                    <th>Created by</th>
                    <th>Updated at</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @if($purchaseOrders->total() == 0)
                    <tr>
                        <td class="has-text-centered" style="line-height:50px;" colspan="6">No data to display</td>
                    </tr>
                @endif
                @foreach($purchaseOrders as $po)
                    <tr>
                        <td>
                            <a href="{{ $po->link }}">{{ $po->id }}</a>
                        </td>
                        <td>{{ optional($po->vendor)->name }}</td>
                        <td>{{ optional($po->created_by)->username . ' - ' . optional($po->created_by)->name }}</td>
                        <td>{{ $po->updated_at }}</td>
                        <td class="has-text-centered"><a href="{{ $po->link }}" class="button is-small is-primary">Show</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            {{ $purchaseOrders->links('vendor.pagination.bulma') }}
        </div>
    </div>
@endsection
