@extends('layouts.app')

@section('titulo', 'Editar Lista')

@section('content')
    <style>
        .selected-song {
            background-color: #2b2b2b;
            padding: 8px 12px;
            border-radius: 6px;
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .selected-song span {
            flex: 1;
        }

        .remove-song {
            cursor: pointer;
            color: red;
            font-weight: bold;
        }

        textarea.form-control {
            font-family: 'Fira Code', monospace;
            white-space: pre-wrap;
            background-color: #1a1a1a;
            color: #F5F5F5;
        }
    </style>

    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0">Editar Lista</h1>
            <a href="{{ route('listas.index') }}" class="btn btn-outline-light">
                <i class="bi bi-arrow-left"></i> Volver al listado
            </a>
        </div>

        <form action="{{ route('listas.update', encrypt($lista->id)) }}" method="POST" class="card bg-dark text-white p-4"
            id="form-lista">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">ID</label>
                    <input type="text" class="form-control bg-secondary text-white" value="{{ $lista->id }}" readonly>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Estado</label>
                    <input type="text" class="form-control bg-secondary text-white"
                        value="{{ $lista->estado ? 'ACTIVO' : 'INACTIVO' }}" readonly>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Nombre</label>
                    <input type="text" name="nombre" value="{{ old('nombre', $lista->nombre) }}" class="form-control"
                        required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Agregar Canciones</label>
                    <select class="form-select select2" name="cancion_id">
                        <option value="" disabled selected>Selecciona una canción...</option>
                        @foreach ($canciones as $cancion)
                            <option value="{{ $cancion->id }}">{{ $cancion->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-12 mb-3">
                    <label class="form-label fw-bold">Canciones Seleccionadas</label>
                    <div id="lista-seleccionadas" class="mb-2"></div>
                    <input type="hidden" name="id_canciones" id="input-id-canciones">
                </div>

                <!-- Botón -->
                <div class="col-md-12 text-center mt-3">
                    <button type="button" id="btn-actualizar-lista" class="btn btn-warning">
                        <i class="bi bi-save"></i> Actualizar Lista
                    </button>
                </div>

                <!-- Campo oculto para estado y fecha -->
                <input type="hidden" name="estado" value="{{ $lista->estado }}">
                <input type="hidden" name="fecha" value="{{ $lista->fecha }}">

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
                    <h5>Actualizando lista...</h5>
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
                            <i class="bi bi-check-circle-fill me-2"></i> Actualizada exitosamente
                        </h5>
                    </div>
                    <div class="modal-body">
                        La lista se ha actualizado correctamente.
                    </div>
                    <div class="modal-footer border-0 d-flex justify-content-between">
                        <button type="button" class="btn btn-outline-success"
                            onclick="window.location.href='{{ route('listas.index') }}'">
                            <i class="bi bi-arrow-right-circle"></i> Continuar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <!-- Modal de Errores -->
    @if ($errors->any())
        <div class="modal fade" id="modalErrores" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
            data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content bg-dark text-white border-danger">
                    <div class="modal-header border-0">
                        <h5 class="modal-title text-danger">
                            <i class="bi bi-exclamation-octagon-fill me-2"></i> Error al actualizar la lista
                        </h5>
                    </div>
                    <div class="modal-body">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li><i class="bi bi-x-circle me-2"></i>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">
                            <i class="bi bi-arrow-left-circle"></i> Revisar formulario
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectCancion = $('.select2');
            const contenedor = document.getElementById('lista-seleccionadas');
            const inputIds = document.getElementById('input-id-canciones');
            const btnActualizar = document.getElementById('btn-actualizar-lista');
            const form = document.getElementById('form-lista');
            let cancionesSeleccionadas = [];

            const categorias = {
                0: 'Todas',
                1: 'Entrada',
                2: 'Canto de Perdón',
                3: 'Gloria',
                4: 'Salmo',
                5: 'Antes del Evangelio',
                6: 'Después del Evangelio',
                7: 'Ofertorio',
                8: 'Santo',
                9: 'Prefacio',
                10: 'Padre Nuestro',
                11: 'Canto de Paz',
                12: 'Cordero',
                13: 'Comunión',
                14: 'Canto Final',
            };

            const datosRaw = {!! json_encode($lista->id_canciones) !!};
            if (datosRaw && datosRaw.trim() !== '') {
                const datos = datosRaw.split('~');
                datos.forEach(par => {
                    const [id, tipo] = par.split('/');
                    const nombre = $('.select2 option[value="' + id + '"]').text();
                    agregarCancion(id, nombre, tipo);
                });
            } else {
                const mensaje = document.createElement('div');
                mensaje.className = 'text-muted';
                mensaje.innerHTML = '<em>Ninguna canción agregada a esta lista.</em>';
                contenedor.appendChild(mensaje);
            }

            selectCancion.select2({
                dropdownParent: $('.container'),
                width: '100%'
            });

            selectCancion.on('select2:select', function(e) {
                const id = e.params.data.id;
                const nombre = e.params.data.text;

                if (cancionesSeleccionadas.find(c => c.id == id)) {
                    selectCancion.val(null).trigger('change');
                    return;
                }

                agregarCancion(id, nombre, 0);
                selectCancion.val(null).trigger('change');
            });

            function agregarCancion(id, nombre, categoria = 0) {
                cancionesSeleccionadas.push({
                    id,
                    categoria
                });

                const div = document.createElement('div');
                div.className = 'selected-song';
                div.innerHTML = `
                    <span>${nombre}</span>
                    <select class="form-select select-categoria" data-id="${id}" style="width: 200px;">
                        ${Object.entries(categorias).map(([val, text]) => `
                                        <option value="${val}" ${val == categoria ? 'selected' : ''}>${text}</option>
                                    `).join('')}
                    </select>
                    <span class="remove-song" data-id="${id}">&times;</span>
                `;
                contenedor.appendChild(div);
                actualizarInput();
            }

            contenedor.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-song')) {
                    const id = e.target.getAttribute('data-id');
                    cancionesSeleccionadas = cancionesSeleccionadas.filter(c => c.id != id);
                    e.target.parentElement.remove();
                    actualizarInput();
                }
            });

            contenedor.addEventListener('change', function(e) {
                if (e.target.classList.contains('select-categoria')) {
                    const id = e.target.getAttribute('data-id');
                    const nuevaCategoria = e.target.value;
                    cancionesSeleccionadas = cancionesSeleccionadas.map(c =>
                        c.id == id ? {
                            ...c,
                            categoria: nuevaCategoria
                        } : c
                    );
                    actualizarInput();
                }
            });

            function actualizarInput() {
                inputIds.value = cancionesSeleccionadas.map(c => `${c.id}/${c.categoria}`).join('~');
            }

            // Modal de Actualizando y submit
            btnActualizar.addEventListener('click', function() {
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

            @if ($errors->any())
                const modalErrores = new bootstrap.Modal(document.getElementById('modalErrores'));
                modalErrores.show();
            @endif
        });
    </script>

@endsection
