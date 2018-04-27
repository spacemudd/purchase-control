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
					<span>{{ trans('words.item-templates') }}</span>
				</a>
			</li>
			<li class="is-active">
				<a href="#">New Item Template</a>
			</li>
		</ul>
	</nav>
@endsection


@section('content')

	<div class="columns">
		<div class="column is-6 is-offset-3">
			<p class="title is-spaced">New item template</p>

			<form method="post" action="{{ route('item-templates.store') }}">
				{{ csrf_field() }}

				<div class="columns is-multiline">
					
					<div class="column is-6">
						<div class="field">
							<label for="name" class="label">Name <span class="has-text-danger">*</span></label>

							<p class="control">
								<input id="name" type="text"
									   class="input {{ $errors->has('name') ? ' is-danger' : '' }}"
									   name="name" value="{{ old('name') }}" required>

								@if ($errors->has('name'))
									<span class="help is-danger">
							            {{ $errors->first('name') }}
							        </span>
								@endif
							</p>
						</div>
					</div>

					<div class="column is-6">
						<div class="field">
							<label for="category_id" class="label">Category</label>

							<div class="control">
								<div class="select is-fullwidth{{ $errors->has('category_id') ? ' is-danger' : '' }}">
									<select name="category_id">
										@foreach($categories as $category)
											<option value="{{ $category->id }}">{{ $category->name }}</option>
										@endforeach
									</select>
								</div>

								@if ($errors->has('category_id'))
									<span class="help is-danger">
							            {{ $errors->first('category_id') }}
							        </span>
								@endif
							</div>
						</div>
					</div>
					
					<div class="column is-6">
						<div class="field">
							<label for="model_number" class="label">Model Number</label>

							<p class="control">
								<input id="model_number" type="text"
									   class="input {{ $errors->has('model_number') ? ' is-danger' : '' }}"
									   name="model_number"
									   value="{{ old('model_number') }}" required>

								@if ($errors->has('model_number'))
									<span class="help is-danger">
										{{ $errors->first('model_number') }}
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
											<option value="{{ $manufacturer->id }}">{{ $manufacturer->name }}</option>
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
									   name="unit_price" value="{{ old('unit_price') }}">

							<p class="help">Auto-fills when creating PO items</p>
							@if ($errors->has('unit_price'))
								<span class="help is-danger">
							            {{ $errors->first('unit_price') }}
							        </span>
								@endif
								</p>
						</div>
					</div>

					<div class="column is-6">
						<div class="field">
							<label for="eol" class="label">End-of-life (EOL)</label>

							<p class="control">
							<div class="select is-fullwidth">
								<select name="eol">
									<option value=""></option>
									<option value="12">1 year</option>
									<option value="24">2 year</option>
									<option value="36">3 years</option>
									<option value="48">4 years</option>
									<option value="60">5 years</option>
								</select>
							</div>

							<p class="help">On purchase, EOL will be calculated automatically.</p>

							@if ($errors->has('eol'))
								<span class="help is-danger">
						            {{ $errors->first('eol') }}
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
