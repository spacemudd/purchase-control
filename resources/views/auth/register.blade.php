@extends('layouts.public', ['title' => "Register"])

@section('content')
    <section class="hero is-fullheight is-white is-bold">
        <div class="hero-body">
            <div class="container">
                <div class="columns is-vcentered">
                    <div class="column is-6 is-offset-3">
                        <div class="box">
                            <a href="{{ url('/') }}" target="_blank" rel="noopener">
                                <img src="{{ asset('img/brand/anb_logo.svg') }}" alt="Clarimount" />
                            </a>
                            <form class="form-horizontal" role="form" method="POST" action="{{ route('register') }}">
                                {{ csrf_field() }}
                                <label class="label">Username</label>
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
                                    <label for="first_name" class="label">First Name</label>

                                    <p class="control">
                                        <input id="first_name" type="text"
                                               class="input {{ $errors->has('first_name') ? ' is-danger' : '' }}"
                                               name="first_name" value="{{ old('first_name') }}" required>

                                        @if ($errors->has('first_name'))
                                            <span class="help is-danger">
                                                {{ $errors->first('first_name') }}
                                            </span>
                                        @endif
                                    </p>
                                <hr>
                                <label for="last_name" class="label">Last Name</label>

                                <p class="control">
                                    <input id="last_name" type="text"
                                           class="input {{ $errors->has('last_name') ? ' is-danger' : '' }}"
                                           name="last_name" value="{{ old('last_name') }}" required>

                                    @if ($errors->has('last_name'))
                                        <span class="help is-danger">
                                            {{ $errors->first('last_name') }}
                                        </span>
                                    @endif
                                </p>
                                <hr>
                                    <label for="last_name" class="label">Email</label>

                                    <p class="control">
                                        <input id="email" type="email"
                                               class="input {{ $errors->has('email') ? ' is-danger' : '' }}"
                                               name="email" value="{{ old('email') }}" required>

                                        @if ($errors->has('email'))
                                            <span class="help is-danger">
                                                {{ $errors->first('email') }}
                                            </span>
                                        @endif
                                    </p>
                                <hr>
                                <label class="label">Password</label>
                                <p class="control">
                                    <input dir="ltr" id="password" class="input {{ $errors->has('password') ? ' has-error' : '' }}" type="password" name="password" {{-- placeholder="●●●●●●●" --}} required>

                                    @if ($errors->has('password'))
                                        <span class="help is-danger">
										<strong>{{ $errors->first('password') }}</strong>
									</span>
                                    @endif
                                </p>
                                <hr>
                                <label class="label">Password Confirmation</label>
                                <p class="control">
                                    <input dir="ltr" id="password_confirmation" class="input" type="password" name="password_confirmation" required>

                                    @if ($errors->has('password_confirmation'))
                                        <span class="help is-danger">
										<strong>{{ $errors->first('password_confirmation') }}</strong>
									</span>
                                    @endif
                                </p>
                                <hr>
                                <p class="control">
                                    <button class="button is-primary">Register</button>
                                </p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
@endsection

