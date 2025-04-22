@extends('layouts.app')

@section('titulo', 'Editar: ' . $cancion->nombre)

@section('content')
<style>
    textarea.form-control {
        font-family: 'Fira Code', monospace;
        white-space: pre-wrap;
        background-color: #1a1a1a;
        color: #F5F5F5;
    }

    .form-label {
        font-weight: bold;
    }
</style>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Editar: {{ $cancion->nombre }}</h1>
        <a href="{{ route('canciones.index') }}" class="btn btn-outline-light">
            <i class="bi bi-arrow-left"></i> Volver al listado
        </a>
    </div>

    <form id="form-cancion" action="{{ route('canciones.update', encrypt($cancion->id)) }}" method="POST"
        class="card bg-dark text-white p-4">
        @csrf
        @method('PUT')

        <div class="row">
            {{-- Columna izquierda --}}
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Nombre de la Canción</label>
                    <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $cancion->nombre) }}"
                        class="form-control @error('nombre') is-invalid @enderror" required>
                    @error('nombre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Introducción</label>
                    <textarea name="introduccion" rows="6" class="form-control @error('introduccion') is-invalid @enderror">{{ old('introduccion', $cancion->introduccion) }}</textarea>
                    @error('introduccion')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Letra y Acordes (1)</label>
                    <textarea name="letra1" rows="10" class="form-control @error('letra1') is-invalid @enderror">{{ old('letra1', $cancion->letra1) }}</textarea>
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
                            <option value="{{ $etiqueta->id }}" {{ $etiqueta->id == $cancion->etiqueta ? 'selected' : '' }}>
                                {{ $etiqueta->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('etiqueta')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Interludio</label>
                    <textarea name="interludio" rows="5" class="form-control @error('interludio') is-invalid @enderror">{{ old('interludio', $cancion->interludio) }}</textarea>
                    @error('interludio')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Letra y Acordes (2)</label>
                    <textarea name="letra2" rows="7" class="form-control @error('letra2') is-invalid @enderror">{{ old('letra2', $cancion->letra2) }}</textarea>
                    @error('letra2')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Final</label>
                    <textarea name="final" rows="5" class="form-control @error('final') is-invalid @enderror">{{ old('final', $cancion->final) }}</textarea>
                    @error('final')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-grid">
                    <label class="form-label opacity-0">Guardar</label>
                    <button type="button" id="btn-guardar-cancion" class="btn btn-warning">
                        <i class="bi bi-save"></i> Guardar Cambios
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Modal "Actualizando..." -->
<div class="modal fade" id="modalActualizando" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
    data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-white border-warning">
            <div class="modal-body text-center p-5">
                <div class="spinner-border text-warning mb-3" role="status"></div>
                <h5>Actualizando canción...</h5>
            </div>
        </div>
    </div>
</div>

<!-- Modal "Guardado exitosamente" -->
@if (session('success'))
<div class="modal fade" id="modalActualizado" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
    data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-white border-success">
            <div class="modal-header border-0">
                <h5 class="modal-title text-success">
                    <i class="bi bi-check-circle-fill me-2"></i> Canción actualizada correctamente
                </h5>
            </div>
            <div class="modal-body text-center">
                Redirigiendo al listado...
            </div>
        </div>
    </div>
</div>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const inputNombre = document.getElementById('nombre');
        const botonGuardar = document.getElementById('btn-guardar-cancion');
        const form = document.getElementById('form-cancion');

        botonGuardar.addEventListener('click', function () {
            const modal = new bootstrap.Modal(document.getElementById('modalActualizando'), {
                backdrop: 'static',
                keyboard: false
            });

            modal.show();

            setTimeout(() => {
                form.submit(); // CSRF token intacto
            }, 300);
        });

        @if (session('success'))
        const modalExito = new bootstrap.Modal(document.getElementById('modalActualizado'));
        modalExito.show();

        setTimeout(() => {
            window.location.href = "{{ route('canciones.index') }}";
        }, 1500);
        @endif
    });
</script>
@endsection
