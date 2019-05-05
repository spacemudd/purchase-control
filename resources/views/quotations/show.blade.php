@extends('layouts.app', ['title' => $quotation->number])

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
                    <span class="icon is-small"><i class="fa fa-shopping-cart"></i></span>
                    <span>Quotations</span>
                </a>
            </li>
            <li class="is-active">
                <a href="#">
                    {{ $quotation->vendor_quotation_number }}
                </a>
            </li>
        </ul>
    </nav>
@endsection

@section('content')
<div class="columns">
    <div class="column is-8 is-offset-2">
        <div class="box">
            {{-- Actions --}}
            <div class="columns">
                <div class="column is-6">
                    <span style="margin-left:0" class="tag">{{ $quotation->status_name }}</span>
                    <p class="is-uppercase"><b>Quotation details</b></p>
                </div>
                <div class="column is-6 has-text-right">
                    @if ((int) $quotation->status != \App\Models\Quotation::SAVED)
                        <form action="{{ route('quotations.save', ['id' => $quotation->id]) }}"
                              method="post"
                              class="is-inline">
                            @csrf
                            <button class="button is-success is-small"
                                    {!! $quotation->items()->count() ? '' : 'disabled' !!}
                                    type="submit">Save</button>
                        </form>
                    @endif
                    {{--
                    TODO: Add button to show Excel export.
                    <a href="{{ route('material-requests.excel', ['id' => $mRequest->id]) }}"
                       class="button has-icon is-small">
                        <span class="icon"><i class="fa fa-file-excel-o"></i></span>
                        <span>Excel</span>
                    </a>
                    --}}
                </div>
            </div>



            <form class="form" style="margin-top:2rem">

                <div class="field">
                    <div class="field">
                        <label for="material_request_number" class="label">Material Request Number</label>
                        <div class="control">
                            <input type="text" class="input" value="{{ $quotation->material_request->number }}" readonly>
                        </div>
                    </div>
                </div>

                <div class="field">
                    <div class="field">
                        <label for="vendor" class="label">Vendor</label>

                        <div class="control">
                            <input type="text" class="input" value="{{ $quotation->material_request->number }}" readonly>
                        </div>
                    </div>
                </div>

                <div class="field">
                    <div class="field">
                        <label for="cost_center_id" class="label">Quotation Number</label>

                        <div class="control">
                            <input type="text" class="input" value="{{ $quotation->vendor_quotation_number }}" readonly>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="columns">
    <div class="column is-8 is-offset-2">
        <div class="box">
            {{-- TODO: Can-edit will be disabled when there is a PO attached to it? --}}
            <quotations-items-container
                    :can-edit="{{ $quotation->saved_at ? 'false' : 'true' }}"
                    :quotation-id.number="{{ $quotation->id }}">
            </quotations-items-container>
        </div>
    </div>
</div>
@endsection
