@extends('layouts.app', ['title' => $itemTemplate->code . ' - Edit - Item Catalog'])


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
					<span>Item Catalog</span>
				</a>
			</li>
			<li>
				<a href="{{ route('item-templates.show', ['id' => $itemTemplate->id]) }}">
					{{ $itemTemplate->code }}
				</a>
			</li>
			<li class="is-active">
				<a href="#">Edit</a>
			</li>
		</ul>
	</nav>
@endsection


@section('content')

	<div class="columns">
		<div class="column is-6 is-offset-3">
			<p class="title is-spaced">Editing {{ $itemTemplate->code }}</p>

			<form method="post" action="{{ route('item-templates.update', ['id' => $itemTemplate->id]) }}">
				{{ csrf_field() }}
				<input type="hidden" name="_method" value="put">

				<div class="columns is-multiline">
					<div class="column is-6">
						<div class="field">
							<label for="code" class="label">{{ __('words.code') }} <span class="has-text-danger">*</span></label>

							<p class="control">
								<input id="code" type="text"
									   class="input {{ $errors->has('code') ? ' is-danger' : '' }}"
									   name="code" value="{{ $itemTemplate->code }}" required>

								@if ($errors->has('code'))
									<span class="help is-danger">
							            {{ $errors->first('code') }}
							        </span>
								@endif
							</p>
						</div>
					</div>

					<div class="column is-6">
						<div class="field">
							<label for="description" class="label">Description</label>

							<p class="control">
								<input id="description" type="text"
									   class="input {{ $errors->has('description') ? ' is-danger' : '' }}"
									   name="description"
									   value="{{ $itemTemplate->description }}">

								@if ($errors->has('description'))
									<span class="help is-danger">
										{{ $errors->first('description') }}
									</span>
								@endif
							</p>
						</div>
					</div>

					<div class="column is-6">
						<div class="field">
							<label for="manufacturer_id" class="label">Manufacturer</label>

							<div class="control">
								<div class="select is-fullwidth">
									<select name="manufacturer_id" class="{{ $errors->has('manufacturer_id') ? ' is-danger' : '' }}">
										<option value=""></option>
										@foreach($manufacturers as $manufacturer)
											<option value="{{ $manufacturer->id }}"{{ $manufacturer->id == $itemTemplate->manufacturer_id ? ' selected' : null }}>{{ $manufacturer->name }}</option>
										@endforeach
									</select>
								</div>

								@if ($errors->has('manufacturer_id'))
									<span class="help is-danger">
										{{ $errors->first('manufacturer_id') }}
									</span>
								@endif
							</div>
						</div>
					</div>

					<div class="column is-6">
						<div class="field">
							<label for="unit_price" class="label">Default Price</label>

							<p class="control">
								<input id="unit_price" type="number"
									   step="0.01"
									   min="0"
									   class="input {{ $errors->has('unit_price') ? ' is-danger' : '' }}"
									   name="unit_price" value="{{ $itemTemplate->unit_price }}">

								@if ($errors->has('unit_price'))
									<span class="help is-danger">
										{{ $errors->first('unit_price') }}
									</span>
								@endif
							</p>
						</div>
					</div>

					<div class="column is-12 has-text-right">
						<a class="button is-text" href="{{ route('item-templates.index') }}">{{ __('words.cancel') }}</a>
						<button type="submit" class="button is-primary">{{ trans('words.save') }}</button>
					</div>
				</div>
			</form>
		</div>
	</div>

@endsection
