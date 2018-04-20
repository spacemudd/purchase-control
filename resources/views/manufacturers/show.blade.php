@extends('layouts.app', ['title' => $manufacturer->code . ' - ' . trans('words.manufacturer')])


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
                    {{ $manufacturer->code }} - {{ $manufacturer->description }}
                </a>
            </li>
        </ul>
    </nav>
@endsection

@section('content')

    <div class="box">
        <div class="columns">
            <div class="column is-6">
                <h1 class="title">{{ $manufacturer->code }}</h1>
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
                            <td>{{ $manufacturer->code }}</td>
                        </tr>
                        <tr>
                            <td><strong>{{ __('words.description') }}</strong></td>
                            <td>{{ $manufacturer->description }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="column is-6">
                <table class="table is-fullwidth is-size-7">
                    <tbody>
                        <tr>
                            <td><strong>{{ __('words.active') }}</strong></td>
                            <td>
                                <span class="tag unset-height{{ $manufacturer->active ? ' is-success' : ' is-danger' }}">
                                    @if($manufacturer->active)
                                        {{ __('words.yes') }}
                                    @else
                                        {{ __('words.no') }}
                                    @endif
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>{{ __('words.created-at') }}</strong></td>
                            <td>
                                {{ $manufacturer->created_at->format('d-m-Y') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
