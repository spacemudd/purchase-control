@extends('layouts.app', ['title' => $manufacturer->id . ' - ' . $manufacturer->name . ' - ' . trans('words.manufacturer')])


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
                <a href="{{ route('manufacturers.index') }}">
                    <span class="icon is-small"><i class="fa fa-cubes"></i></span>
                    <span>{{ trans('words.manufacturers') }}</span>
                </a>
            </li>
            <li class="is-active">
                <a href="{{ route('manufacturers.show', ['id' => $manufacturer->id]) }}">
                    {{ $manufacturer->id }} - {{ $manufacturer->name }}
                </a>
            </li>
        </ul>
    </nav>
@endsection

@section('content')

    <div class="box">
        <div class="columns">
            <div class="column is-6">
                <h1 class="title">{{ $manufacturer->id }}</h1>
                <p class="subtitle is-6">{{ __('words.manufacturer-code') }}</p>
            </div>
            <div class="column is-6 has-text-right">
                <a href="{{ route('manufacturers.edit', ['id' => $manufacturer->id]) }}" class="button is-small is-warning">
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
                            <td>{{ $manufacturer->id }}</td>
                        </tr>
                        <tr>
                            <td><strong>{{ __('words.name') }}</strong></td>
                            <td>{{ $manufacturer->name }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="column is-6">
                <table class="table is-fullwidth is-size-7">
                    <tbody>
                        <tr>
                            <td><strong>{{ __('words.website') }}</strong></td>
                            <td>{{ $manufacturer->website }}</td>
                        </tr>
                        <tr>
                            <td><strong>{{ __('words.support-url') }}</strong></td>
                            <td>{{ $manufacturer->support_url }}</td>
                        </tr>
                        <tr>
                            <td><strong>{{ __('words.support-phone') }}</strong></td>
                            <td>{{ $manufacturer->support_phone }}</td>
                        </tr>
                        <tr>
                            <td><strong>{{ __('words.support-email') }}</strong></td>
                            <td>{{ $manufacturer->support_email }}</td>
                        </tr>
                        <tr>
                            <td><strong>{{ __('words.updated-at') }}</strong></td>
                            <td>
                                {{ $manufacturer->updated_at }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
