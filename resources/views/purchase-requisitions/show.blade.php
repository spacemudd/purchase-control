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
                <a href="{{ route('purchase-requisitions.index') }}">
                    <span class="icon is-small"><i class="fa fa-file-o"></i></span>
                    <span>{{ __('words.purchase-requisitions') }}</span>
                </a>
            </li>
            <li class="is-active">
                <a href="#">{{ $request->id }}</a>
            </li>
        </ul>
    </nav>
@endsection

@section('content')

    <div class="columns">

        <div class="column is-8">
            <div class="box">
                <div class="columns">
                    <div class="column is-6">
                        {{-- todo: subscription/notif bell --}}
                        <h1 class="title">
                            @if($request->number)
                                {{ $request->number }}
                            @else
                                <span class="has-text-grey-lighter">Draft</span>
                            @endif
                        </h1>
                        <p class="subtitle is-6">
                            Purchase Requisition Number
                            @if(!$request->number)
                                <b-tooltip label="Generated when saved"><span class="icon is-small"><i class="fa fa-question-circle"></i></span></b-tooltip>
                            @endif
                        </p>
                    </div>

                    <div class="column has-text-right">

                        @if(!$request->canAddItems)
                            <a class="button is-small" href="{{ route('purchase-requisitions.pdf', ['id' => $request->id]) }}">Print</a>
                        @endif

                        @can('delete-purchase-requisitions')
                            @if($request->canAddItems)
                                <delete-prompt :id.number="{{ $request->id }}"
                                               url="{{ route('purchase-requisitions.destroy', ['id' => $request->id]) }}"
                                >
                                </delete-prompt>
                            @endif
                        @endcan

                        @can('create-purchase-requisitions')
                            @if($request->canAddItems)
                                <form class="button is-warning is-small" action="{{ route('purchase-requisitions.save', ['id' => $request->id]) }}" method="post">
                                    {{ csrf_field() }}
                                    <button type="submit" class="button is-warning is-small">Save</button>
                                </form>
                            @endif
                        @endif
                    </div>
                </div>

                <hr>

                {{-- Resource' table info --}}
                <div class="columns">
                    <div class="column is-6">
                        <table class="table is-size-7 is-fullwidth">
                            <colgroup>
                                <col width="50%">
                            </colgroup>
                            <tbody>
                                <tr>
                                    <td><strong>Date</strong></td>
                                    <td>{{ $request->date }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status</strong></td>
                                    <td class="is-capitalized">
                                        {{ $request->status_name }}
                                        @if($request->canAddItems)
                                            <span class="circle is-warning"></span>
                                        @endif
                                        @if($request->isSaved)
                                            <span class="circle is-warning"></span>
                                        @endif
                                        @if($request->isApproved)
                                            <span class="circle is-success"></span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Requested for</strong><br/>Employee</td>
                                    <td>{{ $request->requested_by->code }} - {{ $request->requested_by->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Requested for</strong><br/>Cost Center</td>
                                    <td>{{ $request->cost_center_by->code }} - {{ $request->cost_center_by->description }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="column">
                        <table class="table is-size-7 is-fullwidth">
                            <colgroup>
                                <col width="50%">
                            </colgroup>
                            <tbody>
                            <tr>
                                <td><strong>Created by</strong></td>
                                <td>{{ $request->created_by->username }} - {{ $request->created_by->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>In progress by</strong></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td><strong>Requested for</strong><br/>Employee</td>
                                <td>{{ optional($request->requested_for)->display_name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Requested for</strong><br/>Cost Center</td>
                                <td>{{ optional($request->cost_center_for)->display_name }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            {{-- Items --}}
            <purchase-requisition-items :requisition-id="{{ $request->id }}" :in-draft.number="{{ $request->canAddItems ? '1' : '0' }}"></purchase-requisition-items>
        </div>

        {{-- Resource side info --}}
        <div class="column">

        </div>

    </div>

@stop
