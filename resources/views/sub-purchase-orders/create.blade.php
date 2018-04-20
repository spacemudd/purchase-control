@extends('layouts.app')

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
                <a href="{{ route('purchase-orders.index') }}">
                    <span class="icon is-small"><i class="fa fa-shopping-cart"></i></span>
                    <span>{{ trans('words.purchase-orders') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('purchase-orders.show', ['id' => $purchaseOrder->id]) }}">
                    @if($purchaseOrder->number)
                        {{ $purchaseOrder->number }}
                    @else
                        {{ $purchaseOrder->id }}
                    @endif
                </a>
            </li>
            <li class="is-active">
                <a href="#">New Sub PO</a>
            </li>
        </ul>
    </nav>
@endsection

@section('content')
    <div class="columns">
        <div class="column is-8 is-offset-2">

            <form action="" method="post">
                <div class="field">
                    <label for="" class="label"></label>

                    <div class="control">
                        <div class="select">
                            <select name="type">
                                <option value="">Multi Vendors</option>
                            </select>
                        </div>
                        <input id="" type="" class="input {{ $errors->has('') ? ' is-danger' : '' }}" name=""
                               value="{{ old('') }}">

                        @if ($errors->has(''))
                            <span class="help is-danger">
                	            {{ $errors->first('') }}
                	        </span>
                        @endif
                    </div>
                </div>

            </form>

        </div>
    </div>
@stop
