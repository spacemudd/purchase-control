@extends('layouts.app', [
	'title' => 'Committed Purchase Orders'
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
                    Committed
                </a>
            </li>
        </ul>
    </nav>
@endsection

@section('content')
    <section class="hero">
        <div class="hero-body">
            <div class="container has-text-centered">
                <h1 class="title is-5">Committed</h1>
                <h2 class="subtitle is-7">Purchase Orders</h2>
            </div>
        </div>
    </section>
    <div class="columns">
        <div class="column is-12">
            <table class="table is-fullwidth is-narrow is-size-7">
                <thead>
                <tr>
                    <th>Purchase Order Number</th>
                    <th>Department</th>
                    <th>Employee</th>
                    <th>Created by</th>
                    <th>Created at</th>
                    <th>Updated at</th>
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
                            <a href="{{ route('purchase-orders.show', ['id' => $record->id]) }}">{{ $record->order_number }}</a>
                        </td>
                        <td>{{ $record->department->department_human }}</td>
                        <td>{{ $record->employee->display_name }}</td>
                        <td>{{ $record->created_by->display_name }}</td>
                        <td>{{ $record->created_at }}</td>
                        <td>{{ $record->updated_at }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            {{ $data->links('vendor.pagination.bulma') }}
        </div>
    </div>
@endsection
