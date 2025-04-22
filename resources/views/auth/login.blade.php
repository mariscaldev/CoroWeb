@extends('layouts.guest')
@section('titulo', 'Inicio de Sesión')
@section('content')
<style>
    body {
        background-color: #111010;
        color: #F5F5F5;
        font-family: 'Inter', sans-serif;
    }

    .login-container {
        display: flex;
        min-height: 100vh;
    }

    .left-panel {
        flex: 1;
        background: url('/background.jpg') no-repeat center center;
        background-size: cover;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .left-panel h1 {
        font-size: 48px;
        font-weight: bold;
        color: #F5F5F5;
        text-shadow: 2px 2px #9AFCE6;
    }

    .right-panel {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px;
        background-color: #111010;
    }

    .login-form {
        width: 100%;
        max-width: 400px;
    }

    .login-form .form-control {
        background-color: #1b1b1b;
        color: #F5F5F5;
        border: 1px solid #444;
    }

    .login-form .form-control:focus {
        border-color: #9AFCE6;
        box-shadow: 0 0 5px #9AFCE6;
    }

    .login-form .btn-login {
        background-color: #9AFCE6;
        border: none;
        color: #111010;
        font-weight: bold;
        width: 100%;
    }

    .login-form a {
        color: #9AFCE6;
    }

    .login-form .form-check-input:checked {
        background-color: #9AFCE6;
        border-color: #9AFCE6;
        color: #111010
    }
</style>

<div class="login-container">
    <div class="left-panel"></div>
    <div class="right-panel">
        <div class="login-form">
            <h2 class="text-center mb-4">@yield('titulo')</h2>
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label for="email">Email</label>
                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>
                </div>

                <div class="mb-3">
                    <label for="password">Contraseña</label>
                    <input id="password" type="password" class="form-control" name="password" required>
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" name="remember" id="remember">
                    <label class="form-check-label" for="remember">Recordar</label>
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-login">Iniciar Sesión</button>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('register') }}">Registrarse</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
