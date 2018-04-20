@extends('layouts.app', ['title' => trans('words.security')])

@section('content')

	
	<ol class="breadcrumb">
		<li>
			<a href="{{ route('dashboard.index') }}">
				<i class="fa fa-folder-open"></i>
				{{ trans('words.dashboard') }}
			</a>
		</li>
		<li class="active">{{ trans('words.security') }}</li>
	</ol>

	<div class="row">
			<div class="col-md-12">
				<p>{{ trans('statements.everything-is-working-ok') }}
			</div>
		</div>

@endsection
