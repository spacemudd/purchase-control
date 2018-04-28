@extends('layouts.app', ['title' => ($request->number ? $request->number : $request->id) . ' - Requests' ])

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
                <a href="{{ route('purchase-requisitions') }}">
                    <span class="icon is-small"><i class="fa fa-file-o"></i></span>
                    <span>{{ __('words.requests') }}</span>
                </a>
            </li>
            <li class="is-active">
                <a href="#">{{ $request->id }}</a>
            </li>
        </ul>
    </nav>
@endsection

@section('content')

    <request-container :request-id.number="{{ $request->id }}"></request-container>

@stop
