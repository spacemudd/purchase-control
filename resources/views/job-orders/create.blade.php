@extends('layouts.app', ['title' => 'Create Job Order'])

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
                    Create
                </a>
            </li>
        </ul>
    </nav>
@endsection

@section('content')
<div class="columns is-centered">
    <div class="column is-12">
        <div class="box">
            <p class="is-uppercase"><b>Job Order details</b></p>
            <job-order-form></job-order-form>
        </div>
    </div>
</div>
@endsection
