@extends('layouts.app', ['title' => 'Sales Taxes'])

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
				<a href="{{ route('sales-taxes.index') }}">
					<span class="icon is-small"><i class="fa fa-balance-scale"></i></span>
					<span>Sales Taxes</span>
				</a>
			</li>
		</ul>
	</nav>
@endsection

@section('content')

@endsection
