@extends('layouts.app', [
	'title' => trans('words.dashboard'),
	'activeMenu' => 'dashboard',
])

@section('header')
	<nav class="breadcrumb" aria-label="breadcrumbs">
		<ul>
			<li class="is-active"><a href="#" aria-current="page">{{ trans('words.dashboard') }}</a></li>
		</ul>
	</nav>
@endsection

@section('content')

	<div class="columns is-desktop is-vcentered has-text-centered">
		<div class="column is-12">
			<h2 class="title is-5">Everything is working in order.</h2>
			<h3 class="subtitle" style="font-size:13px; marign-top: 10px">Begin any procedures by navigating through the sidebar.</h3>
		</div>
	</div>

	<div class="columns">
		<div class="column is-4">
			<div class="panel">
				<p class="panel-heading">My Queue</p>
			</div>
		</div>
	</div>

@endsection
