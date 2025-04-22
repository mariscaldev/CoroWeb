@extends('layouts.app')

@section('titulo', 'Agregar Lista')

@section('content')
    <style>
        textarea.form-control {
            font-family: 'Fira Code', monospace;
            white-space: pre-wrap;
            background-color: #1a1a1a;
            color: #F5F5F5;
        }

        .selected-song {
            background-color: #2b2b2b;
            padding: 10px 15px;
            border-radius: 6px;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .selected-song .song-name {
            flex-grow: 1;
            font-weight: bold;
        }

        .selected-song select {
            min-width: 220px;
            max-width: 260px;
        }

        .selected-song .remove-song {
            cursor: pointer;
            color: red;
            font-size: 1.2rem;
            margin-left: auto;
        }
    </style>

    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0">Agregar Lista</h1>
            <a href="{{ route('listas.index') }}" class="btn btn-outline-light">
                <i class="bi bi-arrow-left"></i> Volver al listado
            </a>
        </div>

        <form action="{{ route('listas.store') }}" method="POST" class="card bg-dark text-white p-4" id="form-lista">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">ID Asignado</label>
                    <input type="text" class="form-control bg-secondary text-white" value="{{ $nextId }}" readonly>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Estado</label>
                    <input type="text" class="form-control bg-secondary text-white" value="ACTIVO" readonly>
                    <input type="hidden" name="estado" value="1">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Nombre</label>
                    <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
                        required>
                    @error('nombre')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Seleccionar canciones</label>
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

                <div class="col-md-12 mt-4 text-center">
                    <button type="button" class="btn btn-success" id="btn-crear-lista">
                        <i class="bi bi-plus-circle"></i> Crear Lista
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
                    <h5>Registrando lista...</h5>
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
                        La lista se ha creado correctamente.
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
                            <i class="bi bi-exclamation-octagon-fill me-2"></i> Error al crear la lista
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
            const contenedorSeleccionadas = document.getElementById('lista-seleccionadas');
            const inputIds = document.getElementById('input-id-canciones');
            const btnCrear = document.getElementById('btn-crear-lista');
            const form = document.getElementById('form-lista');

            let cancionesSeleccionadas = [];

            const opcionesTipo = {
                0: 'Todas las canciones',
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
                14: 'Canto Final'
            };

            selectCancion.select2({
                dropdownParent: $('.container'),
                width: '100%'
            });

            selectCancion.on('select2:select', function(e) {
                const id = e.params.data.id;
                const nombre = e.params.data.text;

                if (cancionesSeleccionadas.find(c => c.id === id)) {
                    selectCancion.val(null).trigger('change');
                    return;
                }

                // Crear select de categoría
                const selectTipo = document.createElement('select');
                selectTipo.className = 'form-select tipo-select2 ms-2';
                selectTipo.style.width = '200px';

                for (const [val, text] of Object.entries(opcionesTipo)) {
                    const option = document.createElement('option');
                    option.value = val;
                    option.textContent = text;
                    selectTipo.appendChild(option);
                }

                // Crear wrapper de la canción seleccionada
                const wrapper = document.createElement('div');
                wrapper.className = 'selected-song';

                const nombreSpan = document.createElement('span');
                nombreSpan.className = 'song-name';
                nombreSpan.textContent = nombre;

                const removeBtn = document.createElement('span');
                removeBtn.className = 'remove-song';
                removeBtn.setAttribute('data-id', id);
                removeBtn.innerHTML = '&times;';

                wrapper.appendChild(nombreSpan);
                wrapper.appendChild(selectTipo);
                wrapper.appendChild(removeBtn);

                contenedorSeleccionadas.appendChild(wrapper);

                // Agrega al array y escucha cambios de categoría
                cancionesSeleccionadas.push({
                    id,
                    tipo: selectTipo.value // por defecto 0
                });

                selectTipo.addEventListener('change', function() {
                    const cancion = cancionesSeleccionadas.find(c => c.id === id);
                    if (cancion) cancion.tipo = this.value;
                    actualizarInputOculto();
                });

                selectCancion.val(null).trigger('change');
                actualizarInputOculto();
            });


            contenedorSeleccionadas.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-song')) {
                    const id = e.target.getAttribute('data-id');
                    cancionesSeleccionadas = cancionesSeleccionadas.filter(c => c.id !== id);
                    e.target.parentElement.remove();
                    actualizarInputOculto();
                }
            });

            function actualizarInputOculto() {
                const combinados = cancionesSeleccionadas.map(c => `${c.id}/${c.tipo}`);
                inputIds.value = combinados.join('~');
            }

            btnCrear.addEventListener('click', function() {
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
