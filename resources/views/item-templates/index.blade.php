@extends('layouts.app', [
    'title' => 'Item Catalog',
])

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
                <a href="{{ route('item-templates.index') }}">
                    <span class="icon is-small"><i class="fa fa-barcode"></i></span>
                    <span>{{ trans('words.item-catalog') }}</span>
                </a>
            </li>
        </ul>
    </nav>
@endsection

@section('content')
    <div class="columns">

        <div class="column is-4">
            <p class="title is-6"><b>Item Catalog</b></p>

            <a href="{{ route('item-templates.all') }}">
                <div class="notification is-success">
                    <p class="subtitle is-7">
                        <b>{{ $itemTemplatesCounter }}</b>
                    </p>
                </div>
            </a>
        </div>
    </div>

    <div class="columns">
        <div class="column is-3">
            <select-item-template url="{{ route('api.search.item-templates') }}" :redirect="true"></select-item-template>
        </div>
        <div class="column is-6 is-offset-3">
            @can('create-item-templates')
                <a class="button is-primary pull-right" href="{{ route('item-templates.create') }}">
                    New Item Catalog
                </a>
            @endcan
        </div>
    </div>

@endsection
