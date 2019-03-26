@extends('layouts.app', ['title' => 'Create Quotation'])

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
                <a href="{{ route('quotations.index') }}">
                    <span class="icon is-small"><i class="fa fa-quote-right"></i></span>
                    <span>Quotation</span>
                </a>
            </li>
            <li class="is-active">
                <a href="#">
                    Create
                </a>
            </li>
        </ul>
    </nav>
@endsection

@section('content')
<div class="columns">
    <div class="column is-6">
        <div class="box">
            <p class="is-uppercase"><b>Quotation details</b></p>
            <form class="form" action="{{ route('quotations.store') }}" method="post" style="margin-top:2rem">
                @csrf
                <div class="field">
                    <label for="date" class="label">Material request number <span class="has-text-danger">*</span></label>

                    <div class="control">
                        <div class="select is-fullwidth">
                            <select class="select{{ $errors->has('material_request_id') ? ' is-danger' : '' }}"
                                    name="material_request_id"
                                    required>
                                @foreach ($mRequests as $request)
                                    <option value="{{ $request->id }}">{{ $request->number }}</option>
                                @endforeach
                            </select>
                        </div>
                        @if ($errors->has('material_request_id'))
                            <span class="help is-danger">
                                {{ $errors->first('material_request_id') }}
                            </span>
                        @endif
                    </div>
                </div>

                <div class="field">
                    <label for="vendor_id" class="label">Vendor</label>

                    <div class="control">
                        <select-vendors name="vendor_id"
                                        url="{{ route('api.search.vendor') }}">
                        </select-vendors>

                        <p class="help">Click here to <a href="#" @click="$store.commit('Vendor/setNewModal', true)">add new vendor</a>.</p>

                        @if ($errors->has('vendor_id'))
                            <span class="help is-danger">
								{{ $errors->first('vendor_id') }}
							</span>
                        @endif
                    </div>
                </div>


                <div class="field">
                    <label for="vendor_quotation_number" class="label">Quotation Number</label>

                    <p class="control">
                        <input id="vendor_quotation_number" type="text"
                               class="input {{ $errors->has('vendor_quotation_number') ? ' is-danger' : '' }}"
                               name="vendor_quotation_number"
                               value="{{ old('vendor_quotation_number') }}"
                               required>

                        @if ($errors->has('vendor_quotation_number'))
                            <span class="help is-danger">
                	            {{ $errors->first('vendor_quotation_number') }}
                	        </span>
                        @endif
                    </p>
                </div>

                <div class="field">
                    <button class="button is-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
