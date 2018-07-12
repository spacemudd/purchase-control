@extends('layouts.app')

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
                <a href="{{ route('purchase-orders.show', ['id' => $purchaseOrder->id]) }}">
                    @if($purchaseOrder->number)
                        {{ $purchaseOrder->number }}
                    @else
                        {{ $purchaseOrder->id }}
                    @endif
                </a>
            </li>
            <li class="is-active">
                <a href="#">New Sub PO</a>
            </li>
        </ul>
    </nav>
@endsection

@section('content')
    <div class="columns">
        <div class="column is-8">
            <form action="{{ route('purchase-orders.sub.store', ['id' => $purchaseOrder->id]) }}" method="post">
                {{ csrf_field() }}

                {{-- Currency --}}
                <div class="columns">
                    <div class="column is-4">
                        <p class="title is-5">Purchase Currency</p>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label for="currency" class="label">Currency</label>

                            <div class="control">
                                <div class="select is-fullwidth">
                                    <select name="currency{{ $errors->has('currency') ? ' is-danger' : '' }}">
                                        <option value=""></option>
                                        @foreach($currencies as $currencyCode => $description)
                                            <option value="{{ $currencyCode }}"{{ $currencyCode === 'SAR' ? ' selected' : '' }}>{{ $currencyCode }} - {{ $description }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                @if ($errors->has('currency'))
                                    <span class="help is-danger">
								{{ $errors->first('currency') }}
							</span>
                                @endif
                            </div>
                        </div>
                        <hr>
                    </div>
                </div>

                {{-- Requested for --}}
                <div class="columns">
                    <div class="column is-4"><p class="title is-5">Request Information</p></div>
                    <div class="column is-6">
                        <div class="field">
                            <label for="supplier_id" class="label">Employee (Requested by)</label>

                            <div class="control">
                                <select-employee name="requested_by_employee_id"
                                                 url="{{ route('api.search.employee') }}">
                                </select-employee>
                            </div>
                        </div>
                        <div class="field">
                            <label for="supplier_id" class="label">Employee (Requested for)</label>

                            <div class="control">
                                <select-employee name="requested_for_employee_id"
                                                 url="{{ route('api.search.employee') }}"
                                                 tabindex="1"
                                >
                                </select-employee>
                            </div>
                        </div>
                        <div class="field">
                            <label for="supplier_id" class="label">Charged Cost Center</label>

                            <div class="control">
                                <select-department name="cost_center_id"
                                                   url="{{ route('api.search.department') }}">
                                </select-department>
                                <p class="help">Search by department code or name</p>
                            </div>
                        </div>
                        <hr>
                    </div>
                </div>

                {{-- Suppliers --}}
                <div class="columns">
                    <div class="column is-4">
                        <p class="title is-5">Supplier</p>
                    </div>
                    <div class="column is-6">
                        <div class="field">
                            <label for="supplier_id" class="label">Supplier</label>

                            <div class="control">
                                <select-vendors name="vendor_id"
                                                url="{{ route('api.search.vendor') }}">
                                </select-vendors>

                                <span class="help">Search by code or name</span>
                                @if ($errors->has('supplier_id'))
                                    <span class="help is-danger">
								{{ $errors->first('supplier_id') }}
							</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- -- Form done. --}}
                <div class="column is-7 has-text-right">
                    <a class="button is-text" href="{{ route('purchase-orders.show', ['id' => $purchaseOrder->id]) }}">Cancel</a>
                    <button type="submit" class="button is-primary">Save</button>
                </div>
            </form>
        </div>
        <div class="column">
            <div class="notification">
                <p class="help">Main PO</p>
                <p><strong>{{ $purchaseOrder->number }}</strong></p>
                <p class="help">Main Supplier</p>
                <p><strong>{{ $purchaseOrder->vendor->name }}</strong></p>
            </div>
        </div>
    </div>
@stop
