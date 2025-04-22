@extends('layouts.guest')

@section('titulo', 'Registro de Usuario')

@section('content')
<style>
    body {
        background-color: #111010;
        color: #F5F5F5;
        font-family: 'Inter', sans-serif;
    }

    .register-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 30px;
    }

    .register-form {
        background-color: #1b1b1b;
        padding: 40px;
        border-radius: 10px;
        width: 100%;
        max-width: 500px;
        box-shadow: 0 0 15px rgba(154, 252, 230, 0.2);
    }

    .register-form h2 {
        margin-bottom: 30px;
        text-align: center;
        font-weight: bold;
        color: #9AFCE6;
    }

    .form-control {
        background-color: #2a2a2a;
        color: #F5F5F5;
        border: 1px solid #444;
    }

    .form-control:focus {
        border-color: #9AFCE6;
        box-shadow: 0 0 5px #9AFCE6;
    }

    .btn-register {
        background-color: #9AFCE6;
        border: none;
        color: #111010;
        font-weight: bold;
        width: 100%;
        padding: 10px;
    }

    .invalid-feedback {
        color: #ff6b6b;
    }

    label {
        font-weight: bold;
    }

    .btn-outline-light:hover {
        background-color: #9AFCE6;
        color: #fff !important;
        border-color: #9AFCE6;
        transition: 0.3s ease;
    }
</style>

<div class="register-container">
    <div class="register-form">
        <h2>Registro de Usuario</h2>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-3">
                <label for="name">Nombre</label>
                <input id="name" type="text"
                       class="form-control @error('name') is-invalid @enderror"
                       name="name" value="{{ old('name') }}" required autofocus>

                @error('name')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="email">Correo Electrónico</label>
                <input id="email" type="email"
                       class="form-control @error('email') is-invalid @enderror"
                       name="email" value="{{ old('email') }}" required>

                @error('email')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password">Contraseña</label>
                <input id="password" type="password"
                       class="form-control @error('password') is-invalid @enderror"
                       name="password" required>

                @error('password')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password-confirm">Confirmar Contraseña</label>
                <input id="password-confirm" type="password"
                       class="form-control" name="password_confirmation" required>
            </div>

            <div class="mb-3">
                <button type="submit" class="btn btn-register">Registrarse</button>
            </div>

            <div class="text-center mt-3">
                <a href="{{ route('login') }}" class="btn btn-outline-light" style="border-color: #9AFCE6; color: #9AFCE6;">
                    ¿Ya tienes cuenta? Inicia sesión
                </a>
            </div>
            
        </form>
    </div>
</div>
@endsection
