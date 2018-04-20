@extends('layouts.app', ['title' => $employee->code . ' - ' . $employee->name . ' - ' . trans('words.employee')])

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
                <a href="{{ route('employees.index') }}">
                    <span class="icon is-small"><i class="fa fa-user"></i></span>
                    <span>{{ trans('words.employees') }}</span>
                </a>
            </li>
            <li class="is-active">
                <a href="{{ route('employees.show', ['id' => $employee->id]) }}">
                    {{ $employee->code }}
                </a>
            </li>
        </ul>
    </nav>
@endsection

@section('content')

    <employee-show :employee-id="{{ $employee->id }}"></employee-show>

@endsection
