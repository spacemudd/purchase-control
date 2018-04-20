@extends('layouts.app', [
	'title' => trans('words.purchase-orders')
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
				<a href="{{ route('purchase-orders.index') }}">
					<span class="icon is-small"><i class="fa fa-shopping-cart"></i></span>
					<span>{{ trans('words.purchase-orders') }}</span>
				</a>
			</li>
		</ul>
	</nav>
@endsection

@section('content')

	<div class="columns">
			<div class="column is-4">
				<p class="title is-6">
					{{ trans('words.draft') }} {{ trans('words.purchase-orders') }}
				</p>

				<a href="{{ route('purchase-orders.draft') }}">
					<div class="notification is-warning">
						<p class="subtitle is-7">
							<b>{{ $draftCounter }}</b>
						</p>
					</div>
				</a>
			</div>

			<div class="column is-4">
					<p class="title is-6">
						{{ trans('words.committed') }} {{ trans('words.purchase-orders') }}
					</p>

				<a href="{{ route('purchase-orders.committed') }}">
					<div class="notification is-success">
						<p class="subtitle is-7">
							<b>{{ $committedCounter }}</b>
						</p>
					</div>
				</a>
			</div>


			<div class="column is-4">
					<p class="title is-6">
						{{ trans('words.void') }} {{ trans('words.purchase-orders') }}
					</p>

				<a href="{{ route('purchase-orders.void') }}">
					<div class="notification is-danger">
						<p class="subtitle is-7">
							<b>{{ $voidCounter }}</b>
						</p>
					</div>
				</a>
			</div>
	</div>

	<purchase-orders></purchase-orders>

@endsection
