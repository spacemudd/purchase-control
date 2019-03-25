@extends('layouts.app', ['title' => 'Create Material Requests'])

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
                <a href="{{ route('material-requests.index') }}">
                    <span class="icon is-small"><i class="fa fa-shopping-cart"></i></span>
                    <span>Material Requests</span>
                </a>
            </li>
            <li class="is-active">
                <a href="#">
                    Create
                </a>
            </li>
        </ul>
    </nav>
@endsection

@section('content')
<div class="columns">
    <div class="column is-6">
        <div class="box">
            <p class="is-uppercase"><b>Request details</b></p>
            <form class="form" action="{{ route('material-requests.store') }}" method="post" style="margin-top:2rem">
                @csrf
                <div class="field">
                    <label for="date" class="label">Date <span class="has-text-danger">*</span></label>

                    <p class="control">
                        <input id="date" type="date" class="input {{ $errors->has('date') ? ' is-danger' : '' }}"
                               name="date"
                               value="{{ old('date') }}" required>

                        @if ($errors->has('date'))
                            <span class="help is-danger">
                                {{ $errors->first('date') }}
                            </span>
                        @endif
                    </p>
                </div>

                <div class="field">
                    <label for="location" class="label">Location <span class="has-text-danger">*</span></label>

                    <div class="control">
                        <div class="select is-fullwidth{{ $errors->has('location') ? ' is-danger' : '' }}">
                            <select name="location_id" id="location" required>
                                @foreach ($locations as $location)
                                    <option value="{{ $location->id }}">{{ $location->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <span class="help">Click here to <a target="_blank" href="{{ route('locations.create') }}">add new locations.</a></span>

                        @if ($errors->has('location'))
                            <span class="help is-danger">
                	            {{ $errors->first('location') }}
                	        </span>
                        @endif
                    </div>
                </div>

                <div class="field">
                    <div class="field">
                        <label for="cost_center_id" class="label">Cost Center <span class="has-text-danger">*</span></label>

                        <div class="control">
                            <select-department name="cost_center_id"
                                               url="{{ route('api.search.department') }}">
                            </select-department>
                            <p class="help">Click here to <a href="#" @click="$store.commit('Department/showNewModal', true)">add new department</a>.</p>
                        </div>
                    </div>
                </div>

                <div class="field">
                    <button class="button is-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
