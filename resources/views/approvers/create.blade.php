@extends('layouts.app', ['title' => __('words.new-approver')])

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
                <a href="{{ route('approvers.index') }}">
                    <span class="icon is-small"><i class="fa fa-address-book"></i></span>
                    <span>{{ trans('words.approvers') }}</span>
                </a>
            </li>
            <li class="is-active">
                <a href="#">{{ trans('words.new-approver') }}</a>
            </li>
        </ul>
    </nav>
@endsection

@section('content')

    <create-approver-page>
    </create-approver-page>

@endsection
