@extends('layouts.app', ['title' => trans('words.create') . ' ' . trans('words.purchase-order')])

@section('content')

	
	<ol class="breadcrumb">
		<li>
			<a href="{{ route('dashboard.index') }}">
				<i class="fa fa-folder-open"></i>
				{{ trans('words.dashboard') }}
			</a>
		</li>
		<li><a href="{{ route('procedures.index') }}">{{ trans('words.procedures') }}</a></li>
		<li><a href="{{ route('purchase-orders.index') }}">{{ trans('words.purchase-orders') }}</a></li>
		<li class="active">{{ trans('words.create') }}</li>
	</ol>

	<div class="row">
			<div class="col-md-12">
				

				<div class="section">
					<div class="panel panel-primary">
					
						<div class="panel-heading">
							<div class="panel-title">
								{{ trans('words.create') }} - {{ trans('words.draft-purchase-order') }}
							</div>
						</div>
						
						<div class="panel-body">

								<form class="form-horizontal form-groups-bordered" method="POST" action="{{ route('purchase-orders.store') }}">
									{{ csrf_field() }}

									<div class="row">

										<div class="col-md-6">
											<div class="form-group{{ $errors->has('main_order_number') ? ' has-error' : '' }}">
												<label class="col-md-4 control-label">{{ trans('words.main-order-number') }}</label>

												<div class="col-md-6">
													<input type="text" class="form-control" name="main_order_number" value="{{ old('main_order_number') }}" autocomplete="off">

													@if ($errors->has('main_order_number'))
														<span class="help-block">
													<strong>{{ $errors->first('main_order_number') }}</strong>
												</span>
													@endif
												</div>
											</div>
										</div>

									</div>

									<hr>

									<div class="row">

										<div class="col-md-6">
											<div class="form-group{{ $errors->has('order_number') ? ' has-error' : '' }}">
												<label for="order_number" class="col-md-4 control-label">{{ trans('words.purchase-order-number') }} *</label>

												<div class="col-md-6">
													<input id="order_number" type="text" class="form-control"
														   name="order_number"
														   autocomplete="off"
														   value="{{ old('order_number') }}" required>

													@if($errors->has('order_number'))
														<span class="help-block">
															<strong>{{ $errors->first('order_number') }}</strong>
														</span>
													@endif
												</div>
											</div>
										</div>

										<div class="col-md-6">

											<div class="form-group{{ $errors->has('date') ? ' has-error' : '' }}">
												<label class="col-md-3 control-label">{{ trans('words.purchase-order-date') }} *</label>

												<div class="col-md-5">
													<masked-input mask="11/11/1111" name="date" value="{{ old('date') }}" class="form-control" autocomplete="off" required />

													@if ($errors->has('date'))
													<span class="help-block">
														<strong>{{ $errors->first('date') }}</strong>
													</span>
													@endif
												</div>
											</div>


										</div>

									</div>


									<hr>

									<div class="row">

										<div class="col-md-6">
											<div class="form-group{{ $errors->has('vendor_id') ? ' has-error' : '' }}">
												<label class="col-md-4 control-label">{{ trans('words.vendor') }} *</label>

												<div class="col-md-6">

													<select name="vendor_id" id="vendor_id" class="form-control">
														{{-- TODO: code --}}
														{{-- TODO: vendor re-insert --}}
														<option value=""></option>
														@foreach($vendors as $vendor)
															<option value="{{ $vendor->id }}">
																{{ $vendor->description }}
															</option>
														@endforeach
													</select>

													@if ($errors->has('vendor_id'))
														<span class="help-block">
															<strong>{{ $errors->first('vendor_id') }}</strong>
														</span>
													@endif
												</div>
											</div>
										</div>

										<div class="col-md-6">
											<div class="form-group{{ $errors->has('delivery_number') ? ' has-error' : '' }}">
												<label class="col-md-3 control-label">{{ trans('words.vendor-delivery-number') }} *</label>

												<div class="col-md-5">
													<input type="text" class="form-control" name="delivery_number" autocomplete="off" value="{{ old('delivery_number') }}" required>

													@if ($errors->has('delivery_number'))
														<span class="help-block">
													<strong>{{ $errors->first('delivery_number') }}</strong>
												</span>
													@endif
												</div>
											</div>
										</div>

									</div>

									<hr>


									<div class="row">

										<div class="col-md-6">
											<div class="form-group{{ $errors->has('department_id') ? ' has-error' : '' }}">
												<label class="col-md-4 control-label">{{ trans('words.departments') }} *</label>

												<div class="col-md-6">

													<select name="department_id" id="department_id" class="form-control">
														{{-- TODO: code --}}
														{{-- TODO: department --}}
														<option value=""></option>
														@foreach($departments as $department)
															<option value="{{ $department->id }}">
																{{ $department->description }}
															</option>
														@endforeach
													</select>

													@if ($errors->has('department_id'))
														<span class="help-block">
													<strong>{{ $errors->first('department_id') }}</strong>
												</span>
													@endif
												</div>
											</div>
										</div>

										<div class="col-md-6">
											<div class="form-group{{ $errors->has('employee_id') ? ' has-error' : '' }}">
												<label class="col-md-3 control-label">{{ trans('words.employee') }} *</label>

												<div class="col-md-5">

													<simple-search-return-id
															search-endpoint="{{ route('api.search.employee') }}"
															field-name="employee_id"
													>
													</simple-search-return-id>

													@if ($errors->has('employee_id'))
														<span class="help-block">
													<strong>{{ $errors->first('employee_id') }}</strong>
												</span>
													@endif
												</div>
											</div>
										</div>
									</div>

									<hr>

									<div class="row">

										<div class="col-md-6">
											<div class="form-group{{ $errors->has('delivery_date') ? ' has-error' : '' }}">
												<label class="col-md-4 control-label">{{ trans('words.delivery-date') }} *</label>

												<div class="col-md-6">
													<masked-input mask="11/11/1111" name="delivery_date" value="{{ old('delivery_date') }}" autocomplete="off" class="form-control" required />

													@if ($errors->has('delivery_date'))
														<span class="help-block">
													<strong>{{ $errors->first('delivery_date') }}</strong>
												</span>
													@endif
												</div>
											</div>
										</div>

									</div>

									<div class="form-group">
										<div class="col-sm-offset-3 col-sm-5">
											<button type="submit" class="btn btn-success btn-lg">{{ trans('words.create') }}</button>
										</div>
									</div>

								</form>
							
						</div>
					
					</div>
				</div>
			
			</div>
		</div>

@endsection
