@extends('layouts.app')

@section('titulo', $cancion->nombre)

@section('content')
<style>
    .badge {
        font-size: 0.85rem;
        padding: 0.5em 0.75em;
        border-radius: 0.75rem;
    }

    .bloque-cancion {
        background-color: #1b1b1b;
        color: #F5F5F5;
        font-family: 'Fira Code', monospace;
        white-space: pre-wrap;
        padding: 1rem;
        border-radius: 8px;
        margin: 0;
        overflow-x: auto;
        line-height: 1.45;
    }
    .accordion-button::after {
        filter: brightness(0.7);
    }

    .accordion-button:hover {
        background-color: #222;
        color: #fff;
    }

    .accordion-body {
        background-color: #131313;
        border-radius: 0 0 8px 8px;
    }

    .accordion-item {
        border-left: 2px solid #dc3545;
    }
</style>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Canción: {{ $cancion->nombre }}</h1>
        <a href="{{ route('canciones.index') }}" class="btn btn-outline-light">
            <i class="bi bi-arrow-left"></i> Volver al listado
        </a>
    </div>

    <div class="accordion" id="accordionCancion">
        @php
            $bloques = [
                'introduccion' => ['Introducción', 'bi-music-note-list'],
                'letra1' => ['Letra y Acordes (1)', 'bi-file-music'],
                'interludio' => ['Interludio', 'bi-pause-btn-fill'],
                'letra2' => ['Letra y Acordes (2)', 'bi-file-music'],
                'final' => ['Final', 'bi-flag-fill'],
                'etiqueta' => ['Etiqueta', 'bi-tags-fill']
            ];
        @endphp

        <div class="accordion accordion-flush" id="accordionCancion">
            @foreach($bloques as $campo => [$titulo, $icon])
            <div class="accordion-item bg-dark border-bottom border-secondary">
                <h2 class="accordion-header" id="heading-{{ $campo }}">
                    <button class="accordion-button collapsed bg-dark text-white d-flex align-items-center gap-2" type="button"
                        data-bs-toggle="collapse" data-bs-target="#collapse-{{ $campo }}" aria-expanded="false" aria-controls="collapse-{{ $campo }}">
                        <i class="bi {{ $icon }} text-primary fs-5"></i> <span class="fw-semibold">{{ $titulo }}</span>
                    </button>
                </h2>
                <div id="collapse-{{ $campo }}" class="accordion-collapse collapse" aria-labelledby="heading-{{ $campo }}" data-bs-parent="#accordionCancion">
                    <div class="accordion-body p-3">
                        @if ($campo === 'etiqueta')
                            @foreach(explode(',', $cancion->etiqueta) as $tag)
                                <span class="badge bg-primary text-white me-2 mb-1">{{ trim($tag) }}</span>
                            @endforeach
                        @else
                            <div class="bloque-cancion">{!! e($cancion->$campo) !!}</div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

    </div>
</div>
@endsection
