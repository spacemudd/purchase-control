@extends('layouts.public')

@section('content')

    <section>
        <div class="hero-body">
            <div class="container">
                <div class="columns">
                    <div class="column is-4 is-offset-4">
                        <p class="title has-text-centered has-text-weight-light is-1" style="margin-bottom:100px;">{{ __('words.welcome') }}</p>
                        <form action="{{ route('invite.process-accept') }}" method="post">
                            {{ csrf_field() }}
                            <input type="hidden" name="token" value="{{ $invite->token }}">
                            <div class="field">
                                <label for="email" class="label">{{ __('auth.email') }} <span class="has-text-danger">*</span></label>

                                <p class="control">
                                    <p>{{ $invite->email }}</p>
                                </p>
                                @if ($errors->has('email'))
                                    <span class="help is-danger">
                                        {{ $errors->first('email') }}
                                    </span>
                                @endif
                            </div>


                            <div class="field">
                                <label for="password" class="label">{{ trans('auth.password') }} <span class="has-text-danger">*</span></label>

                                <p class="control">
                                    <input id="password" type="password"
                                           class="input {{ $errors->has('password') ? ' is-danger' : '' }}" name="password"
                                           value="{{ old('password') }}" required>

                                    @if ($errors->has('password'))
                                        <span class="help is-danger">
                                                {{ $errors->first('password') }}
                                            </span>
                                    @endif
                                </p>
                            </div>

                            <div class="field">
                                <label for="password-confirmation" class="label">{{ trans('auth.confirm-password') }} <span class="has-text-danger">*</span></label>

                                <p class="control">
                                    <input id="password-confirmation" type="password"
                                           class="input"
                                           name="password_confirmation"
                                           required>
                                </p>
                            </div>

                            <p class="control has-text-right">
                                <button type="submit" class="button is-primary">Continue</button>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>


@endsection
