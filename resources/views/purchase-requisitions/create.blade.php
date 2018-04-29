@extends('layouts.app', ['title' => 'New purchase requisition'])

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
                <a href="{{ route('purchase-requisitions.index') }}">
                    <span class="icon is-small"><i class="fa fa-file"></i></span>
                    <span>{{ __('words.purchase-requisitions') }}</span>
                </a>
            </li>
            <li class="is-active">
                <a href="">New</a>
            </li>
        </ul>
    </nav>
@endsection

@section('content')

    <form action="{{ route('purchase-requisitions.store') }}" method="post">
        {{ csrf_field() }}
        <div class="section">

            <div class="columns">
                <div class="column is-2">
                    <h1 class="title is-5">Requested by</h1>
                </div>
                <div class="column is-4">
                    <div class="field">
                        <label for="requested_by_id" class="label">Employee <span class="has-text-danger">*</span></label>

                        <p class="control">
                            <select-employee name="requested_by_id" url="{{ route('api.search.employee') }}"></select-employee>

                            @if ($errors->has('requested_by_id'))
                                <span class="help is-danger">
                	            {{ $errors->first('requested_by_id') }}
                	        </span>
                            @endif
                        </p>
                    </div>

                    <div class="field">
                        <label for="cost_center_by_id" class="label">Cost Center <span class="has-text-danger">*</span></label>

                        <p class="control">
                            <select-department name="cost_center_by_id" url="{{ route('api.search.department') }}"></select-department>

                            @if ($errors->has('cost_center_by_id'))
                                <span class="help is-danger">
                	            {{ $errors->first('cost_center_by_id') }}
                	        </span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <hr>

            <div class="columns">
                <div class="column is-2">
                    <h1 class="title is-5">Requested for</h1>
                </div>
                <div class="column is-4">
                    <div class="field">
                        <label for="requested_for_id" class="label">Employee</label>

                        <p class="control">
                            <select-employee name="requested_for_id" url="{{ route('api.search.employee') }}"></select-employee>

                            @if ($errors->has('requested_for_id'))
                                <span class="help is-danger">
                	            {{ $errors->first('requested_for_id') }}
                	        </span>
                            @endif
                        </p>
                    </div>

                    <div class="field">
                        <label for="cost_center_for_id" class="label">Cost Center</label>

                        <p class="control">
                            <select-department name="cost_center_for_id" url="{{ route('api.search.department') }}"></select-department>

                            @if ($errors->has('cost_center_for_id'))
                                <span class="help is-danger">
                	            {{ $errors->first('cost_center_for_id') }}
                	        </span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <div class="columns">
                <div class="column is-6 has-text-right">
                    <a href="{{ route('purchase-requisitions.index') }}" class="button is-text">{{ __('words.cancel') }}</a>
                    <button class="button is-primary">{{ __('words.save') }}</button>
                </div>
            </div>

        </div>
    </form>
@stop
