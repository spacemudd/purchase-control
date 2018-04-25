@extends('layouts.app', ['title' => $department->code . ' - ' . trans('words.departments')])

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
                <a href="{{ route('departments.index') }}">
                    <span class="icon is-small"><i class="fa fa-building"></i></span>
                    <span>{{ trans('words.departments') }}</span>
                </a>
            </li>
            <li class="is-active">
                <a href="#">{{ $department->code }}</a>
            </li>
        </ul>
    </nav>
@endsection

@section('content')

    <department-show :department-id="{{ $department->id }}"
                     :can-create.number="{{ Auth::user()->can('update-departments') ? '1' : '0' }}"
    ></department-show>

@endsection
