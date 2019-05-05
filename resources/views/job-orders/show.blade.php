@extends('layouts.app', ['title' => $jobOrder->job_order_number.' - Job orders'])

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
                <a href="{{ route('job-orders.index') }}">
                    <span class="icon is-small"><i class="fa fa-shopping-cart"></i></span>
                    <span>Job Orders</span>
                </a>
            </li>
            <li class="is-active">
                <a href="#">
                    {{ $jobOrder->job_order_number }}
                </a>
            </li>
        </ul>
    </nav>
@endsection

@section('content')
<div class="columns">
    <div class="column is-8 is-offset-2">
        <div class="box">
            <div class="columns">
                <div class="column is-6">
                    <span style="margin-left:0" class="tag">{{ $jobOrder->status }}</span>
                    <p class="is-uppercase"><b>Job Order details</b></p>
                </div>
                <div class="column is-6 has-text-right">
                    @if (!$jobOrder->approved_at)
                        {{-- <form action="{{ route('job-orders.approve', $jobOrder) }}"
                              method="post"
                              class="is-inline">
                            @csrf
                            <button class="button is-success is-small" type="submit">Approve</button>
                        </form> --}}
                    @endif
                    {{-- <a href="{{ route('job-orders.excel', $jobOrder) }}"
                       class="button has-icon is-small">
                        <span class="icon"><i class="fa fa-file-excel-o"></i></span>
                        <span>Excel</span>
                    </a> --}}
                </div>
            </div>

            <form class="form" style="margin-top:2rem">
                @csrf
                <div class="field">
                    <label for="date" class="label">Date</label>

                    <p class="control">
                        <input id="date" type="text" class="input" value="{{ $jobOrder->date->format('d-m-Y') }}" readonly>

                        @if ($errors->has('date'))
                            <span class="help is-danger">
                                {{ $errors->first('date') }}
                            </span>
                        @endif
                    </p>
                </div>

                <div class="field">
                    <label for="location" class="label">Location</label>

                    <div class="control">
                        <input type="text" class="input" value="{{ $jobOrder->location->name }}" readonly>
                    </div>
                </div>

                <div class="field">
                    <label for="employee" class="label">Employee</label>

                    <div class="control">
                        <input type="text" class="input" value="{{ $jobOrder->employee->name }}" readonly>
                    </div>
                </div>

                <div class="field">
                    <div class="field">
                        <label for="cost_center_id" class="label">Cost Center</label>

                        <div class="control">
                            <input type="text" class="input" value="{{ $jobOrder->department->name }}" readonly>
                        </div>
                    </div>
                </div>

                <div class="field">
                    <div class="field">
                        <label for="requested_through_type" class="label">Requested through</label>

                        <div class="control">
                            <input type="text" class="input" value="{{ $jobOrder->requested_through_type }}" readonly>
                        </div>
                    </div>
                </div>

                <div class="field">
                    <div class="field">
                        <label for="ext" class="label">Ext</label>

                        <div class="control">
                            <input type="text" class="input" value="{{ $jobOrder->ext }}" readonly>
                        </div>
                    </div>
                </div>

                <div class="field">
                    <div class="field">
                        <label for="job_description" class="label">Job Description</label>

                        <div class="control">
                            <textarea class="textarea" readonly>{{ $jobOrder->job_description }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="field">
                    <label for="job_duration" class="label">Job duration <span class="has-text-danger">*</span></label>
                    <div class="control">
                        <div class="columns">
                            <div class="column">
                                <input class="input" type="time" value="{{ $jobOrder->time_start->format('H:i') }}" readonly></input>
                            </div>
                            <div class="column">
                                <input class="input" type="time" value="{{ $jobOrder->time_end->format('H:i') }}" readonly></input>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="field">
                    <div class="field">
                        <label for="materials_used" class="label">Materials Used</label>

                        <div class="control">
                            <textarea class="textarea" readonly>{{ $jobOrder->materials_used }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="field">
                    <div class="field">
                        <label for="status" class="label">Status</label>

                        <div class="control">
                            <input class="input" type="text" value="{{ $jobOrder->status }}" readonly></input>
                        </div>
                    </div>
                </div>

                <div class="field">
                    <div class="field">
                        <label for="remark" class="label">Remark</label>

                        <div class="control">
                            <textarea class="textarea" readonly>{{ $jobOrder->remark }}</textarea>
                        </div>
                    </div>
                </div>
                
            </form>
        </div>
    </div>
</div>

{{--<div class="columns">--}}
    {{--<div class="column is-8 is-offset-2">--}}
        {{--<div class="box">--}}
            {{--<material-request-items-container--}}
                    {{--:can-edit="{{ $jobOrder->can_edit ? 'true' : 'false' }}"--}}
                    {{--:material-request-id.number="{{ $jobOrder->id }}">--}}
            {{--</material-request-items-container>--}}
        {{--</div>--}}
    {{--</div>--}}
{{--</div>--}}
@endsection
