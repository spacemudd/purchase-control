@extends('layouts.app', ['title' => $salesTax->display_name . ' - Sales Taxes'])

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
                <a href="{{ route('sales-taxes.index') }}">
                    <span class="icon is-small"><i class="fa fa-balance-scale"></i></span>
                    <span>Sales Taxes</span>
                </a>
            </li>
            <li>
                <a href="#">
                    {{ $salesTax->display_name }}
                </a>
            </li>
        </ul>
    </nav>
@endsection

@section('content')

    <div class="columns">
        <div class="column is-6 is-offset-3 has-text-right">
            <form action="{{ route('sales-taxes.destroy', ['id' => $salesTax->id]) }}" method="post" class="is-inline">
                {{ csrf_field() }}
                <input type="hidden" name="_method" value="delete">
                <button class="button is-small is-danger">Archive</button>
            </form>
        </div>
    </div>

    <div class="columns">
        <div class="column is-4 is-offset-4">
            <div class="panel">
                <div class="panel-heading">{{ $salesTax->display_name }}</div>
                <div class="panel-block">
                    <table class="table is-fullwidth">
                    	<tbody>
                        <tr>
                            <td><strong>Tax Name</strong></td>
                            <td class="has-text-right">{{ $salesTax->tax_name }}</td>
                        </tr>
                        <tr>
                            <td><strong>Abbreviation</strong></td>
                            <td class="has-text-right">{{ $salesTax->abbreviation }}</td>
                        </tr>
                        <tr>
                            <td><strong>Description</strong></td>
                            <td class="has-text-right">{{ $salesTax->description }}</td>
                        </tr>
                        <tr>
                            <td><strong>Current Tax Rate</strong></td>
                            <td class="has-text-right">{{ $salesTax->clean_tax_rate }}</td>
                        </tr>
                    	</tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
