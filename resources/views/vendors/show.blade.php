@extends('layouts.app', ['title' => $vendor->code . ' ' . trans('words.vendor')])


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
                    {{ $vendor->code }}
                </a>
            </li>
        </ul>
    </nav>
@endsection

@section('content')

    <div class="box">
        <div class="columns">
            <div class="column is-6">
                <h1 class="title">{{ $vendor->code }}</h1>
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
                            <td>{{ $vendor->code }}</td>
                        </tr>
                        <tr>
                            <td><strong>{{ __('words.description') }}</strong></td>
                            <td>{{ $vendor->description }}</td>
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
                            <td><strong>{{ __('words.contact-person') }}</strong></td>
                            <td>{{ $vendor->contact_person }}</td>
                        </tr>
                        <tr>
                            <td><strong>{{ __('words.contact-details') }}</strong></td>
                            <td>{{ $vendor->contact_details }}</td>
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

@endsection
