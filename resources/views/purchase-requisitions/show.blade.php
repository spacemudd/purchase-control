@extends('layouts.app', ['title' => ($request->number ? $request->number : $request->id) . ' - Purchase Requisitions' ])

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
                            <br/>
                            <span class="is-capitalized">
                                {{ $request->status_name }}
                                @if($request->canAddItems)
                                    <span class="circle is-warning"></span>
                                @endif
                                @if($request->isSaved)
                                    <span class="circle is-success"></span>
                                @endif
                                @if($request->isApproved)
                                    <span class="circle is-success"></span>
                                @endif
                            </span>
                        </p>
                    </div>

                    <div class="column has-text-right">

                        @if(!$request->canAddItems)
                            <toggle-preview-requisition></toggle-preview-requisition>
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

                        @can('approve-purchase-requisitions')
                            {{--
                            @if($request->is_saved)
                                <approve-requisition url="{{ route('api.purchase-requisitions.approve', ['id' => $request->id]) }}"
                                                     search-approvers-url="{{ route('api.search.approvers') }}"
                                >
                                </approve-requisition>
                            @endif
                            --}}
                            {{--
                            @if($request->is_saved)
                                <form class="button is-warning is-small" action="{{ route('purchase-requisitions.approve', ['id' => $request->id]) }}" method="post">
                                    {{ csrf_field() }}
                                    <button type="submit" class="button is-warning is-small">Approve</button>
                                </form>
                            @endif
                            --}}
                        @endif
                    </div>
                </div>

                <preview-pdf url="{{ route('purchase-requisitions.pdf', ['id' => $request->id]) }}"></preview-pdf>

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
                                    <td>{{ $request->created_at->format('Y-m-d') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Requested by</strong><br/>Employee</td>
                                    <td>{{ $request->requested_by->code }} - {{ $request->requested_by->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Requested by</strong><br/>Cost Center</td>
                                    <td>{{ $request->cost_center_by->code }} - {{ $request->cost_center_by->description }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="column is-6">
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

                {{-- Signatures block --}}
                <div class="columns">
                    <div class="column is-6">
                        <div class="box">
                            <p class="title is-7">Recommended by<br/><br/></p>
                            <edit-pr-recommended-by-token employee-name="{{ optional($request->recommended_by)->display_name }}"
                                                          url="{{ route('api.purchase-requisitions.update', ['id' => $request->id]) }}"
                                                          :can-edit="{{ $request->has_been_saved ? 'false' : 'true' }}"
                                                          recommended-by-id="{{ $request->recommended_by_id }}"
                            >
                            </edit-pr-recommended-by-token>
                        </div>
                    </div>

                    <div class="column is-6">
                        <div class="box">
                            <p class="title is-7 is-spaced">
                                <strong>Approved by</strong>
                                <br/>
                                <span class="has-text-weight-light has-text-grey">Having financial authority</span>
                            </p>
                            <edit-pr-financial-approved-by-token employee-name="{{ optional($request->f_auth_by)->display_name }}"
                                                                 url="{{ route('api.purchase-requisitions.update', ['id' => $request->id]) }}"
                                                                 :can-edit="{{ $request->has_been_saved ? 'false' : 'true' }}"
                                                                 f-approved-by-id="{{ $request->f_auth_by_id }}"
                            >
                            </edit-pr-financial-approved-by-token>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="columns">
                    <div class="column is-6">
                        <div class="box">
                            <p class="title is-7">Checked and registered by<br/><br/></p>
                            {{--<p class="subtitle is-7"></p>--}}
                            <edit-pr-checked-by-token employee-name="{{ optional($request->checked_by)->display_name }}"
                                                          url="{{ route('api.purchase-requisitions.update', ['id' => $request->id]) }}"
                                                          :can-edit="{{ $request->has_been_saved ? 'false' : 'true' }}"
                                                          checked-by-id="{{ $request->checked_by_id }}"
                            >
                            </edit-pr-checked-by-token>
                        </div>
                    </div>

                    <div class="column is-6">
                        <div class="box">
                            <p class="title is-7">
                                <strong>Approved by</strong>
                                <br/>
                                <span class="has-text-weight-light has-text-grey">Head of IT Assets Management</span>
                            </p>
                            <edit-pr-itam-approved-by-token employee-name="{{ optional($request->head_of_itam)->display_name }}"
                                                       url="{{ route('api.purchase-requisitions.update', ['id' => $request->id]) }}"
                                                       :can-edit="{{ $request->has_been_saved ? 'false' : 'true' }}"
                                                       head-of-itam-id="{{ $request->head_of_itam_id }}"
                            >
                            </edit-pr-itam-approved-by-token>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="columns">
                    <div class="column">
                        <edit-requisition-remarks :id.number="{{ $request->id }}"
                                                  :can-edit.number="{{ $request->has_been_saved ? 0 : 1 }}"
                                                  remarks-text="{{ $request->itam_remarks }}"
                                                  url="{{ route('api.purchase-requisitions.remarks', ['id' => $request->id]) }}"
                                                  style="margin-left: 10px;"
                        >
                        </edit-requisition-remarks>
                    </div>
                </div>
            </div>

            {{-- Simple Items --}}
            {{-- Removed because it is now automated, or so the ITAM manager have said.
            <purchase-requisition-simple-items :requisition-id="{{ $request->id }}"
                                                :is-approved.number="{{ $request->has_been_saved ? '1' : 0 }}"
                                                :in-draft.number="{{ $request->canAddItems ? '1' : '0' }}">
            </purchase-requisition-simple-items>
            --}}

            {{-- ITAM Items --}}
            <purchase-requisition-items :requisition-id="{{ $request->id }}"
                                        :is-approved.number="{{ $request->has_been_saved ? '1' : 0 }}"
                                        :in-draft.number="{{ $request->canAddItems ? '1' : '0' }}">
            </purchase-requisition-items>
        </div>

        {{-- Resource side info --}}
        <div class="column">
            {{-- Notes section --}}
            <notes-container url="{{ route('api.purchase-requisition.notes', ['id' => $request->id]) }}"
                             resource-id.number="{{ $request->id }}"
            >
            </notes-container>

            {{-- Uploads section --}}
            <uploads-container url="{{ route('api.purchase-requisition.uploads', ['id' => $request->id]) }}"
                               resource-id.number="{{ $request->id }}"
            >
            </uploads-container>
        </div>
    </div>
@stop
