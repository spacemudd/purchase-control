@extends('layouts.app', ['title' => trans('words.approvers')])

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
                <a href="{{ route('approvers.index') }}">
                    <span class="icon is-small"><i class="fa fa-address-book"></i></span>
                    <span>{{ trans('words.approvers') }}</span>
                </a>
            </li>
        </ul>
    </nav>
@endsection

@section('content')

    <div class="columns">
        <div class="column has-text-right">
            <a href="{{ route('approvers.create') }}" class="button is-primary is-small">New Approver</a>
        </div>
    </div>

    <div class="columns">
        <div class="column">
            <table class="table is-fullwidth is-size-7">
            <thead>
            <tr>
            	<th>Code</th>
                <th>Name</th>
                <th>Department</th>
                <th>Financial Authority</th>
                <th></th>
            </tr>
            </thead>
            	<tbody>
                    @foreach($approvers as $approver)
                        <tr>
                            <td>{{ $approver->code }}</td>
                            <td>{{ $approver->name }}</td>
                            <td>{{ $approver->department->display_name }}</td>
                            <td>{{ $approver->financial_authority_human }}</td>
                            <td>
                                <a href="{{ route('approvers.show', ['id' => $approver->id]) }}"
                                   class="button is-text is-small">
                                    View
                                </a>
                            </td>
                        </tr>
                    @endforeach
            	</tbody>
            </table>
        </div>
    </div>

@endsection
