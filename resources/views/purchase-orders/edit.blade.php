@extends('layouts.app', ['title' => $purchase_order->prefix . $purchase_order->id . ' - ' .
									trans('words.edit-draft-purchase-order')])

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
		<li><a href="{{ route('purchase-orders.show', ['id' => $purchase_order->id]) }}">{{ $purchase_order->order_number }}</a></li>
		<li class="active">{{ trans('words.edit') }}</li>
	</ol>

	<div class="row">
			<div class="col-md-12">

				<div class="section">

					<div class="section">
						<div class="level">
							<div class="level-left">
								
							</div>
							<div class="level-right">
								<form method="POST" action="{{ route('purchase-orders.destroy', ['id' => $purchase_order->id]) }}">
									{{ csrf_field() }}
									<input type="hidden" name="_method" value="delete" class="input">
									<button class="btn btn-danger">
										{{ trans('words.delete') }}
									</button>
								</form>
							</div>
						</div>
					</div>

					<div class="panel panel-primary">
					
						<div class="panel-heading">
							<div class="panel-title">
								{{ trans('words.edit-draft-purchase-order') }}
							</div>
						</div>
						
						<div class="panel-body">

								<form class="form-horizontal form-groups-bordered" method="POST" action="{{ route('purchase-orders.update', ['id' => $purchase_order->id]) }}">
									{{ csrf_field() }}
									<input type="hidden" name="_method" value="PUT">

									<div class="form-group{{ $errors->has('vendor_id') ? ' has-error' : '' }}">
										<label class="col-md-3 control-label">{{ trans('words.vendor') }} *</label>

										<div class="col-md-5">

											<select name="vendor_id" class="form-control" required>
												@foreach($vendors as $vendor)
													<option value="{{ $vendor->id }}"{{ ($vendor->id == $purchase_order->vendor->id) ? 'selected' : '' }}>{{ $vendor->vendor_human }}</option>
												@endforeach
											</select>
									
											@if ($errors->has('vendor_id'))
												<span class="help-block">
													<strong>{{ $errors->first('vendor_id') }}</strong>
												</span>
											@endif
										</div>
									</div>

									<div class="form-group{{ $errors->has('delivery_number') ? ' has-error' : '' }}">
										<label class="col-md-3 control-label">{{ trans('words.vendor-delivery-number') }} *</label>
									
										<div class="col-md-5">
											<input type="text" class="form-control" name="delivery_number" value="{{ $purchase_order->delivery_number }}" required>
									
											@if ($errors->has('delivery_number'))
												<span class="help-block">
													<strong>{{ $errors->first('delivery_number') }}</strong>
												</span>
											@endif
										</div>
									</div>

									<div class="form-group{{ $errors->has('date') ? ' has-error' : '' }}">
										<label class="col-md-3 control-label">{{ trans('words.purchase-order-date') }} *</label>
									
										<div class="col-md-5">
											<input type="text" name="date" value="{{ $purchase_order->date_human }}" placeholder="d/m/Y" class="form-control" required>
									
											@if ($errors->has('date'))
												<span class="help-block">
													<strong>{{ $errors->first('date') }}</strong>
												</span>
											@endif
										</div>
									</div>

									<div class="form-group{{ $errors->has('main_order_number') ? ' has-error' : '' }}">
										<label class="col-md-3 control-label">{{ trans('words.main-order-number') }}</label>
									
										<div class="col-md-5">
											<input type="text" class="form-control" name="main_order_number" value="{{ $purchase_order->main_order_number }}">
									
											@if ($errors->has('main_order_number'))
												<span class="help-block">
													<strong>{{ $errors->first('main_order_number') }}</strong>
												</span>
											@endif
										</div>
									</div>

									<div class="form-group{{ $errors->has('department_id') ? ' has-error' : '' }}">
										<label class="col-md-3 control-label">{{ trans('words.departments') }} *</label>
									
										<div class="col-md-5">

											<select name="department_id" class="form-control" required>
												@foreach($departments as $department)
													<option value="{{ $department->id }}"{{ ($department->id == $purchase_order->department->id) ? 'selected' : '' }}>{{ $department->department_human }}</option>
												@endforeach
											</select>
									
											@if ($errors->has('department_id'))
												<span class="help-block">
													<strong>{{ $errors->first('department_id') }}</strong>
												</span>
											@endif
										</div>
									</div>

									<div class="form-group{{ $errors->has('employee_id') ? ' has-error' : '' }}">
										<label class="col-md-3 control-label">{{ trans('words.employee') }} *</label>
									
										<div class="col-md-5">

											<select name="employee_id" class="form-control" required>
												@foreach($employees as $employee)
													<option value="{{ $employee->id }}"{{ ($employee->id == $purchase_order->employee->id) ? 'selected' : '' }}>{{ $employee->displayname }}</option>
												@endforeach
											</select>
									
											@if ($errors->has('employee_id'))
												<span class="help-block">
													<strong>{{ $errors->first('employee_id') }}</strong>
												</span>
											@endif
										</div>
									</div>

									<div class="form-group{{ $errors->has('delivery_date') ? ' has-error' : '' }}">
										<label class="col-md-3 control-label">{{ trans('words.delivery-date') }} *</label>
									
										<div class="col-md-5">
											<input type="text" name="delivery_date" value="{{ $purchase_order->delivery_date_human }}" placeholder="d-m-Y" class="form-control" required>
									
											@if ($errors->has('delivery_date'))
												<span class="help-block">
													<strong>{{ $errors->first('delivery_date') }}</strong>
												</span>
											@endif
										</div>
									</div>
									

									<div class="form-group">
										<div class="col-sm-offset-3 col-sm-5">
											<button type="submit" class="btn btn-success btn-lg">{{ trans('words.edit') }}</button>
										</div>
									</div>

								</form>
							
						</div>
					
					</div>
				</div>
			
			</div>
		</div>

@endsection
