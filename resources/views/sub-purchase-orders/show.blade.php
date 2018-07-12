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
        <div class="column is-9">
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

                        @can('delete-purchase-orders')
                            @if($subPurchaseOrder->is_draft)
                                <form class="button is-danger is-small" action="{{ route('purchase-orders.destroy', ['id' => $subPurchaseOrder->id]) }}" method="post">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="_method" value="delete">
                                    <button type="submit" class="button is-danger is-small">Delete</button>
                                </form>
                            @endif
                        @endcan

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
                            <tr>
                                <td><strong>Billing Address</strong></td>
                                <td>
                                    <address-field-token :id.number="{{ $subPurchaseOrder->id }}"
                                                         name="billing_address_id"
                                                         @if($subPurchaseOrder->billing_address_json)
                                                         :value="{{ $subPurchaseOrder->billing_address_json ? collect($subPurchaseOrder->billing_address_json)->toJson() : null }}"
                                                         @endif
                                                         placeholder="BILLING ADDRESS"
                                                         url="{{ route('purchase-orders.tokens', ['id' => $subPurchaseOrder->id]) }}"
                                                         search-url="{{ route('api.search.billing-addresses') }}"
                                                         :is-billing="true"
                                                         :can-edit="{{ $subPurchaseOrder->is_draft ? 'true' : 'false' }}"
                                    ></address-field-token>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Shipping Address</strong></td>
                                <td>
                                    <address-field-token :id.number="{{ $subPurchaseOrder->id }}"
                                                         name="shipping_address_id"
                                                         @if($subPurchaseOrder->shipping_address_json)
                                                         :value="{{ $subPurchaseOrder->shipping_address_json ? collect($subPurchaseOrder->shipping_address_json)->toJson() : null }}"
                                                         @endif
                                                         placeholder="SHIPPING ADDRESS"
                                                         url="{{ route('purchase-orders.tokens', ['id' => $subPurchaseOrder->id]) }}"
                                                         search-url="{{ route('api.search.shipping-addresses') }}"
                                                         :can-edit="{{ $subPurchaseOrder->is_draft ? 'true' : 'false' }}"
                                    ></address-field-token>
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
                            <tr>
                                <td><strong>Cost Center</strong></td>
                                <td class="is-capitalized">
                                    <edit-department-token  :id.number="{{ $subPurchaseOrder->id }}"
                                                            prop-department-id="{{ $subPurchaseOrder->cost_center_id }}"
                                                            value="{{ optional($subPurchaseOrder->cost_center)->display_name }}"
                                                            name="cost_center_id"
                                                            url="{{ route('purchase-orders.tokens', ['id' => $subPurchaseOrder->id]) }}"
                                                            :can-edit="{{ $subPurchaseOrder->is_draft ? 'true' : 'false' }}"
                                    >
                                    </edit-department-token>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Requested for</strong></td>
                                <td class="is-capitalized">
                                    <edit-employee-token  :id.number="{{ $subPurchaseOrder->id }}"
                                                          prop-employee-id="{{ $subPurchaseOrder->requested_for_employee_id }}"
                                                          value="{{ optional($subPurchaseOrder->requested_for_employee)->display_name }}"
                                                          name="requested_for_employee_id"
                                                          url="{{ route('purchase-orders.tokens', ['id' => $subPurchaseOrder->id]) }}"
                                                          :can-edit="{{ $subPurchaseOrder->is_draft ? 'true' : 'false' }}"
                                    >
                                    </edit-employee-token>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Requested by</strong></td>
                                <td class="is-capitalized">
                                    <edit-employee-token  :id.number="{{ $subPurchaseOrder->id }}"
                                                          prop-employee-id="{{ $subPurchaseOrder->requested_by_employee_id }}"
                                                          value="{{ optional($subPurchaseOrder->requested_by_employee)->display_name }}"
                                                          name="requested_by_employee_id"
                                                          url="{{ route('purchase-orders.tokens', ['id' => $subPurchaseOrder->id]) }}"
                                                          :can-edit="{{ $subPurchaseOrder->is_draft ? 'true' : 'false' }}"
                                    >
                                    </edit-employee-token>
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

    {{-- Items --}}
    <div class="columns">
        <div class="column is-9">
            <div class="columns">
                <div class="column">
                    <h2 class="title is-5 has-text-weight-light">Items</h2>
                </div>
                {{--
                <div class="column has-text-right">
                    @if($subPurchaseOrder->is_draft)
                        <new-po-item-from-pr-button :po-id.number="{{ $subPurchaseOrder->id }}"></new-po-item-from-pr-button>
                    @endif
                </div>
                --}}
            </div>

            {{-- Items --}}
            <div class="box">
                @if($subPurchaseOrder->is_draft)
                    <purchase-order-items :po-id.number="{{ $subPurchaseOrder->id }}"></purchase-order-items>
                @else
                    <table class="table is-fullwidth">
                        <thead>
                        <tr>
                            <th>Item</th>
                            <th class="has-text-right">Quantity</th>
                            <th class="has-text-right">Unit Price</th>
                            <th>Tax</th>
                            <th class="has-text-right">Subtotal</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($subPurchaseOrder->items as $item)
                            <tr>
                                <td>{{ $item->item_catalog->display_name }}</td>
                                <td class="has-text-right">{{ $item->qty }}</td>
                                <td class="has-text-right">{{ $item->unit_price }}</td>
                                <td>
                                    @if($item->taxes)
                                        <div class="tags">
                                            @foreach($item->taxes as $tax)
                                                <span class="tag">{{ $tax->display_name }}</span>
                                            @endforeach
                                        </div>
                                    @endif
                                </td>
                                <td class="has-text-right">{{ $item->subtotal }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="columns">
                        <div class="column">
                            <table class="table invoice-bill-totals pull-right"><thead>
                                <tr>
                                    <td class="has-text-right">Subtotal:</td>
                                    <td class="has-text-right"><span class="js-subtotal">{{ $subPurchaseOrder->subtotal }}</span></td>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach($subPurchaseOrder->taxes_totals as $taxName => $minorAmount)
                                    <tr>
                                        <td class="has-text-right">{{ $taxName }}:</td>
                                        <td class="has-text-right">{{ \Brick\Money\Money::ofMinor($minorAmount, $subPurchaseOrder->currency) }}</td>
                                    </tr>
                                </tbody>
                                @endforeach

                                <tfoot>
                                <tr>
                                    <td class="has-text-right">Total (<span class="js-currency">{{ $subPurchaseOrder->currency }}</span>):</td>
                                    <td class="has-text-right"><span class="js-total">{{ $subPurchaseOrder->total }}</span></td>
                                </tr>
                                {{--
                                <tr class="js-native-currency-total hide">
                                    <td>
                                        <small>Total (<span class="js-business-currency">SAR</span>
                                            at <span class="js-exchange-rate">1</span>):</small>
                                    </td>
                                    <td class="align-right">
                                        <small class="js-business-total">﷼10,227.84</small>
                                    </td>
                                </tr>
                                --}}
                                </tfoot>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>


    {{-- Delivery & Terms --}}
    <div class="columns">
        <div class="column is-4">
            <h2 class="title is-5 has-text-weight-light">Purchase Terms</h2>
        </div>
    </div>
    <div class="columns">
        <div class="column is-9">
            <div class="box purchase-terms">
                @if($subPurchaseOrder->is_draft || $subPurchaseOrder->other_terms)
                    <div class="columns">
                        <div class="column is-4">
                            <p class="title is-7">Other Terms</p>
                        </div>
                        <div class="column">
                            <other-terms-token :id.number="{{ $subPurchaseOrder->id }}"
                                               name="other_terms"
                                               value="{{ $subPurchaseOrder->other_terms  }}"
                                               placeholder="OTHER TERMS"
                                               url="{{ route('purchase-orders.tokens', ['id' => $subPurchaseOrder->id]) }}"
                                               :can-edit="{{ $subPurchaseOrder->is_draft ? 'true' : 'false' }}"
                            ></other-terms-token>
                        </div>
                    </div>
                @endif
                @foreach($subPurchaseOrder->terms_json as $key => $terms)
                    @if($key === 'Others')
                        @break
                    @endif
                    <div class="columns">
                        <div class="column is-4">
                            <p class="title is-7">{{ $key }}</p>
                        </div>
                        <div class="column">
                            @foreach($terms as $term)
                                <ul>
                                    <toggle-purchase-term :term-id.number="{{ $term->value->id }}"
                                                          :po-id.number="{{ $subPurchaseOrder->id }}"
                                                          :enabled-prop.number="{{ $term->enabled ? 'true' : 'false' }}"
                                                          :can-toggle="{{ $subPurchaseOrder->is_draft ? 'true' : 'false' }}"
                                    >
                                        {{ $term->value->value }}
                                    </toggle-purchase-term>
                                </ul>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

@stop
