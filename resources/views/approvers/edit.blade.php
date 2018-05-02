@extends('layouts.app', ['title' => $approver->display_name . ' - ' . 'Approvers'])

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
                <a href="{{ route('approvers.index') }}">
                    <span class="icon is-small"><i class="fa fa-address-book"></i></span>
                    <span>{{ trans('words.approvers') }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('approvers.show', ['id' => $approver->id]) }}">{{ $approver->display_name }}</a>
            </li>
        </ul>
    </nav>
@endsection

@section('content')

    <div class="columns">
        <div class="column is-4 is-offset-4">
            <p class="title">{{ $approver->display_name }}</p>
            <form action="{{ route('approvers.update', ['id' => $approver->id]) }}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="_method" value="put">

                <div class="field">
                    <label for="financial_auth" class="label">Financial Auth</label>

                    <p class="control">
                        <input id="financial_auth"
                               type="number"
                               min="0"
                               step="0.01"
                               class="input {{ $errors->has('financial_auth') ? ' is-danger' : '' }}"
                               name="financial_auth"
                               value="{{ $approver->financial_authority_human }}"
                               required>

                        @if ($errors->has('financial_auth'))
                            <span class="help is-danger">
                	            {{ $errors->first('financial_auth') }}
                	        </span>
                        @endif
                    </p>
                </div>

                <div class="field">
                    <label for="designation" class="label">Designation</label>

                    <p class="control">
                        <input id="designation" type="text"
                               class="input {{ $errors->has('designation') ? ' is-danger' : '' }}" name="designation"
                               value="{{ $approver->designation }}" required>

                        @if ($errors->has('designation'))
                            <span class="help is-danger">
                	            {{ $errors->first('designation') }}
                	        </span>
                        @endif
                    </p>
                </div>

                <div class="field has-text-right">
                    <a href="{{ route('approvers.show', ['id' => $approver->id]) }}" class="button is-text">
                        {{ __('words.cancel') }}
                    </a>
                    <button type="submit" class="button is-primary">Save</button>
                </div>
            </form>
        </div>
    </div>

@endsection
