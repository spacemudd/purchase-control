@extends('layouts.app', ['title' => $address->location])


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
                <a href="{{ route('addresses.index') }}">
                    <span class="icon is-small"><i class="fa fa-map-marker"></i></span>
                    <span>{{ trans('words.addresses') }}</span>
                </a>
            </li>
            <li class="is-active">
                <a href="{{ route('addresses.show', ['id' => $address->id]) }}">
                    {{ $address->location }}
                </a>
            </li>
        </ul>
    </nav>
@endsection

@section('content')

    <div class="columns">
        <div class="column is-6 is-offset-3 has-text-right">
            <form action="{{ route('addresses.destroy', ['id' => $address->id]) }}" method="post" class="is-inline">
                {{ csrf_field() }}
                <input type="hidden" name="_method" value="delete">
                <button class="button is-small is-danger">Archive</button>
            </form>
            {{--
            <a href="{{ route('addresses.edit', ['id' => $address->id]) }}"
               class="button is-warning is-small"
               style="margin-left:30px;"
            >Edit</a>
            --}}
        </div>
    </div>

    <div class="columns">
        <div class="column is-6 is-offset-3">
            <div class="content">
                <h1 class="title">Type</h1>
                <p>{{ $address->type_human }}</p>
                <h1 class="title">Location</h1>
                <p>{!! nl2br(e($address->location)) !!}</p>
                <h1 class="title">Department</h1>
                <p>{{ $address->department }}</p>
                <h1 class="title">Contact Name</h1>
                <p>{{ $address->contact_name }}</p>
                <h1 class="title">Phone</h1>
                <p>{{ $address->phone }}</p>
                <h2 class="title">Email</h2>
                <p>{{ $address->email }}</p>
            </div>
        </div>
    </div>

@endsection
