@extends('layouts.app', ['title' => $subPurchaseOrder->display_name . '- Sub Purchase Order'])

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
                <a href="{{ route('purchase-orders.index') }}">
                    <span class="icon is-small"><i class="fa fa-shopping-cart"></i></span>
                    <span>{{ trans('words.purchase-orders') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('purchase-orders.show', ['id' => $subPurchaseOrder->purchase_order_main_id]) }}">
                    {{ $subPurchaseOrder->main_purchase_order->number }}
                </a>
            </li>
            <li>
                <a href="{{ route('purchase-orders.sub.index', ['purchase_order_id' => $subPurchaseOrder->purchase_order_main_id]) }}">
                    Sub Purchase Orders
                </a>
            </li>
            <li>
                <a href="{{ route('purchase-orders.sub.show', ['purchase_order_id' => $subPurchaseOrder->purchase_order_main_id, 'id' => $subPurchaseOrder->id]) }}">
                    {{ $subPurchaseOrder->display_name }}
                </a>
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
                        {{-- todo: notification/subscription bell --}}
                        <h1 class="title">
                            @if($subPurchaseOrder->number)
                                {{ $subPurchaseOrder->number }}
                            @else
                                <span class="has-text-grey-lighter">Draft</span>
                            @endif
                        </h1>
                        <p class="subtitle is-6">
                            <span class="help">Sub</span> Purchase Order Number
                            @if(!$subPurchaseOrder->number)
                                <b-tooltip label="Generated when saved"><span class="icon is-small"><i class="fa fa-question-circle"></i></span></b-tooltip>
                            @endif
                        </p>
                    </div>

                    {{-- Options --}}
                    <div class="column has-text-right">
                        @if(!$subPurchaseOrder->is_draft)
                            <toggle-preview-purchase-order></toggle-preview-purchase-order>
                        @endif

                        @can('create-purchase-orders')
                            @if($subPurchaseOrder->is_draft)
                                <form class="button is-warning is-small" action="{{ route('purchase-orders.sub.save', [
                                'purchase_order_id' => $subPurchaseOrder->purchase_order_main_id,
                                'id' => $subPurchaseOrder->id
                                ]) }}" method="post">
                                    {{ csrf_field() }}
                                    <button type="submit" class="button is-warning is-small">Save</button>
                                </form>
                            @endif
                        @endif
                    </div>
                </div>

                <preview-pdf-container url="{{ route('purchase-orders.pdf', ['id' => $subPurchaseOrder->id]) }}"
                                       show-type="PurchaseOrder/previewPdf"
                ></preview-pdf-container>

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
                                <td>
                                    <datetime-token :id.number="{{ $subPurchaseOrder->id }}"
                                                    name="date"
                                                    value="{{ $subPurchaseOrder->date_string }}"
                                                    :highlighted="{{ $subPurchaseOrder->is_draft ? 'true' : 'false' }}"
                                                    placeholder="PURCHASE ORDER DATE"
                                                    url="{{ route('purchase-orders.tokens', ['id' => $subPurchaseOrder->id]) }}"
                                    ></datetime-token>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Supplier</strong></td>
                                <td>
                                    <edit-supplier-token name="vendor_id"
                                                         value="{{  $subPurchaseOrder->vendor_json_display_name }}"
                                                         :highlighted="{{ $subPurchaseOrder->is_draft ? 'true' : 'false' }}"
                                                         placeholder="SUPPLIER ID"
                                                         url="{{ route('purchase-orders.tokens', ['id' => $subPurchaseOrder->id]) }}"
                                    ></edit-supplier-token>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Delivery Date</strong> <b-tooltip label="To supply the items not later than..."><span class="icon is-small"><i class="fa fa-question-circle"></i></span></b-tooltip></td>
                                <td>
                                    <delivery-date-token :id.number="{{ $subPurchaseOrder->id }}"
                                                         name="delivery_date"
                                                         value="{{ $subPurchaseOrder->delivery_date_string }}"
                                                         :highlighted="{{ $subPurchaseOrder->is_draft ? 'true' : 'false' }}"
                                                         placeholder="DELIVERY DATE DATE"
                                                         url="{{ route('purchase-orders.tokens', ['id' => $subPurchaseOrder->id]) }}"
                                    ></delivery-date-token>
                                </td>
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
                                <td>
                                    @if($subPurchaseOrder->created_by)
                                        {{ optional($subPurchaseOrder->created_by)->username }} - {{ optional($subPurchaseOrder->created_by)->name }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>Status</strong>
                                </td>
                                <td class="is-capitalized">
                                    {{ $subPurchaseOrder->status_name }}
                                    @if($subPurchaseOrder->isDraft)
                                        <span class="circle is-warning"></span>
                                    @endif
                                    @if($subPurchaseOrder->isSaved)
                                        <span class="circle is-warning"></span>
                                    @endif
                                    @if($subPurchaseOrder->isApproved)
                                        <span class="circle is-success"></span>
                                    @endif
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Resource side info --}}
        <div class="column">
            {{-- Notes section --}}
            <notes-container url="{{ route('api.purchase-orders.notes', ['id' => $subPurchaseOrder->id]) }}"
                             resource-id.number="{{ $subPurchaseOrder->id }}"
            >
            </notes-container>

            {{-- Uploads section --}}
            <uploads-container url="{{ route('api.purchase-orders.uploads', ['id' => $subPurchaseOrder->id]) }}"
                               resource-id.number="{{ $subPurchaseOrder->id }}"
            >
            </uploads-container>
        </div>

    </div>

    {{-- Delivery & Terms --}}
    <div class="columns">
        <div class="column is-4">
            <h2 class="title is-5 has-text-weight-light">Delivery &amp; Terms</h2>
        </div>
    </div>
    <div class="columns">
        <div class="column is-8">
            <div class="box">
                @foreach($purchaseTermsTypes as $key => $type)
                    <div class="columns">
                        <div class="column is-4">
                            <p class="title is-7">{{ $type->name }}</p>
                        </div>
                        <div class="column">
                            @foreach($type->terms()->get() as $term)
                                <ul>
                                    <toggle-purchase-term :term-id.number="{{ $term->id }}"
                                                          :po-id.number="{{ $subPurchaseOrder->id }}"
                                                          :enabled-prop.number="{{ $subPurchaseOrder->terms->contains($term->id) ? 'true' : 'false' }}"
                                                          :can-toggle="{{ $subPurchaseOrder->is_draft ? 'true' : 'false' }}"
                                    >
                                        {{ $term->value }}
                                    </toggle-purchase-term>
                                </ul>
                            @endforeach
                        </div>
                    </div>
                    @if(!($key === count($purchaseTermsTypes) - 1))
                        <hr>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    {{-- Items --}}
    <div class="columns">
        <div class="column is-8">
            <div class="columns">
                <div class="column">
                    <h2 class="title is-5 has-text-weight-light">Items</h2>
                </div>
                <div class="column has-text-right">
                    @if($subPurchaseOrder->is_draft)
                        <new-po-item-from-pr-button :po-id.number="{{ $subPurchaseOrder->id }}"></new-po-item-from-pr-button>
                    @endif
                </div>
            </div>

            {{-- Items --}}
            <div class="box">
                <purchase-order-items :po-id.number="{{ $subPurchaseOrder->id }}"></purchase-order-items>
            </div>
        </div>
    </div>

@stop
