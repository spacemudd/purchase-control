@extends('layouts.app', ['title' => $vendor->id . ' - ' . $vendor->name . ' - ' . trans('words.vendor')])


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
                <a href="{{ route('vendors.index') }}">
                    <span class="icon is-small"><i class="fa fa-truck"></i></span>
                    <span>{{ trans('words.vendors') }}</span>
                </a>
            </li>
            <li class="is-active">
                <a href="{{ route('vendors.show', ['id' => $vendor->id]) }}">
                    {{ $vendor->id }} - {{ $vendor->name }}
                </a>
            </li>
        </ul>
    </nav>
@endsection

@section('content')

    <div class="box">
        <div class="columns">
            <div class="column is-6">
                <h1 class="title">{{ $vendor->id }}</h1>
                <p class="subtitle is-6">{{ __('words.vendor-code') }}</p>
            </div>
            <div class="column is-6 has-text-right">
                <a href="{{ route('vendors.edit', ['id' => $vendor->id]) }}" class="button is-small is-warning">
                    <span class="icon is-small"><i class="fa fa-pencil"></i></span>
                    <span>{{ __('words.edit') }}</span>
                </a>
            </div>
        </div>

        <hr>

        <div class="columns">
            <div class="column is-6">
                <table class="table is-fullwidth is-size-7">
                    <tbody>
                        <tr>
                            <td><strong>{{ __('words.code') }}</strong></td>
                            <td>{{ $vendor->id }}</td>
                        </tr>
                        <tr>
                            <td><strong>{{ __('words.name') }}</strong></td>
                            <td>{{ $vendor->name }}</td>
                        </tr>

                        <tr>
                            <td><strong>{{ __('words.website') }}</strong></td>
                            <td>{{ $vendor->website }}</td>
                        </tr>
                        <tr>
                            <td><strong>{{ __('auth.email') }}</strong></td>
                            <td>{{ $vendor->email }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="column is-6">
                <table class="table is-fullwidth is-size-7">
                    <tbody>
                        <tr>
                            <td><strong>{{ __('words.established-at') }}</strong></td>
                            <td>{{ $vendor->established_at }}</td>
                        </tr>
                        <tr>
                            <td><strong>{{ __('words.telephone-number') }}</strong></td>
                            <td>{{ $vendor->telephone_number }}</td>
                        </tr>
                        <tr>
                            <td><strong>{{ __('words.fax-number') }}</strong></td>
                            <td>{{ $vendor->fax_number }}</td>
                        </tr>
                        <tr>
                            <td><strong>{{ __('words.address') }}</strong></td>
                            <td>{{ $vendor->address }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="columns">
        <div class="column is-4">
            <div class="panel">
                <p class="panel-heading">
                    {{ __('words.representatives') }}
                    <a href="{{ route('vendor-representatives.create', ['vendor_id' => $vendor->id]) }}" class="button is-primary is-small is-pulled-right">
                        Add
                    </a>
                </p>
                @if($vendor->reps->count())

                @else
                    <p class="panel-block">
                        <span class="has-text-centered fullwidth"><i>No representatives</i></span>
                    </p>
                @endif
            </div>
        </div>
    </div>
@endsection
