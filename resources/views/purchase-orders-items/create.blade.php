@extends('layouts.app', ['title' => trans('words.create-purchase-item') . ' - ' .
                                    '#' . $purchase_order->prefix . $purchase_order->id
                        ])

@section('content')


    <ol class="breadcrumb">
        <li>
            <a href="{{ route('dashboard.index') }}">
                <i class="fa fa-folder-open"></i>
                {{ trans('words.dashboard') }}
            </a>
        </li>
        <li><a href="{{ route('procedures.index') }}">{{ trans('words.procedures') }}</a></li>
        <li><a href="{{ route('purchase-orders.index') }}">{{ trans('words.purchase-orders') }}</a></li>
        <li><a href="{{ route('purchase-orders.show', ['id' => $purchase_order->id]) }}">{{ $purchase_order->order_number }}</a></li>
        <li><a href="#">{{ trans('words.items') }}</a></li>
        <li class="active">{{ trans('words.create') }}</li>
    </ol>

    <div class="row">
            <div class="col-md-12">

                <div class="section">
                    <div class="panel panel-primary">

                        <div class="panel-heading">
                            <div class="panel-title">
                                {{ trans('words.create-purchase-item') }}
                            </div>
                        </div>

                        <div class="panel-body">

                                <form class="form-horizontal form-groups-bordered" method="POST" action="{{ route('purchase-orders.items.store', ['purchase_order_id' => $purchase_order->id]) }}">
                                    {{ csrf_field() }}

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group{{ $errors->has('asset_template_id') ? ' has-error' : '' }}">
                                                <label class="col-md-2 control-label">{{ trans('words.asset') }} *</label>

                                                <div class="col-md-8">
                                                    <simple-search-return-id
                                                            search-endpoint="{{ route('api.search.asset-template') }}"
                                                            field-name="asset_template_id">
                                                    </simple-search-return-id>

                                                    @if ($errors->has('asset_template_id'))
                                                        <span class="help-block">
                                                            <strong>{{ $errors->first('asset_template_id') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>


                                    <div class="row">

                                        <div class="col-md-6">
                                            <div class="form-group{{ $errors->has('manufacturer_serial_number') ? ' has-error' : '' }}">
                                                <label class="col-md-4 control-label">{{ trans('words.manufacturer-serial-number') }} *</label>

                                                <div class="col-md-6">

                                                    <input type="text" class="form-control" name="manufacturer_serial_number" value="{{ old('manufacturer_serial_number') }}" autocomplete="off">

                                                    @if ($errors->has('vendor_id'))
                                                        <span class="help-block">
                                                            <strong>{{ $errors->first('vendor_id') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group{{ $errors->has('system_tag_number') ? ' has-error' : '' }}">
                                                <label class="col-md-3 control-label">{{ trans('words.system-tag-number') }} *</label>

                                                <div class="col-md-5">
                                                    <input type="text" class="form-control" name="system_tag_number" autocomplete="off" value="{{ old('system_tag_number') }}" required>

                                                    @if ($errors->has('system_tag_number'))
                                                        <span class="help-block">
                                                    <strong>{{ $errors->first('system_tag_number') }}</strong>
                                                </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <hr>


                                    <div class="row">

                                        <div class="col-md-6">
                                            <div class="form-group{{ $errors->has('finance_tag_number') ? ' has-error' : '' }}">
                                                <label class="col-md-4 control-label">{{ trans('words.finance-tag-number') }} *</label>

                                                <div class="col-md-6">

                                                    <input type="text" class="form-control" name="finance_tag_number" value="{{ old('finance_tag_number') }}" autocomplete="off">

                                                    @if ($errors->has('finance_tag_number'))
                                                        <span class="help-block">
                                                            <strong>{{ $errors->first('finance_tag_number') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group{{ $errors->has('unit_price') ? ' has-error' : '' }}">
                                                <label class="col-md-3 control-label">{{ trans('words.unit-price') }} *</label>

                                                <div class="col-md-5">
                                                    <input type="number" min="0" step="0.01" class="form-control" name="unit_price" autocomplete="off" value="{{ old('unit_price') }}" required>

                                                    @if ($errors->has('unit_price'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('unit_price') }}</strong>
                                                    </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <hr>


                                    <div class="row">

                                        <div class="col-md-6">
                                            <div class="form-group{{ $errors->has('warranty') ? ' has-error' : '' }}">
                                                <label class="col-md-4 control-label">{{ trans('words.warranty') }} *</label>

                                                <div class="col-md-6">

                                                    <input type="text" class="form-control" name="warranty" autocomplete="off" value="2">

                                                    @if ($errors->has('warranty_amount'))
                                                        <span class="help-block">
                                                            <strong>{{ $errors->first('warranty') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group{{ $errors->has('warranty_type') ? ' has-error' : '' }}">
                                                <label class="col-md-3 control-label">{{ trans('words.warranty-type') }} *</label>

                                                <div class="col-md-5">

                                                    <select name="warranty_type" id="" class="form-control">
                                                        <option value="year">{{ trans('words.year') }}</option>
                                                        <option value="month">{{ trans('words.month') }}</option>
                                                        <option value="week">{{ trans('words.week') }}</option>
                                                        <option value="day">{{ trans('words.day') }}</option>
                                                    </select>

                                                    @if ($errors->has('warranty_type'))
                                                        <span class="help-block">
                                                        <strong>{{ $errors->first('warranty_type') }}</strong>
                                                    </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <hr>

                                    {{-- TODO:
                                    <div class="row">

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="form-group{{ $errors->has('location_id') ? ' has-error' : '' }}">
                                                    <label for="location_id" class="col-md-4 control-label">{{ trans('words.location-code') }}</label>

                                                    <div class="col-md-6">
                                                        <input id="location_id" type="text" class="form-control"
                                                               name="location_id"
                                                               value="{{ old('location_id') }}" required>

                                                        @if($errors->has('location_id'))
                                                            <span class="help-block">
                                                				<strong>{{ $errors->first('location_id') }}</strong>
                                                			</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    --}}





                                    <div class="form-group">
                                        <div class="col-sm-offset-3 col-sm-5">
                                            <button type="submit" class="btn btn-success btn-lg">{{ trans('words.save') }}</button>
                                        </div>
                                    </div>

                                </form>

                        </div>

                    </div>
                </div>

            </div>
        </div>

@endsection
