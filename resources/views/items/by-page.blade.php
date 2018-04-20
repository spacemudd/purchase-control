@extends('layouts.app', ['title' => trans('words.assets')])

@section('content')

	
	<ol class="breadcrumb">
		<li>
			<a href="{{ route('dashboard.index') }}">
				<i class="fa fa-folder-open"></i>
				{{ trans('words.dashboard') }}
			</a>
		</li>
		<li><a href="{{ route('settings.index') }}">{{ trans('words.settings') }}</a></li>
		<li class="active">{{ trans('words.assets') }} ({{ __('words.by-page') }})</li>
	</ol>

	<div class="row">
		@if(session()->has('message'))
			@component('components.alerts.basic', ['status' => (session()->has('status') == null) ? 'default' : session()->get('status')])
				{{ session()->get('message') }}
			@endcomponent
		@endif
	</div>

	<assets></assets>

@endsection
