<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('titulo', config('app.name', 'Laravel'))</title>

    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Fira+Code&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    @php
        function vite_asset($path)
        {
            $manifestPath = public_path('build/manifest.json');

            if (!file_exists($manifestPath)) {
                return '';
            }

            $manifest = json_decode(file_get_contents($manifestPath), true);

            return asset('build/' . ($manifest[$path]['file'] ?? ''));
        }
    @endphp

    <link rel="stylesheet" href="{{ vite_asset('resources/sass/app.scss') }}">
    <script src="{{ vite_asset('resources/js/app.js') }}" defer></script>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">

    <style>
        body {
            background-color: #111010;
            color: #F5F5F5;
            font-family: 'Inter', sans-serif;
            margin: 0;
        }

        .sidemenu {
            width: 250px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #1a1a1a;
            padding-top: 20px;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.5);
            z-index: 1030;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .sidemenu a {
            color: #F5F5F5;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            font-weight: bold;
        }

        .sidemenu a:hover {
            background-color: #9AFCE6;
            color: #111010;
            transition: 0.2s;
        }

        .main-content {
            margin-left: 250px;
            padding: 20px;
        }

        .brand {
            font-size: 24px;
            text-align: center;
            color: #9AFCE6;
            margin-bottom: 30px;
        }

        .brand img {
            width: 60%;
            height: auto;
        }

        .active-link {
            background-color: #9AFCE6 !important;
            color: #111010 !important;
        }

        .dt-buttons {
            float: right;
            margin-bottom: 10px;
        }

        @media (max-width: 768px) {
            .sidemenu {
                display: none;
            }

            .main-content {
                margin-left: 0;
            }

            .mobile-toggle {
                display: block;
                position: fixed;
                top: 10px;
                left: 10px;
                z-index: 1040;
            }
        }

        @media (min-width: 769px) {
            .mobile-toggle {
                display: none;
            }
        }

        .select2-container--bootstrap4 .select2-selection {
            background-color: #1a1a1a !important;
            color: #F5F5F5 !important;
            border: 1px solid #6c757d;
        }

        .select2-container--bootstrap4 .select2-selection__rendered {
            color: #F5F5F5 !important;
        }

        .select2-container--bootstrap4.select2-container--focus .select2-selection {
            border-color: #9AFCE6 !important;
            box-shadow: 0 0 5px rgba(255, 3, 3, 0.5);
        }

        .select2-container--bootstrap4 .select2-results__option {
            background-color: #1a1a1a;
            color: #F5F5F5;
        }

        .select2-container--bootstrap4 .select2-results__option--highlighted {
            background-color: #9AFCE6;
            color: #fff;
        }
    </style>
</head>

<body>
    <div id="app">
        <button class="btn btn-outline-light mobile-toggle" type="button" data-bs-toggle="offcanvas"
            data-bs-target="#mobileSidebar" aria-controls="mobileSidebar">
            ☰
        </button>

        <nav class="sidemenu d-none d-md-flex">
            <div>
                <div class="brand">
                    <img src="{{ asset('favicon.png') }}" alt="Logo">
                </div>

                @guest
                    @if (Route::has('login'))
                        <a href="{{ route('login') }}"><i class="bi bi-box-arrow-in-right"></i> Login</a>
                    @endif
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"><i class="bi bi-person-plus"></i> Register</a>
                    @endif
                @else
                    <div class="text-center text-white fw-bold mb-3">Hola, <span
                            class="text-primary">{{ Auth::user()->name }}</span></div>
                    <a href="{{ url('/home') }}" class="{{ request()->is('home') ? 'active-link' : '' }} mb-3">
                        <i class="bi bi-house-door"></i> Inicio
                    </a>
                    <a href="{{ url('/canciones') }}" class="{{ request()->is('canciones') ? 'active-link' : '' }} mb-3">
                        <i class="bi bi-music-note-list"></i> Canciones
                    </a>
                    <a href="{{ url('/lista-semanal') }}"
                        class="{{ request()->is('lista-semanal') ? 'active-link' : '' }} mb-3">
                        <i class="bi bi-journal-text"></i> Lista
                    </a>
                    <a href="{{ url('/etiquetas') }}" class="{{ request()->is('etiquetas') ? 'active-link' : '' }} mb-3">
                        <i class="bi bi-tags"></i> Etiquetas
                    </a>
                @endguest
            </div>

            @auth
                <form id="logout-form" action="{{ route('logout') }}" method="POST">
                    @csrf
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">
                        <i class="bi bi-box-arrow-right"></i> Cerrar sesión
                    </a>
                </form>
            @endauth
        </nav>

        <main class="main-content">
            @yield('content')
        </main>
    </div>


    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <!-- DataTables Core -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <!-- Responsive Extension -->
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

    <!-- Buttons Extension -->
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.colVis.min.js"></script>

    <!-- Export Dependencies -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

    <!-- Español -->
    <script src="https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"></script>

    {{-- SELECT2 --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Select2 Bootstrap Theme -->
    <link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css"
        rel="stylesheet">


    <!-- Activación global -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            if ($('#datatable').length) {
                $('#datatable').DataTable({
                    responsive: true,
                    dom: '<"d-flex justify-content-between align-items-center mb-2"lfB>rt<"d-flex justify-content-between align-items-center mt-2"lip>',
                    buttons: ['copy', 'excel', 'pdf', 'print'],
                    language: {
                        url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
                    },
                    lengthMenu: [
                        [10, 25, 50, 100, -1],
                        [10, 25, 50, 100, "Todos"]
                    ],
                    pageLength: 25
                });
            }
            // ✅ Inicialización global de Select2
            $('.select2').select2({
                theme: 'bootstrap4',
                width: '100%' // puedes ajustarlo a 'resolve' si es necesario
            });
        });
    </script>
</body>

</html>
