@extends('layouts.app', [
	'title' => 'Requests ' . $statusSlug
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
            <li>
                <a href="{{ route('requests.index') }}">
                    <span class="icon is-small"><i class="fa fa-file-o"></i></span>
                    <span>{{ trans('words.requests') }}</span>
                </a>
            </li>
            <li class="is-active">
                <a href="#">
                    <span class="is-capitalized">{{ $statusSlug }}</span>
                </a>
            </li>
        </ul>
    </nav>
@endsection

@section('content')
    <section class="hero">
        <div class="hero-body">
            <div class="container has-text-centered">
                <h1 class="title is-5"><span class="is-capitalized">{{ $statusSlug }}</span></h1>
                <h2 class="subtitle is-7">Requests</h2>
            </div>
        </div>
    </section>
    <div class="columns">
        <div class="column is-12">
            <table class="table is-fullwidth is-narrow is-size-7">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Number</th>
                    <th>Employee (By)</th>
                    <th>Cost Center (By)</th>
                    <th>Employee (For)</th>
                    <th>Cost Center (For)</th>
                    <th>Created by</th>
                    <th>Created at</th>
                    <th>Updated at</th>
                </tr>
                </thead>
                <tbody>
                @if($data->total() == 0)
                    <tr>
                        <td class="has-text-centered" style="line-height:50px;" colspan="8">No data to display</td>
                    </tr>
                @endif
                @foreach($data as $record)
                    <tr>
                        <td>
                            <a href="{{ route('requests.show', ['id' => $record->id]) }}">{{ $record->id }}</a>
                        </td>
                        <td>
                            <a href="{{ route('requests.show', ['id' => $record->id]) }}">{{ $record->number }}</a>
                        </td>
                        <td>{{ $record->requested_by->code . ' - ' . $record->requested_by->name }}</td>
                        <td>{{ $record->cost_center_by->code . ' - ' . $record->cost_center_by->description }}</td>
                        <td>
                            @if($record->requested_for)
                                {{ $record->requested_for->code . ' - ' . $record->requested_for->name }}
                            @endif
                        </td>
                        <td>
                            @if($record->cost_center_for)
                                {{ $record->cost_center_for->code . ' - ' . $record->cost_center_for->description }}
                            @endif
                        </td>
                        <td>{{ $record->created_by->display_name }}</td>
                        <td>{{ $record->created_at }}</td>
                        <td>{{ $record->updated_at }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            {{ $data->links('vendor.pagination.bulma') }}
        </div>
    </div>
@endsection
