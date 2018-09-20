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
            <a href="{{ route('addresses.edit', ['id' => $address->id]) }}"
               class="button is-warning is-small"
               style="margin-left:30px;"
            >Edit</a>
        </div>
    </div>

    <div class="columns">
        <div class="column is-6 is-offset-3">
            <table class="table is-fullwidth">
            	<tbody>
                    <tr>
                        <th>Type</th>
                        <td>{{ $address->type_human }}</td>
                    </tr>
                    <tr>
                        <th>Location</th>
                        <td>{!! nl2br(e($address->location)) !!}</td>
                    </tr>
                    <tr>
                        <th>Department</th>
                        <td>{{ $address->department }}</td>
                    </tr>
                    <tr>
                        <th>Contact Name</th>
                        <td>{{ $address->contact_name }}</td>
                    </tr>
                    <tr>
                        <th>Phone</th>
                        <td>{{ $address->phone }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $address->email }}</td>
                    </tr>
            	</tbody>
            </table>
        </div>
    </div>

@endsection
