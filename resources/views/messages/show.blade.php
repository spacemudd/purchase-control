@extends('layouts.app', [
	'title' => 'Messages'
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
                <a href="#">
                    <span class="icon is-small"><i class="fa fa-inbox"></i></span>
                    <span>Inbox</span>
                </a>
            </li>
            <li class="is-active">
                <a href="#">
                    <span>Messages</span>
                </a>
            </li>
        </ul>
    </nav>
@endsection


@section('content')

    <div class="columns">
        <div class="column is-12">
            <div class="box">
                {!! $message->content !!}
            </div>
            <p class="is-size-7">{{ $message->type }} - {{ $message->created_at }}</p>
        </div>
    </div>

@endsection
