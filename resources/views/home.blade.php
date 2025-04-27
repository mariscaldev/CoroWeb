@extends('layouts.app')

@section('titulo', 'Inicio')

@section('content')
    <div class="container">
        <h1 class="mb-4">Panel de Control</h1>

        <div class="row">
            {{-- Gráfico de barras --}}
            <div class="col-md-8 mb-4">
                <div class="card bg-dark text-white border-light mb-4" style="height: 100%;">
                    <div class="card-header">
                        <i class="bi bi-bar-chart-fill"></i> Canciones por Etiqueta
                    </div>
                    <div class="card-body" style="height: 500px;">
                        <canvas id="graficoBarras" style="max-height: 100%;"></canvas>
                    </div>
                </div>
            </div>

            {{-- Gráficos laterales --}}
            <div class="col-md-4 d-flex flex-column justify-content-between">
                <div class="card bg-dark text-white border-light mb-4 flex-fill">
                    <div class="card-header text-center">
                        <strong>Total de:</strong>
                    </div>
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <div style="width: 150px; height: 150px;">
                            <canvas id="graficoDonut"></canvas>
                        </div>
                        <p class="mt-3 mb-0"><strong>Canciones sin etiqueta</strong></p>
                    </div>
                </div>

                <div class="card bg-dark text-white border-light flex-fill mb-4">
                    <div class="card-header">
                        <i class="bi bi-music-note-list"></i> Últimas 5 canciones agregadas
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            @foreach ($ultimas as $cancion)
                                <li class="list-group-item bg-dark text-white border-bottom">
                                    <i class="bi bi-music-note-beamed me-2"></i>
                                    <a href="{{ route('canciones.show', encrypt($cancion->id)) }}"
                                        class="text-white text-decoration-none">
                                        {{ $cancion->nombre }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Chart.js y Plugin --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const etiquetas = @json(array_column($datos, 'nombre'));
            const cantidades = @json(array_column($datos, 'cantidad'));
            const sinEtiqueta = {{ $sinEtiqueta }};
            const totalCanciones = {{ $totalCanciones }};

            const colores = etiquetas.map((_, i) => {
                const hue = (i * 360 / etiquetas.length) % 360;
                return `hsl(${hue}, 70%, 60%)`;
            });

            // Plugin para el texto central del donut
            const textoCentralPlugin = {
                id: 'textoCentralPlugin',
                beforeDraw(chart) {
                    const {
                        width,
                        height,
                        ctx
                    } = chart;
                    const texto = `${sinEtiqueta}/${totalCanciones}`;
                    ctx.save();
                    ctx.font = 'bold 22px Nunito, sans-serif';
                    ctx.fillStyle = 'white';
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';
                    ctx.fillText(texto, width / 2, height / 2);
                    ctx.restore();
                }
            };

            new Chart(document.getElementById('graficoBarras'), {
                type: 'bar',
                data: {
                    labels: etiquetas,
                    datasets: [{
                        label: 'Cantidad de canciones',
                        data: cantidades,
                        backgroundColor: colores,
                        borderColor: '#222',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            ticks: {
                                color: 'white'
                            },
                            grid: {
                                color: '#444'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                color: 'white'
                            },
                            grid: {
                                color: '#444'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        datalabels: {
                            color: 'white',
                            anchor: 'end',
                            align: 'end',
                            offset: 8,
                            font: {
                                weight: 'bold'
                            },
                            formatter: value => value
                        }
                    }
                },
                plugins: [ChartDataLabels]
            });

            // Gráfico donut
            new Chart(document.getElementById('graficoDonut'), {
                type: 'doughnut',
                data: {
                    labels: ['Sin Etiqueta', 'Con Etiqueta'],
                    datasets: [{
                        data: [sinEtiqueta, totalCanciones - sinEtiqueta],
                        backgroundColor: ['#e74c3c', '#2ecc71'],
                        borderColor: '#111',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    cutout: '70%',
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            enabled: true
                        }
                    }
                },
                plugins: [textoCentralPlugin]
            });
        });
    </script>
@endsection
