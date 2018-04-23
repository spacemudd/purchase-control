@extends('layouts.app', ['title' => trans('words.edit') . ' - ' . $manufacturer->id . ' - ' . $manufacturer->name . ' ' . trans('words.manufacturers')])

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
				<a href="{{ route('manufacturers.index') }}">
					<span class="icon is-small"><i class="fa fa-cubes"></i></span>
					<span>{{ trans('words.manufacturers') }}</span>
				</a>
			</li>
            <li>
                <a href="{{ route('manufacturers.show', ['id' => $manufacturer->id]) }}">
                    {{ $manufacturer->id }} - {{ $manufacturer->name }}
                </a>
            </li>
			<li class="is-active">
				<a href="#">{{ __('words.edit') }}</a>
			</li>
		</ul>
	</nav>
@endsection

@section('content')

    <div class="columns">
        <div class="column is-8 is-offset-2">
            <form method="post" action="{{ route('manufacturers.update', ['id' => $manufacturer->id]) }}">
                {{ csrf_field() }}
                <input type="hidden" name="_method" value="put" class="input">

                <div class="columns is-multiline">

                    <div class="column is-6">
                        <div class="field">
                            <label for="name" class="label">{{ trans('words.name') }}</label>

                            <p class="control">
                                <input id="name" type="text" class="input {{ $errors->has('name') ? ' is-danger' : '' }}"
                                       name="name"
                                       tabindex="1"
                                       autofocus
                                       value="{{ $manufacturer->name }}" required>

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
                            <label for="website" class="label">{{ trans('words.website') }}</label>

                            <p class="control">
                                <input id="website" type="text"
                                       class="input {{ $errors->has('website') ? ' is-danger' : '' }}"
                                       name="website"
                                       tabindex="2"
                                       value="{{ $manufacturer->website }}">

                                @if ($errors->has('website'))
                                    <span class="help is-danger">
                                {{ $errors->first('website') }}
                            </span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="column is-6">
                        <div class="field">
                            <label for="support_url" class="label">{{ trans('words.support-url') }}</label>

                            <p class="control">
                                <input id="support_url" type="text"
                                       class="input {{ $errors->has('support_url') ? ' is-danger' : '' }}"
                                       name="support_url"
                                       tabindex="3"
                                       value="{{ $manufacturer->support_url }}">

                                @if ($errors->has('support_url'))
                                    <span class="help is-danger">
                                {{ $errors->first('support_url') }}
                            </span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="column is-6">
                        <div class="field">
                            <label for="support_phone" class="label">{{ trans('words.support-phone') }}</label>

                            <p class="control">
                                <input id="support_phone" type="text"
                                       class="input {{ $errors->has('support_phone') ? ' is-danger' : '' }}"
                                       name="support_phone"
                                       tabindex="4"
                                       value="{{ $manufacturer->support_phone }}">

                                @if ($errors->has('support_phone'))
                                    <span class="help is-danger">
                                {{ $errors->first('support_phone') }}
                            </span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="column is-6">
                        <div class="field">
                            <label for="support_email" class="label">{{ trans('words.support-email') }}</label>

                            <p class="control">
                                <input id="support_email" type="email"
                                       class="input {{ $errors->has('support_email') ? ' is-danger' : '' }}"
                                       name="support_email"
                                       tabindex="5"
                                       value="{{ $manufacturer->support_email }}">

                                @if ($errors->has('support_email'))
                                    <span class="help is-danger">
                                {{ $errors->first('support_email') }}
                            </span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="column is-6"></div>

                    <div class="column is-6">
                        {{--<form method="post" action="{{ route('manufacturers.destroy', ['id' => $manufacturer->id]) }}">--}}
                            {{--{{ csrf_field() }}--}}
                            {{--<input type="hidden" name="_method" value="delete" class="input">--}}
                            {{--<button type="submit" class="button is-danger" tabindex="8">--}}
                                {{--{{ trans('words.delete') }}--}}
                            {{--</button>--}}
                        {{--</form>--}}
                    </div>
                    <div class="column is-6 has-text-right">
                        <a class="button is-text" href="{{ route('manufacturers.show', ['id' => $manufacturer->id]) }}" tabindex="7">{{ __('words.cancel') }}</a>
                        <button type="submit" class="button is-success" tabindex="6">{{ trans('words.save') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
