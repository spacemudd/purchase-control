@extends('layouts.app', ['title' => 'Job Orders'])

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
                <a href="{{ route('job-orders.index') }}">
                    <span class="icon is-small"><i class="fa fa-jobOrder-right"></i></span>
                    <span>Job Orders</span>
                </a>
            </li>
        </ul>
    </nav>
@endsection

@section('content')
<div class="columns is-multiline">
    <div class="column is-12">
        <div class="has-text-right">
            <a href="{{ route('job-orders.create') }}" class="button is-primary is-small">Add new</a>
        </div>
    </div>
    <div class="column">
        <table class="table is-fullwidth is-bordered is-size-7 is-narrow">
        <thead>
        <tr>
        	<th>#</th>
            <th>Date</th>
            <th>Requester</th>
            <th>Employee ID</th>
            <th>Department</th>
            <th>Location</th>
            <th>Ext</th>
            <th>CC</th>
            <th>Requested Though</th>
            <th>Status</th>
            <th></th>
        </tr>
        </thead>
        	<tbody>
                @if ($jobOrders)
                    @foreach ($jobOrders as $jobOrder)
                        <tr>
                            <td>{{ $jobOrder->job_order_number }}</td>
                            <td>{{ $jobOrder->date->format('d-m-Y') }}</td>
                            <td>{{ $jobOrder->employee->name }}</td>
                            <td>{{ $jobOrder->employee->code }}</td>
                            <td>{{ $jobOrder->department->description }}</td>
                            <td>{{ $jobOrder->location->name }}</td>
                            <td>{{ $jobOrder->ext }}</td>
                            <td>{{ $jobOrder->department->code }}</td>
                            <td>{{ $jobOrder->requested_throught_type }}</td>
                            <td>{{ $jobOrder->status }}</td>
                            <td class="has-text-centered">
                                <div class="buttons">
                                    <a href="{{ route('job-orders.show', $jobOrder) }}"
                                       class="button is-small"
                                       style="height:20px;">
                                        <span class="icon"><i class="fa fa-print"></i></span>
                                        <span>Print</span>
                                    </a>
                                    <a href="{{ route('job-orders.show', $jobOrder) }}"
                                       class="button is-small is-warning"
                                       style="height:20px;">
                                            <span class="icon"><i class="fa fa-eye"></i></span>
                                            <span>View</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td class="has-text-centered is-italic" colspan="8" style="background-color:#f5f5f5;">No Job Orders.</td>
                    </tr>
                @endif
        	</tbody>
        </table>
    </div>
</div>
@endsection
