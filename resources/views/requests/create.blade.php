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
                <a href="{{ route('requests.index') }}">
                    <span class="icon is-small"><i class="fa fa-file"></i></span>
                    <span>{{ __('words.requests') }}</span>
                </a>
            </li>
            <li class="is-active">
                <a href="">New</a>
            </li>
        </ul>
    </nav>
@endsection

@section('content')
    <div class="columns">
        <div class="column is-8 is-offset-2">
            <create-request-form endpoint="{{ route('api.requests.store') }}">
                <a slot="cancelButton"
                   class="button is-text"
                   href="{{ route('requests.index') }}">
                    Cancel
                </a>
            </create-request-form>
        </div>
    </div>
@stop
