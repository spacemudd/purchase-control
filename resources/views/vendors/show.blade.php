@extends('layouts.app', ['title' => $vendor->id . ' - ' . $vendor->name . ' - ' . trans('words.vendor')])


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
                <a href="{{ route('vendors.index') }}">
                    <span class="icon is-small"><i class="fa fa-truck"></i></span>
                    <span>{{ trans('words.vendors') }}</span>
                </a>
            </li>
            <li class="is-active">
                <a href="{{ route('vendors.show', ['id' => $vendor->id]) }}">
                    {{ $vendor->id }} - {{ $vendor->name }}
                </a>
            </li>
        </ul>
    </nav>
@endsection

@section('content')

    <div class="box">
        <div class="columns">
            <div class="column is-6">
                <h1 class="title">{{ $vendor->id }}</h1>
                <p class="subtitle is-6">{{ __('words.vendor-code') }}</p>
            </div>
            <div class="column is-6 has-text-right">
                <a href="{{ route('vendors.edit', ['id' => $vendor->id]) }}" class="button is-small is-warning">
                    <span class="icon is-small"><i class="fa fa-pencil"></i></span>
                    <span>{{ __('words.edit') }}</span>
                </a>
            </div>
        </div>

        <hr>

        <div class="columns">
            <div class="column is-6">
                <table class="table is-fullwidth is-size-7">
                    <tbody>
                        <tr>
                            <td><strong>{{ __('words.code') }}</strong></td>
                            <td>{{ $vendor->id }}</td>
                        </tr>
                        <tr>
                            <td><strong>{{ __('words.name') }}</strong></td>
                            <td>{{ $vendor->name }}</td>
                        </tr>

                        <tr>
                            <td><strong>{{ __('words.website') }}</strong></td>
                            <td>{{ $vendor->website }}</td>
                        </tr>
                        <tr>
                            <td><strong>{{ __('auth.email') }}</strong></td>
                            <td>{{ $vendor->email }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="column is-6">
                <table class="table is-fullwidth is-size-7">
                    <tbody>
                        <tr>
                            <td><strong>{{ __('words.established-at') }}</strong></td>
                            <td>{{ $vendor->established_at }}</td>
                        </tr>
                        <tr>
                            <td><strong>{{ __('words.telephone-number') }}</strong></td>
                            <td>{{ $vendor->telephone_number }}</td>
                        </tr>
                        <tr>
                            <td><strong>{{ __('words.fax-number') }}</strong></td>
                            <td>{{ $vendor->fax_number }}</td>
                        </tr>
                        <tr>
                            <td><strong>{{ __('words.address') }}</strong></td>
                            <td>{{ $vendor->address }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="columns">
        <div class="column is-4">
            <vendor-manufacturers-card :vendor-id.number="{{ $vendor->id }}"></vendor-manufacturers-card>
        </div>

        <div class="column is-4">
            <div class="card events-card">
                <header class="card-header">
                    <p class="card-header-title">{{ __('words.representatives') }}</p>
                    <a href="{{ route('vendor-representatives.create', ['vendor_id' => $vendor->id]) }}" class="card-header-icon">
                        <span class="icon">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                        </span>
                    </a>
                </header>
                <div class="card-table">
                    <div class="content">
                        @if($vendor->reps->count())
                            <table class="table is-fullwidth is-striped">
                            <tbody>
                                @foreach($vendor->reps as $rep)
                                    <tr>
                                        <td>
                                            {{ $rep->name }}<br/>
                                            {{ $rep->number }}</br>
                                            {{ $rep->email }}<br/>
                                            {{ $rep->position }}</td>
                                        </td>
                                        <td class="has-text-right">

                                            <form method="post" action="{{ route('vendor-representatives.destroy', ['vendor_id' => $vendor->id, 'id' => $rep->id]) }}">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="_method" value="delete">
                                                <button type="submit" class="button has-icon is-small">
                                                    <span class="icon is-small"><i class="fa fa-trash"></i></span>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                            <p class="has-text-centered" style="padding:30px;"><i>No representatives</i></p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="column is-4">
            <div class="card">
                <header class="card-header">
                    <p class="card-header-title">{{ __('words.bank-information') }}</p>

                    @if($vendor->bank)
                        <a href="{{ route('vendor-bank.edit', ['vendor_id' => $vendor->id, 'id' => $vendor->bank->id]) }}" class="card-header-icon">
                            <span class="icon">
                                <i class="fa fa-pencil" aria-hidden="true"></i>
                            </span>
                        </a>
                    @else
                        <a href="{{ route('vendor-bank.create', ['vendor_id' => $vendor->id]) }}" class="card-header-icon">
                            <span class="icon">
                                <i class="fa fa-plus" aria-hidden="true"></i>
                            </span>
                        </a>
                    @endif
                </header>
                <div class="card-content">
                    @if($vendor->bank)
                        <table class="table is-fullwidth is-size-7 is-striped">
                            <colgroup>
                                <col width="50%">
                                <col width="50%">
                            </colgroup>
                            <tbody>
                                <tr>
                                    <td>{{ __('words.bank-name') }}</td>
                                    <td>{{ $vendor->bank->name }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('words.address') }}</td>
                                    <td>{{ $vendor->bank->address }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('words.beneficiary-name') }}</td>
                                    <td>{{ $vendor->bank->beneficiary_name }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('words.account-number') }}</td>
                                    <td>{{ $vendor->bank->account_number }}</td>
                                </tr>
                                <tr>
                                    <td>IBAN</td>
                                    <td>{{ $vendor->bank->iban_code }}</td>
                                </tr>
                                <tr>
                                    <td>SWIFT</td>
                                    <td>{{ $vendor->bank->swift_code }}</td>
                                </tr>
                                <tr>
                                    <td>SORT</td>
                                    <td>{{ $vendor->bank->sort_code }}</td>
                                </tr>
                                <tr>
                                    <td>Currency</td>
                                    <td>{{ $vendor->bank->currency }}</td>
                                </tr>
                            </tbody>
                        </table>
                    @else
                        <p class="has-text-centered" style="font-size:14px;"><i>No bank information</i></p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
