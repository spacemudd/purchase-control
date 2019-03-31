@extends('layouts.app', ['title' => 'Material Requests'])

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
                    <span class="icon is-small"><i class="fa fa-shopping-cart"></i></span>
                    <span>Material Requests</span>
                </a>
            </li>
        </ul>
    </nav>
@endsection

@section('content')
<div class="columns is-multiline">
    <div class="column is-12">
        <div class="has-text-right">
            <a href="{{ route('material-requests.create') }}" class="button is-primary is-small">Add new</a>
            <a href="{{ route('material-requests.all-excel') }}" class="button is-small has-icon">
                <span class="icon"><i class="fa fa-file-excel-o"></i></span>
                <span>Excel</span>
            </a>
        </div>
    </div>
    <div class="column">
        <table class="table is-fullwidth is-bordered is-size-7">
        <thead>
        <tr>
        	<th>Code</th>
            {{--<th>Lines</th>--}}
            <th>Cost Center</th>
            <th>Location</th>
            <th width="80px">Status</th>
            <th width="100px"></th>
        </tr>
        </thead>
        	<tbody>
                @if($mRequests)
                    @foreach ($mRequests as $request)
                        <tr>
                            <td>{{ $request->number }}</td>
                            <td>{{ $request->cost_center->display_name }}</td>
                            <td>{{ $request->location->name }}</td>
                            <td><span class="tag{{ $request->status_css_class }}">{{ $request->status_name }}</span></td>
                            <td class="has-text-right">
                                <a href="{{ route('material-requests.show', ['id' => $request->id]) }}" class="button is-small is-warning">
                                    <span class="icon"><i class="fa fa-eye"></i></span>
                                    <span>View</span>
                                </a>

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
                        <td class="has-text-centered is-italic" colspan="7" style="background-color:#f5f5f5;">No material requests.</td>
                    </tr>
                @endif
        	</tbody>
        </table>
    </div>
</div>
@endsection
