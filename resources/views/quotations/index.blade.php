@extends('layouts.app', ['title' => 'Quotations'])

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
                <a href="{{ route('material-requests.index') }}">
                    <span class="icon is-small"><i class="fa fa-quote-right"></i></span>
                    <span>Quotations</span>
                </a>
            </li>
        </ul>
    </nav>
@endsection

@section('content')
<div class="columns is-multiline">
    <div class="column is-12">
        <div class="has-text-right">
            <a href="{{ route('quotations.create') }}" class="button is-primary is-small">Add new</a>
        </div>
    </div>
    <div class="column">
        <table class="table is-fullwidth is-bordered is-size-7 is-narrow">
        <thead>
        <tr>
        	<th width="200px">Code</th>
            <th>Vendor</th>
            <th width="100px">Items</th>
            <th width="100px">Total</th>
            <th width="100px">Status</th>
            <th width="100px"></th>
        </tr>
        </thead>
        	<tbody>
                @if ($quotations)
                    @foreach ($quotations as $quote)
                        <tr>
                            <td>{{ $quote->vendor_quotation_number }}</td>
                            <td>{{ $quote->vendor->display_name }}</td>
                            <td>{{ $quote->items()->count() }}</td>
                            <td class="has-text-right">{{ $quote->totalCost() }}</td>
{{--                            <td>{{ $request->cost_center->display_name }}</td>--}}
                            {{--<td>{{ $request->location->name }}</td>--}}
                            <td class="has-text-centered">{{ $quote->status_name }}</td>
                            <td class="has-text-centered">
                                <a href="{{ route('quotations.show', ['id' => $quote->id]) }}"
                                   class="button is-small is-warning"
                                   style="height:20px;"
                                >
                                    <span class="icon"><i class="fa fa-eye"></i></span>
                                    <span>View</span>
                                </a>
                                {{--<button class="button is-small is-warning">--}}
                                    {{--<span class="icon"><i class="fa fa-pencil"></i></span>--}}
                                    {{--<span>Edit</span>--}}
                                {{--</button>--}}

                                {{--<button class="button is-small is-primary">--}}
                                    {{--<span class="icon"><i class="fa fa-inbox"></i></span>--}}
                                    {{--<span>Mark as received</span>--}}
                                {{--</button>--}}

                                {{--<button class="button is-small">--}}
                                    {{--<span class="icon"><i class="fa fa-print"></i></span>--}}
                                    {{--<span>Print</span>--}}
                                {{--</button>--}}
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td class="has-text-centered is-italic" colspan="7" style="background-color:#f5f5f5;">No quotations.</td>
                    </tr>
                @endif
        	</tbody>
        </table>
    </div>
</div>
@endsection
