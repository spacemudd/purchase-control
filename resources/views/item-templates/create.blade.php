@extends('layouts.app', ['title' => trans('words.create') . ' Item Template'])


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
				<a href="{{ route('item-templates.index') }}">
					<span class="icon is-small"><i class="fa fa-building"></i></span>
					<span>{{ trans('words.item-catalog') }}</span>
				</a>
			</li>
			<li class="is-active">
				<a href="#">New Item Catalog</a>
			</li>
		</ul>
	</nav>
@endsection


@section('content')

	<div class="columns">
		<div class="column is-8 is-offset-2">
			<p class="title is-spaced">New item catalog</p>

			<item-template-form action="{{ route('api.item-templates.store') }}"
								:categories="{{ $categories }}"
                                csrf-token="{{ csrf_token() }}"
                                :manufacturers="{{ $manufacturers }}"
								cancel-url="{{ route('item-templates.index') }}"
            ></item-template-form>
		</div>
	</div>

@endsection
