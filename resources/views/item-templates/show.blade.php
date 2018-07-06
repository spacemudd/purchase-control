@extends('layouts.app', ['title' => $itemTemplate->code . ' - Item Catalog'])


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
                <a href="{{ route('item-templates.index') }}">
                    <span class="icon is-small"><i class="fa fa-bars"></i></span>
                    <span>Item Catalog</span>
                </a>
            </li>
            <li class="is-active">
                <a href="{{ route('item-templates.show', ['id' => $itemTemplate->id]) }}">
                    {{ $itemTemplate->code }}
                </a>
            </li>
        </ul>
    </nav>
@endsection

@section('content')

    <div class="box">
        <div class="columns">
            <div class="column is-6">
                <h1 class="title">{{ $itemTemplate->code }}</h1>
                <p class="subtitle is-6">Item Catalog Code</p>
            </div>
            <div class="column is-6 has-text-right">
                @can('update-item-templates')
                    <a href="{{ route('item-templates.edit', ['id' => $itemTemplate->id]) }}" class="button is-small is-warning">
                        <span class="icon is-small"><i class="fa fa-pencil"></i></span>
                        <span>{{ __('words.edit') }}</span>
                    </a>
                @endcan
            </div>
        </div>

        <hr>

        <div class="columns">
            <div class="column is-6">
                <table class="table is-fullwidth is-size-7">
                    <tbody>
                    <tr>
                        <td><strong>{{ __('words.code') }}</strong></td>
                        <td>{{ $itemTemplate->code }}</td>
                    </tr>
                    <tr>
                        <td><strong>Description</strong></td>
                        <td>{{ $itemTemplate->description }}</td>
                    </tr>
                    <tr>
                        <td><strong>Default Unit Price</strong></td>
                        <td>{{ $itemTemplate->unit_price }}</td>
                    </tr>
                    <tr>
                        <td><strong>Model Details</strong></td>
                        <td>{{ $itemTemplate->model_details }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="column is-6">
                <table class="table is-fullwidth is-size-7">
                    <tbody>
                    <tr>
                        <td><strong>{{ __('words.category') }}</strong></td>
                        <td>
                            @if(optional($itemTemplate->category)->parent)
                                {{ $itemTemplate->category->parent->name }} →
                            @endif
                            {{ $itemTemplate->category->name }}
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
