@extends('layouts.app')

@section('titulo', 'Lista: ' . $lista->nombre)

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Lista: {{ $lista->nombre }}</h1>
        <a href="{{ route('listas.index') }}" class="btn btn-outline-light">
            <i class="bi bi-arrow-left"></i> Volver al listado
        </a>
    </div>

    @php
        use App\Models\Canciones;

        $categorias = [
            0 => 'Todas las canciones',
            1 => 'Entrada',
            2 => 'Canto de Perdón',
            3 => 'Gloria',
            4 => 'Salmo',
            5 => 'Antes del Evangelio',
            6 => 'Después del Evangelio',
            7 => 'Ofertorio',
            8 => 'Santo',
            9 => 'Prefacio',
            10 => 'Padre Nuestro',
            11 => 'Canto de Paz',
            12 => 'Cordero',
            13 => 'Comunión',
            14 => 'Canto Final',
        ];

        $agrupadas = [];

        if ($lista->id_canciones) {
            $items = explode('~', $lista->id_canciones);
            foreach ($items as $item) {
                [$id, $tipo] = explode('/', $item);
                $cancion = Canciones::find($id);
                if ($cancion) {
                    $agrupadas[$tipo][] = $cancion;
                }
            }
        }

        // Distribuir categorías en 3 columnas de forma continua
        $columnas = array_chunk($categorias, ceil(count($categorias) / 3), true);
    @endphp

    <div class="row">
        @foreach($columnas as $columna)
            <div class="col-md-4">
                @foreach ($columna as $tipo => $nombre)
                    <div class="card bg-dark text-white mb-4" style="height: 8rem;">
                        <div class="card-header fw-bold text-info">{{ $nombre }}</div>
                        <div class="card-body p-3">
                            @if (!empty($agrupadas[$tipo]))
                                <ul class="list-group list-group-flush">
                                    @foreach ($agrupadas[$tipo] as $cancion)
                                        <li class="list-group-item bg-dark text-white border-bottom">
                                            <i class="bi bi-music-note-beamed me-2"></i>
                                            <a href="#" class="text-white text-decoration-none"
                                                onclick="abrirCancion('{{ route('canciones.show', encrypt($cancion->id)) }}'); return false;">
                                                {{ $cancion->nombre }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-muted fst-italic mb-0">Sin canciones</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
</div>

<script>
    function abrirCancion(url) {
        window.open(
            url,
            'VisualizarCancion',
            'width=700,height=600,resizable=yes,scrollbars=yes'
        );
    }
</script>
@endsection
