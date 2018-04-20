@extends('layouts.app', ['title' => trans('words.items')])

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
				<a href="{{ route('items.index') }}">
					<span class="icon is-small"><i class="fa fa-barcode"></i></span>
					<span>{{ trans('words.items') }}</span>
				</a>
			</li>
		</ul>
	</nav>
@endsection

@section('content')

	<div class="columns">
		<div class="column is-4">
			<p class="title is-6">
				<b>{{ trans('words.active') }} {{ trans('words.items') }}</b>
			</p>

			<div class="notification is-success">
				<p class="subtitle is-7">
					<b>{{ $activeItems }}</b>
				</p>
			</div>
		</div>
	</div>

	<items></items>

@endsection
