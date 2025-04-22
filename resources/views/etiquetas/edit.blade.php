@extends('layouts.app')

@section('titulo', 'Editar Etiqueta')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0">Editar Etiqueta</h1>
            <a href="{{ route('etiquetas.index') }}" class="btn btn-outline-light">
                <i class="bi bi-arrow-left"></i> Volver al listado
            </a>
        </div>

        <form id="form-etiqueta" action="{{ route('etiquetas.update', $etiqueta->id) }}" method="POST"
            class="card bg-dark text-white p-4">
            @csrf
            @method('PUT')

            <div class="row align-items-end">
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-bold">ID</label>
                    <input type="text" class="form-control bg-secondary text-white" value="{{ $etiqueta->id }}" readonly>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="nombre" class="form-label fw-bold">Nombre de la Etiqueta</label>
                    <input type="text" name="nombre" id="nombre" value="{{ $etiqueta->nombre }}"
                        class="form-control @error('nombre') is-invalid @enderror" required>
                    @error('nombre')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3 mb-3 d-grid">
                    <label class="form-label opacity-0">Guardar</label>
                    <button type="button" class="btn btn-warning" id="btn-guardar-etiqueta" disabled>
                        <i class="bi bi-save"></i> Guardar Cambios
                    </button>
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
                    <h5>Actualizando etiqueta...</h5>
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
                            <i class="bi bi-check-circle-fill me-2"></i> Guardado exitosamente
                        </h5>
                    </div>
                    <div class="modal-body">
                        La etiqueta se ha actualizado correctamente.
                    </div>
                    <div class="modal-footer border-0 d-flex justify-content-between">
                        <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">
                            <i class="bi bi-pencil-square"></i> Seguir editando
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
            const inputNombre = document.getElementById('nombre');
            const botonGuardar = document.getElementById('btn-guardar-etiqueta');
            const valorOriginal = inputNombre.value.trim();
            const formEtiqueta = document.getElementById('form-etiqueta');

            inputNombre.addEventListener('input', function() {
                const valor = this.value.trim();
                botonGuardar.disabled = (valor === '' || valor === valorOriginal);
            });

            botonGuardar.addEventListener('click', function() {
                const modal = new bootstrap.Modal(document.getElementById('modalActualizando'), {
                    backdrop: 'static',
                    keyboard: false
                });

                modal.show();

                setTimeout(() => {
                    formEtiqueta.submit();
                }, 300);
            });

            @if (session('success'))
                const modalExito = new bootstrap.Modal(document.getElementById('modalActualizado'));
                modalExito.show();
            @endif
        });
    </script>
@endsection
