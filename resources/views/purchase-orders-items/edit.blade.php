@extends('layouts.app', ['title' => trans('words.create-purchase-item') . ' - ' .
									'#' . $item->purchase_order->prefix . $item->purchase_order->id
						])

@section('content')

	
	<ol class="breadcrumb">
		<li>
			<a href="{{ route('dashboard.index') }}">
				<i class="fa fa-folder-open"></i>
				{{ trans('words.dashboard') }}
			</a>
		</li>
		<li><a href="{{ route('procedures.index') }}">{{ trans('procedures') }}</a></li>
		<li><a href="{{ route('purchase-orders.index') }}">{{ trans('words.purchase-orders') }}</a></li>
		<li><a href="{{ route('purchase-orders.show', ['id' => $item->purchase_order->id]) }}">{{ $item->purchase_order->ref_id }}</a></li>
		<li><a href="#">{{ trans('words.item') }}</a></li>
		<li><a href="#">{{ $item->id }}</a></li>
		<li clas="active">{{ trans('words.edit') }}</li>
	</ol>

	<div class="row">
			<div class="col-md-12">

				<div class="section">
					<div class="panel panel-primary">
					
						<div class="panel-heading">
							<div class="panel-title">
								{{ trans('words.create-purchase-item') }}
							</div>
						</div>

						<div class="panel-body">

								<form class="form-horizontal form-groups-bordered" method="POST" action="{{ route('purchase-orders.items.update', 
																																	['purchase_order_id' => $item->purchase_order->id, 
																																	'item' => $item->id]) }}">
									{{ csrf_field() }}
									<input type="hidden" name="_method" value="PUT">


									<div class="form-group{{ $errors->has('manufacturer_serial_number') ? ' has-error' : '' }}">
										<label class="col-md-3 control-label">{{ trans('words.manufacturer-serial-number') }}</label>
									
										<div class="col-md-5">
											<input type="text" class="form-control" name="manufacturer_serial_number" value="{{ $item->manufacturer_serial_number }}">
									
											@if ($errors->has('manufacturer_serial_number'))
												<span class="help-block">
													<strong>{{ $errors->first('manufacturer_serial_number') }}</strong>
												</span>
											@endif
										</div>
									</div>

									<div class="form-group{{ $errors->has('system_tag_number') ? ' has-error' : '' }}">
										<label class="col-md-3 control-label">{{ trans('words.system-tag-number') }}</label>
									
										<div class="col-md-5">
											<input type="text" class="form-control" name="system_tag_number" value="{{ $item->system_tag_number }}">
									
											@if ($errors->has('system_tag_number'))
												<span class="help-block">
													<strong>{{ $errors->first('system_tag_number') }}</strong>
												</span>
											@endif
										</div>
									</div>

									<div class="form-group{{ $errors->has('finance_tag_number') ? ' has-error' : '' }}">
										<label class="col-md-3 control-label">{{ trans('words.finance-tag-number') }}</label>
									
										<div class="col-md-5">
											<input type="text" class="form-control" name="finance_tag_number" value="{{ $item->finance_tag_number }}">
									
											@if ($errors->has('finance_tag_number'))
												<span class="help-block">
													<strong>{{ $errors->first('finance_tag_number') }}</strong>
												</span>
											@endif
										</div>
									</div>

									<div class="form-group{{ $errors->has('unit_price') ? ' has-error' : '' }}">
										<label class="col-md-3 control-label">{{ trans('words.unit-price') }} *</label>
									
										<div class="col-md-5">

											<input type="number" step="0.01" name="unit_price" value="{{ $item->unit_price }}" class="form-control">
									
											@if ($errors->has('unit_price'))
												<span class="help-block">
													<strong>{{ $errors->first('unit_price') }}</strong>
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
