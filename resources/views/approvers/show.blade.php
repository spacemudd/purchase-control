@extends('layouts.app', ['title' => $approver->display_name . ' - ' . 'Approvers'])

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
                <a href="#">{{ $approver->display_name }}</a>
            </li>
        </ul>
    </nav>
@endsection

@section('content')

    <div class="box">
        <div class="columns">
            <div class="column is-6">
                <h1 class="title">{{ $approver->id }}</h1>
                <p class="subtitle is-6">Employee Code</p>
            </div>
            <div class="column is-6 has-text-right">
                <delete-dialog url="{{ route('api.approvers.destroy', ['id' => $approver->id]) }}"
                               button-text="Remove Financial Authority Information"
                ></delete-dialog>
                <a href="{{ route('approvers.edit', ['id' => $approver->id]) }}" class="button is-small" style="margin-left: 50px;">
                    <span class="icon is-small"><i class="fa fa-pencil"></i></span>
                    <span>Edit</span>
                </a>
                <a href="{{ route('employees.show', ['id' => $approver->id]) }}" class="button is-small">
                    <span class="icon is-small"><i class="fa fa-pencil"></i></span>
                    <span>Employee Page</span>
                </a>
            </div>
        </div>

        <hr>

        <div class="columns">
            <div class="column is-6">
                <table class="table is-fullwidth is-size-7">
                    <tbody>
                    <tr>
                        <td><strong>{{ __('words.code') }}</strong></td>
                        <td>{{ $approver->id }}</td>
                    </tr>
                    <tr>
                        <td><strong>{{ __('words.name') }}</strong></td>
                        <td>{{ $approver->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Financial Authority</strong></td>
                        <td>{{ $approver->financial_authority_human }}</td>
                    </tr>
                    <tr>
                        <td><strong>Designation</strong></td>
                        <td>{{ $approver->designation }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
