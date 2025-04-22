@extends('layouts.app')

@section('titulo', 'Agregar Canción')

@section('content')
<style>
    textarea.form-control {
        font-family: 'Fira Code', monospace;
        white-space: pre-wrap;
        background-color: #1a1a1a;
        color: #F5F5F5;
    }
</style>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Agregar Canción</h1>
        <a href="{{ route('canciones.index') }}" class="btn btn-outline-light">
            <i class="bi bi-arrow-left"></i> Volver al listado
        </a>
    </div>

    <form action="{{ route('canciones.store') }}" method="POST" class="card bg-dark text-white p-4">
        @csrf

        <div class="row">
            {{-- Columna izquierda --}}
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label fw-bold">ID Asignado</label>
                    <input type="text" class="form-control bg-secondary text-white" value="{{ $nextId }}" readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" required>
                    @error('nombre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Introducción</label>
                    <textarea name="introduccion" rows="6" class="form-control @error('introduccion') is-invalid @enderror"></textarea>
                    @error('introduccion')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Letra y Acordes (1)</label>
                    <textarea name="letra1" rows="10" class="form-control @error('letra1') is-invalid @enderror"></textarea>
                    @error('letra1')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Columna derecha --}}
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Etiqueta</label>
                    <select name="etiqueta" class="form-select @error('etiqueta') is-invalid @enderror">
                        <option value="">Sin etiqueta</option>
                        @foreach($etiquetas as $etiqueta)
                            <option value="{{ $etiqueta->id }}">{{ $etiqueta->nombre }}</option>
                        @endforeach
                    </select>
                    @error('etiqueta')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Interludio</label>
                    <textarea name="interludio" rows="5" class="form-control @error('interludio') is-invalid @enderror"></textarea>
                    @error('interludio')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Letra y Acordes (2)</label>
                    <textarea name="letra2" rows="7" class="form-control @error('letra2') is-invalid @enderror"></textarea>
                    @error('letra2')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Final</label>
                    <textarea name="final" rows="5" class="form-control @error('final') is-invalid @enderror"></textarea>
                    @error('final')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-md-12 mt-4 text-center">
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-plus-circle"></i> Crear Canción
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
