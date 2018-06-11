@extends('layouts.app')

@section('content')

<div class="row justify-content-center">
    <div class="col-12 col-sm-6">

        <h1>LDAP-Login</h1>

        <form method="POST" action="{{ route('login_create') }}">
            {{ csrf_field() }}

            @include('common/_input_form_group', [
                'scope' => 'auth', 'name' => 'login', 
                'options' => ['placeholder' => 'Benutzername'],
                'input_group' => ['append_class' => 'fa fa-user']
            ])

            @include('common/_input_form_group', [
                'scope' => 'auth', 'name' => 'password', 'type' => 'password',
                'options' => ['placeholder' => 'Passwort'],
                'input_group' => ['append_class' => 'fa fa-key']
            ])

            <div class="form-group">
                <div class="form-check">
                    <input class="form-check-input{{ $errors->has('auth.remember') ? ' is-invalid' : '' }}" name="remember" id="remember" type="checkbox" {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label" for="remember">
                        Remember Me
                    </label>
                </div>
                @include('common/_input_error', ['validation_key' => 'remember'])
            </div>

            <button type="submit" class="btn btn-primary">Login</button>
        </form>

    </div>
</div>

@endsection
