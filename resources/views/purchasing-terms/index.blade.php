@extends('layouts.app', [
	'title' => 'Purchasing Terms',
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
            <li class="is-active">
                <a href="{{ route('purchasing-terms.index') }}">
                    <span>Purchasing Terms</span>
                </a>
            </li>
        </ul>
    </nav>
@endsection

@section('content')

    <div class="columns">
        <div class="column">
            <p class="title">Purchasing Terms</p>
        </div>
    </div>

    <div class="columns">
        <div class="column">
            <div class="card">
                <table class="table is-fullwidth">
                	<tbody>
                    @foreach($purchasingTypes as $type)
                        <tr>
                            <td><strong>{{ $type->name }}</strong></td>
                            <td></td>
                        </tr>
                        @foreach($type->terms as $term)
                            <tr>
                                <td><span style="padding-left:1rem">{{ $term->value }}</span></td>
                                <td>
                                    <toggle-default-purchase-term :term-id.number="{{ $term->id }}"
                                                          :enabled-prop.number="{{ $term->enabled ? 'true' : 'false' }}"
                                    >
                                        Enabled by default
                                    </toggle-default-purchase-term>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                	</tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
