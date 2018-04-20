@extends('layouts.app', ['title' => $asset->asset_template->code . ' (' . $asset->manufacturer_serial_number .') - ' . trans('words.assets')])

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
                <a href="{{ route('assets.index') }}">
                    <span class="icon is-small"><i class="fa fa-barcode"></i></span>
                    <span>{{ trans('words.assets') }}</span>
                </a>
            </li>
            <li class="is-active">
                <a href="#">{{ $asset->asset_template->code }} ({{  $asset->manufacturer_serial_number }})</a>
            </li>
        </ul>
    </nav>
@endsection

@section('content')

    <asset-show :asset-id.number="{{ $asset->id }}"></asset-show>

@endsection
