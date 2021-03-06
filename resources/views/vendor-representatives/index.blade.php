@extends('layouts.app', ['title' => trans('words.vendors')])

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
				<a href="{{ route('vendors.index') }}">
					<span class="icon is-small"><i class="fa fa-truck"></i></span>
					<span>{{ trans('words.vendors') }}</span>
				</a>
			</li>
		</ul>
	</nav>
@endsection

@section('content')

	<div class="columns">
		<div class="column is-4">
			<p class="title is-6">
				<b>{{ trans('words.inactive') }} {{ trans('words.vendors') }}</b>
			</p>

			<div class="notification is-warning">
				<p class="subtitle is-7">
					<b>{{ $inactiveVendors }}</b>
				</p>
			</div>
		</div>

		<div class="column is-4">
			<p class="title is-6">
				<b>{{ trans('words.active') }} {{ trans('words.vendors') }}</b>
			</p>

			<div class="notification is-success">
				<p class="subtitle is-7">
					<b>{{ $activeVendors }}</b>
				</p>
			</div>
		</div>
	</div>

	<vendors></vendors>

@endsection
