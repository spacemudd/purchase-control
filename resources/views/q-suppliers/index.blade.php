@extends('layouts.app', ['title' => trans('words.suppliers')])

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
					<span>{{ trans('words.suppliers') }}</span>
				</a>
			</li>
		</ul>
	</nav>
@endsection

@section('content')

	<manage-quotation-suppliers-page/>

@endsection
