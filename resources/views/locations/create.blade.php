@extends('layouts.app', ['title' => 'Create location'])

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
                <a href="{{ route('locations.index') }}">
                    <span class="icon is-small"><i class="fa fa-map-marker"></i></span>
                    <span>Locations</span>
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
            <p class="is-uppercase"><b>Location details</b></p>
            <form class="form" action="{{ route('locations.store') }}" method="post" style="margin-top:2rem">
                @csrf
                <div class="field">
                    <label for="name" class="label">Name</label>

                    <p class="control">
                        <input id="name" type="text" class="input {{ $errors->has('name') ? ' is-danger' : '' }}"
                               name="name"
                               value="{{ old('name') }}" required>

                        @if ($errors->has('name'))
                            <span class="help is-danger">
                	            {{ $errors->first('name') }}
                	        </span>
                        @endif
                    </p>
                </div>
                <div class="field">
                    <button type="submit" class="button is-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
