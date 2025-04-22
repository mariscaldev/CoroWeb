@extends('layouts.app')

@section('titulo', 'Etiquetas')

@section('content')
    <style>
        .fila-etiqueta {
            cursor: pointer;
        }
    </style>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0">Administración de Etiquetas</h1>
            <a href="{{ route('etiquetas.create') }}" class="btn btn-outline-success">
                <i class="bi bi-plus-circle me-1"></i> Agregar Etiqueta
            </a>
        </div>

        @if ($etiquetas->isEmpty())
            <p class="text-muted">No hay etiquetas registradas.</p>
        @else
            <table id="datatable" class="table table-dark table-striped table-hover border-light nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($etiquetas as $etiqueta)
                        <tr
                            class="fila-etiqueta {{ session('nueva_etiqueta_id') == $etiqueta->id ? 'highlight-nueva' : '' }}">
                            <td style="width: 50px;">{{ $etiqueta->id }}</td>
                            <td>{{ $etiqueta->nombre }}</td>
                            <td class="text-center" style="width: 150px;">
                                <a href="{{ route('etiquetas.edit', encrypt($etiqueta->id)) }}"
                                    class="btn btn-sm btn-outline-warning me-2" title="Editar">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <button type="button" class="btn btn-sm btn-outline-danger btn-confirmar-eliminacion"
                                    data-id="{{ $etiqueta->id }}" title="Eliminar">
                                    <i class="bi bi-trash3"></i>
                                </button>

                                <form id="form-eliminar-{{ $etiqueta->id }}"
                                    action="{{ route('etiquetas.destroy', encrypt($etiqueta->id)) }}" method="POST"
                                    class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
    <!-- Modal de Confirmación -->
    <div class="modal fade" id="modalConfirmarEliminacion" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark text-white border-danger">
                <div class="modal-header border-0">
                    <h5 class="modal-title text-danger">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> Confirmar eliminación
                    </h5>
                </div>
                <div class="modal-body">
                    ¿Estás seguro que deseas eliminar esta etiqueta?
                    <input type="hidden" id="id-a-eliminar">
                </div>
                <div class="modal-footer border-0 d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Cancelar
                    </button>
                    <button type="button" class="btn btn-outline-danger" id="btn-confirmar-eliminar">
                        <i class="bi bi-trash3"></i> Eliminar
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal "Eliminando..." -->
    <div class="modal fade" id="modalEliminando" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark text-white border-danger">
                <div class="modal-body text-center p-5">
                    <div class="spinner-border text-danger mb-3" role="status"></div>
                    <h5>Eliminando etiqueta...</h5>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let idEliminar = null;

            const modal = new bootstrap.Modal(document.getElementById('modalConfirmarEliminacion'), {
                backdrop: 'static',
                keyboard: false
            });

            // Mostrar modal al hacer clic en eliminar
            document.querySelectorAll('.btn-confirmar-eliminacion').forEach(btn => {
                btn.addEventListener('click', function() {
                    idEliminar = this.getAttribute('data-id');
                    document.getElementById('id-a-eliminar').value = idEliminar;
                    modal.show();
                });
            });

            document.getElementById('btn-confirmar-eliminar').addEventListener('click', function() {
                if (idEliminar) {
                    const modalConfirmar = bootstrap.Modal.getInstance(document.getElementById(
                        'modalConfirmarEliminacion'));
                    modalConfirmar.hide(); // Cierra el modal de confirmación primero

                    const modalEliminando = new bootstrap.Modal(document.getElementById(
                        'modalEliminando'), {
                        backdrop: 'static',
                        keyboard: false
                    });

                    modalEliminando.show();

                    setTimeout(() => {
                        document.getElementById(`form-eliminar-${idEliminar}`).submit();
                    }, 300);
                }
            });

        });
    </script>

@endsection
