@extends('layouts.app', ['title' => trans('words.manufacturers')])

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
                    <span class="icon is-small"><i class="fa fa-industry"></i></span>
                    <span>{{ trans('words.manufacturers') }}</span>
                </a>
            </li>
            <li class="is-active">
                <a href="#">{{ __('words.all') }}</a>
            </li>
        </ul>
    </nav>
@endsection

@section('content')

    <div class="columns">
        <div class="column is-12">
            <table class="table is-fullwidth is-size-7">
                <thead>
                <tr>
                    <th>{{ __('words.code') }}</th>
                    <th>{{ __('words.name') }}</th>
                    <th>{{ __('words.website') }}</th>
                    <th>{{ __('words.support-url') }}</th>
                    <th>{{ __('words.support-phone') }}</th>
                    <th>{{ __('words.support-email') }}</th>
                    <th>{{ __('words.created-by') }}</th>
                    <th>{{ __('words.updated-at') }}</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($manufacturers as $manufacturer)
                    <tr>
                        <td>{{ $manufacturer->id }}</td>
                        <td>{{ $manufacturer->name }}</td>
                        <td><a target="_blank" rel="noopener noreferrer" href="{{ $manufacturer->website }}">{{ $manufacturer->website }}</a></td>
                        <td><a target="_blank" rel="noopener noreferrer" href="{{ $manufacturer->support_url }}">{{ $manufacturer->support_url }}</a></td>
                        <td>{{ $manufacturer->support_phone }}</td>
                        <td><a href="mailto:{{ $manufacturer->email }}">{{ $manufacturer->email }}</a></td>
                        <td>{{ optional($manufacturer->created_by)->name }}</td>
                        <td>{{ $manufacturer->updated_at }}</td>
                        <td class="has-text-right">
                            <a href="{{ route('manufacturers.show', ['id' => $manufacturer->id]) }}" class="button is-small has-icon">
                                <span class="icon is-small"><i class="fa fa-eye"></i></span>
                                <span>{{ __('words.show') }}</span>
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection
