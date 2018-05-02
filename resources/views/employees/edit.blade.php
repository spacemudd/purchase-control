@extends('layouts.app', ['title' =>
                                    $employee->code . ' - ' .
                                    trans('words.employee') . ' ' .
                                    trans('words.edit')

                        ])

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
            <li>
                <a href="{{ route('employees.show', ['id' => $employee->id]) }}">{{ $employee->code }}</a>
            </li>
            <li class="is-active">
                <a href="#">{{ trans('words.edit') }}</a>
            </li>
        </ul>
    </nav>
@endsection

@section('content')

    @can('delete-employees')
        <div class="columns">
            <div class="column is-8 is-offset-2 has-text-right">
                <form method="post" action="{{ route('employees.destroy', ['id' => $employee->id]) }}">
                    {{ csrf_field() }}
                    <input type="hidden" name="_method" value="delete" class="input">
                    <button class="button is-small is-danger">
                        {{ trans('words.archive') }}
                    </button>
                    <p class="help">Employee will become hidden in forms.</p>
                </form>
                <hr>
            </div>
        </div>
    @endcan

    <div class="columns">
        <div class="column is-8 is-offset-2">
            <form method="post" action="{{ route('employees.update', ['id' => $employee->id]) }}">
                {{ csrf_field() }}
                <input type="hidden" name="_method" value="put">

                <div class="columns is-multiline">

                    <div class="column is-6">
                        <div class="field">
                            <label for="code" class="label">{{ trans('words.code') }} <span class="has-text-danger">*</span></label>

                            <p class="control">
                                <input id="code" type="text" class="input {{ $errors->has('code') ? ' is-danger' : '' }}" name="code"
                                       value="{{ $employee->code }}" required>

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
                            <label for="name" class="label">{{ trans('words.name') }} <span class="has-text-danger">*</span></label>

                            <p class="control">
                                <input id="name" type="text" class="input {{ $errors->has('name') ? ' is-danger' : '' }}"
                                       name="name"
                                       value="{{ $employee->name }}" required>

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
                            <label for="department_id" class="label">{{ trans('words.department') }}</label>

                            <div class="control">
                                <div class="select is-fullwidth">
                                    <select name="department_id">
                                        <option></option>
                                        @foreach($departments as $department)
                                            <option value="{{ $department->id }}"{{ $employee->department->id == $department->id ? ' selected' : '' }}>
                                                {{ $department->id }} - {{ $department->description }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                @if ($errors->has('department_id'))
                                    <span class="help is-danger">
                                        {{ $errors->first('department_id') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>


                    <div class="column is-6">
                        <div class="field">
                            <label for="staff_type_id" class="label">{{ trans('words.staff-type') }}</label>

                            <p class="control">
                                <div class="select is-fullwidth">
                                    <select name="staff_type_id">
                                        <option></option>
                                        @foreach($types as $type)
                                            <option value="{{ $type->id }}"{{ $employee->staff_type_id == $type->id ? ' selected' : '' }}>{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                @if ($errors->has('staff_type_id'))
                                    <span class="help is-danger">
                                        {{ $errors->first('staff_type_id') }}
                                    </span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="column is-6">
                        <div class="field">
                            <label for="email" class="label">{{ trans('auth.email') }}</label>

                            <p class="control">
                                <input id="email" type="email" class="input {{ $errors->has('email') ? ' is-danger' : '' }}"
                                       name="email"
                                       value="{{ $employee->email }}" >

                                @if ($errors->has('email'))
                                    <span class="help is-danger">
                                        {{ $errors->first('email') }}
                                    </span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="column is-6">
                        <div class="field">
                            <label for="phone" class="label">{{ trans('words.contact-number') }}</label>

                            <p class="control">
                                <input id="phone" type="text" class="input {{ $errors->has('phone') ? ' is-danger' : '' }}"
                                       name="phone"
                                       value="{{ $employee->phone }}" >

                                @if ($errors->has('phone'))
                                    <span class="help is-danger">
                                        {{ $errors->first('phone') }}
                                    </span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="column is-12">
                        <div class="field has-text-right">
                            <a class="button is-text" href="{{ route('employees.index') }}">{{ __('words.cancel') }}</a>
                            <button type="submit" class="button is-primary">{{ trans('words.save') }}</button>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>

@endsection
