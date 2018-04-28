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
            <li class="is-active">
                <a href="{{ route('purchase-requisitions') }}">
                    <span class="icon is-small"><i class="fa fa-file"></i></span>
                    <span>{{ __('words.requests') }}</span>
                </a>
            </li>
        </ul>
    </nav>
@endsection

@section('content')

    {{-- Counts for the purchase-requisitions --}}
    <div class="columns">
        <div class="column is-4">
            <p class="title is-6">
                Draft Requests
            </p>

            <a href="{{ route('requests.by-status', ['status' => 'draft']) }}">
                <div class="notification is-warning">
                    <p class="subtitle is-7">
                        <b>{{ $draftCounter }}</b>
                    </p>
                </div>
            </a>
        </div>

        <div class="column is-4">
            <p class="title is-6">
                Saved Requests
            </p>

            <a href="{{ route('requests.by-status', ['status' => 'saved']) }}">
                <div class="notification is-success">
                    <p class="subtitle is-7">
                        <b>{{ $savedCounter }}</b>
                    </p>
                </div>
            </a>
        </div>


        <div class="column is-4">
            <p class="title is-6">
                Void Requests
            </p>

            <a href="{{ route('requests.by-status', ['status' => 'void']) }}">
                <div class="notification is-danger">
                    <p class="subtitle is-7">
                        <b>{{ $voidCounter }}</b>
                    </p>
                </div>
            </a>
        </div>
    </div>

    <div class="columns">
        <div class="column is-6">

        </div>
        <div class="column is-6 has-text-right">
            <a href="{{ route('requests.create') }}" class="button is-primary">New Request</a>
        </div>
    </div>

@stop
