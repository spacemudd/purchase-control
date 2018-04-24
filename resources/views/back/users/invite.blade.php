@extends('layouts.app')

@section('content')

    <div class="columns">
        <div class="column is-4 is-offset-4">
            <form action="{{ route('invite') }}" method="post">
                {{ csrf_field() }}
                <div class="field">
                    <label for="username" class="label">Username <span class="has-text-danger">*</span></label>

                    <p class="control">
                        <input id="username" type="text" class="input {{ $errors->has('username') ? ' is-danger' : '' }}"
                               name="username"
                               value="{{ old('username') }}" required>

                    @if ($errors->has('username'))
                        <span class="help is-danger">
                	            {{ $errors->first('username') }}
                	        </span>
                        @endif
                    </p>
                </div>

                <div class="field">
                    <label for="name" class="label">Name <span class="has-text-danger">*</span></label>

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
                    <label for="email" class="label">Email <span class="has-text-danger">*</span></label>

                    <p class="control">
                        <input id="email" type="email" class="input {{ $errors->has('email') ? ' is-danger' : '' }}"
                               name="email"
                               value="{{ old('email') }}" required>

                    <p class="help">Email will be sent to the user with an invite link.</p>
                    @if ($errors->has('email'))
                        <span class="help is-danger">
                	            {{ $errors->first('email') }}
                	        </span>
                        @endif
                        </p>
                </div>

                <div class="field">
                    <label for="phone" class="label">Phone</label>

                    <p class="control">
                        <input id="phone" type="text" placeholder="966" class="input {{ $errors->has('phone') ? ' is-danger' : '' }}"
                               name="phone"
                               value="{{ old('phone') }}">

                    @if ($errors->has('phone'))
                        <span class="help is-danger">
                	            {{ $errors->first('phone') }}
                	        </span>
                        @endif
                        </p>
                </div>

                <div class="field">
                    <label for="role_name" class="label">Role</label>

                    <p class="control">
                    <div class="select is-fullwidth">
                        <select name="role_name" class="select">
                            <option value=""></option>
                            @foreach(\Spatie\Permission\Models\Role::get() as $role)
                                <option value="{{ $role->name }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    @if ($errors->has("role_name"))
                        <span class="help is-danger">
                	            {{ $errors->first("role_name") }}
                	        </span>
                        @endif
                        </p>
                </div>

                <p class="control has-text-right" style="margin-top:30px;">
                    <a href="{{ route('dashboard.index') }}" class="button is-text">{{ __('words.cancel') }}</a>
                    <button class="button is-primary">{{ __('words.save') }}</button>
                </p>
            </form>
        </div>
    </div>

@endsection
