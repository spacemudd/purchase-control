@extends('layouts.app', ['title' => 'Import employees'])

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
                <a href="{{ route('employees.index') }}">
                    <span class="icon is-small"><i class="fa fa-user"></i></span>
                    <span>{{ trans('words.employees') }}</span>
                </a>
            </li>
            <li class="is-active">
                <a href="#">Import</a>
            </li>
        </ul>
    </nav>
@endsection

@section('content')

    <div class="columns">
        <div class="column is-4 is-offset-4">
            <form action="{{ route('employees.import') }}"
                  method="post" class="box" accept-charset="UTF-8" enctype="multipart/form-data">
                {{ csrf_field() }}

                <b-field label="CSV File">
                    <input type="file" name="file" id='fileupload' accept=".csv" required>
                </b-field>

                @if($errors->has('file'))
                    <p class="help is-danger">{{ $errors->first('file') }}</p>
                @endif

                <hr>

                <b-field horizontal>
                    <div class="control">
                        <a class="button is-text" href="{{ route('employees.index') }}">
                            {{ __('words.cancel') }}
                        </a>
                    </div>
                    <div class="control">
                        <button class="button is-primary" type="submit">
                            Import
                        </button>
                    </div>
                </b-field>

            </form>
        </div>
    </div>

@endsection
