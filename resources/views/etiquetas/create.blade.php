@extends('layouts.app')

@section('titulo', 'Agregar Etiqueta')

@section('content')
    <style>
        .highlight-nueva {
            animation: resaltar 3s ease-in-out;
            background-color: #154e28 !important;
        }

        @keyframes resaltar {
            0% {
                background-color: #28a745;
            }

            50% {
                background-color: #218838;
            }

            100% {
                background-color: transparent;
            }
        }
    </style>

    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0">Nueva Etiqueta</h1>
            <a href="{{ route('etiquetas.index') }}" class="btn btn-outline-light">
                <i class="bi bi-arrow-left"></i> Volver al listado
            </a>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Ups!</strong> Hubo un problema con tu entrada.<br><br>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="form-etiqueta" action="{{ route('etiquetas.store') }}" method="POST"
            class="card bg-dark text-white p-4">
            @csrf
            <div class="row align-items-end">
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-bold">ID Asignado</label>
                    <input type="text" class="form-control bg-secondary text-white" value="{{ $nextId }}" readonly>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="nombre" class="form-label fw-bold">Nombre de la Etiqueta</label>
                    <input type="text" name="nombre" id="nombre"
                        class="form-control @error('nombre') is-invalid @enderror" required>
                    @error('nombre')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3 mb-3 d-grid">
                    <label class="form-label opacity-0">Crear</label>
                    <button type="button" class="btn btn-success" id="btn-crear-etiqueta" disabled>
                        <i class="bi bi-plus-circle"></i> Crear Etiqueta
                    </button>
                </div>
            </div>
        </form>

    </div>

    <!-- Modal "Registrando..." -->
    <div class="modal fade" id="modalRegistrando" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark text-white border-warning">
                <div class="modal-body text-center p-5">
                    <div class="spinner-border text-warning mb-3" role="status"></div>
                    <h5>Registrando etiqueta...</h5>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal "Guardado Exitosamente" -->
    @if (session('success'))
        <div class="modal fade" id="modalGuardado" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
            data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content bg-dark text-white border-success">
                    <div class="modal-header border-0">
                        <h5 class="modal-title text-success">
                            <i class="bi bi-check-circle-fill me-2"></i> Guardado exitosamente
                        </h5>
                    </div>
                    <div class="modal-body">
                        La etiqueta se ha creado correctamente.
                    </div>
                    <div class="modal-footer border-0 d-flex justify-content-between">
                        <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">
                            <i class="bi bi-plus-circle"></i> Registrar otra etiqueta
                        </button>
                        <button type="button" class="btn btn-outline-success"
                            onclick="window.location.href='{{ route('etiquetas.index') }}'">
                            <i class="bi bi-arrow-right-circle"></i> Continuar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Habilita el botón solo si hay texto en el input
            const inputNombre = document.getElementById('nombre');
            const botonCrear = document.getElementById('btn-crear-etiqueta');

            inputNombre.addEventListener('input', function() {
                botonCrear.disabled = this.value.trim() === '';
            });

            const btn = document.getElementById('btn-crear-etiqueta');
            const form = document.getElementById('form-etiqueta');

            btn.addEventListener('click', function() {
                const modal = new bootstrap.Modal(document.getElementById('modalRegistrando'), {
                    backdrop: 'static',
                    keyboard: false
                });

                modal.show();

                setTimeout(() => {
                    form.submit();
                }, 300);
            });

            @if (session('success'))
                const modalExito = new bootstrap.Modal(document.getElementById('modalGuardado'));
                modalExito.show();
            @endif
        });
    </script>
@endsection
