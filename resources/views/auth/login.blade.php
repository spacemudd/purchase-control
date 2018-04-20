@extends('layouts.public', ['title' => "Login"])

@section('content')
    <section class="hero is-fullheight is-white is-bold">
        <div class="hero-body">
            <div class="container">
                <div class="columns is-vcentered">
                    <div class="column is-4-tablet is-offset-4-tablet is-4-mobile is-offset-4-mobile">
                        <div class="box">
                            <div class="block has-text-centered">
                                <a href="{{ url('/') }}" target="_blank" rel="noopener">
                                    <img src="{{ asset('img/brand/brand_logo.svg') }}" alt="Clarimount" />
                                </a>
                            </div>
                            <form class="form-horizontal" role="form" method="POST" action="{{ route('login') }}">
                                {{ csrf_field() }}
                                <label class="label">{{ __('auth.username') }}</label>
                                <p class="control">
                                    <input dir="ltr" id="username" class="input {{ $errors->has('username') ? ' is-danger' : '' }}" type="text" name="username" value="{{ old('username') }}" required autofocus>
                                    <!-- TODO: Ability to login as a user -->
                                    @if ($errors->has('username'))
                                        <span class="help is-danger">
										{{ $errors->first('username') }}
									</span>
                                    @endif
                                </p>
                                <hr>
                                <label class="label">{{ __('auth.password') }}</label>
                                <p class="control">
                                    <input dir="ltr" id="password" class="input {{ $errors->has('password') ? ' has-error' : '' }}" type="password" name="password" {{-- placeholder="●●●●●●●" --}} required>

                                    @if ($errors->has('password'))
                                        <span class="help is-danger">
										<strong>{{ $errors->first('password') }}</strong>
									</span>
                                    @endif
                                </p>
                                <hr>
                                <div class="field">
                                    <p class="control">
                                        <label class="label checkbox">
                                            <input id="remember" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                            {{ __('auth.remember-me') }}
                                        </label>
                                    </p>
                                </div>
                                <p class="control">
                                    <button class="button is-primary">{{ __('auth.login') }}</button>
                                </p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
@endsection
