@extends('layouts.app')

@section('titulo', 'Lista Semanal')

@section('content')
    <style>
        .fila-lista {
            cursor: pointer;
        }
    </style>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0">Lista Semanal</h1>
            <a href="{{ route('listas.create') }}" class="btn btn-outline-success">
                <i class="bi bi-plus-circle me-1"></i> Agregar Lista
            </a>
        </div>

        @if ($listas->isEmpty())
            <p class="text-muted">No hay listas registradas.</p>
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
                    @foreach ($listas as $lista)
                        <tr class="fila-lista {{ session('nueva_lista_id') == $lista->id ? 'highlight-nueva' : '' }}"
                            data-id="{{ encrypt($lista->id) }}">
                            <td style="width: 50px;">{{ $lista->id }}</td>
                            <td>{{ $lista->nombre }}</td>
                            <td class="text-center" style="width: 150px;">
                                <button type="button" class="btn btn-sm btn-outline-info me-2 toggle-estado"
                                    data-id="{{ encrypt($lista->id) }}"
                                    title="{{ $lista->estado ? 'Ocultar' : 'Mostrar' }}">
                                    <i class="bi {{ $lista->estado ? 'bi-eye-fill' : 'bi-eye-slash-fill' }}"></i>
                                </button>

                                <a href="{{ route('listas.edit', encrypt($lista->id)) }}"
                                    class="btn btn-sm btn-outline-warning me-2" title="Editar">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <button type="button" class="btn btn-sm btn-outline-danger btn-confirmar-eliminacion"
                                    data-id="{{ $lista->id }}" title="Eliminar">
                                    <i class="bi bi-trash3"></i>
                                </button>

                                <form id="form-eliminar-{{ $lista->id }}"
                                    action="{{ route('listas.destroy', encrypt($lista->id)) }}" method="POST"
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
                    ¿Estás seguro que deseas eliminar esta lista?
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
                    <h5>Eliminando lista...</h5>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal "Actualizando estado..." -->
    <div class="modal fade" id="modalActualizandoEstado" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark text-white border-info">
                <div class="modal-body text-center p-5">
                    <div class="spinner-border text-info mb-3" role="status"></div>
                    <h5>Actualizando estado...</h5>
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

        // Redirigir al hacer clic en la fila
        document.querySelectorAll('.fila-lista').forEach(fila => {
            fila.addEventListener('click', function(e) {
                // Evita que se active si se hace clic en los botones de acción
                if (e.target.closest('button') || e.target.closest('a') || e.target.tagName === 'I') {
                    return;
                }

                const id = this.getAttribute('data-id');
                if (id) {
                    window.location.href = `/lista-semanal/${id}`;
                }
            });
        });

        // ESTADO DE LA LISTA (VISIBLE/INVISIBLE)
        document.querySelectorAll('.toggle-estado').forEach(boton => {
            boton.addEventListener('click', function() {
                const id = this.dataset.id;
                const icono = this.querySelector('i');
                const botonActual = this;

                // Mostrar modal "Actualizando estado..."
                const modalEstado = new bootstrap.Modal(document.getElementById(
                'modalActualizandoEstado'), {
                    backdrop: 'static',
                    keyboard: false
                });
                modalEstado.show();

                fetch(`/lista-semanal/${id}/estado`, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            const nuevoEstado = data.estado;
                            icono.className = 'bi ' + (nuevoEstado ? 'bi-eye-fill' :
                                'bi-eye-slash-fill');
                            botonActual.title = nuevoEstado ? 'Ocultar' : 'Mostrar';
                        }
                    })
                    .catch(error => {
                        console.error('Error al cambiar estado:', error);
                    })
                    .finally(() => {
                        modalEstado.hide();
                    });
            });
        });
    </script>

@endsection
