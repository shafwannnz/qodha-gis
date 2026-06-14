<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Qodha GIS — Persebaran Mitra')</title>

    <link rel="icon" href="https://qodha.id/favicon.ico" type="image/x-icon">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">

    {{-- Leaflet CSS --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    {{-- Leaflet MarkerCluster --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" />

    {{-- TailwindCSS CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        display: ['Space Grotesk', 'sans-serif'],
                        body: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        ink: {
                            DEFAULT: '#0a0a0a',
                            50: '#f5f5f5', 100: '#e8e8e8', 200: '#c8c8c8',
                            300: '#a0a0a0', 400: '#707070', 500: '#4a4a4a',
                            600: '#2a2a2a', 700: '#1a1a1a', 800: '#111111', 900: '#0a0a0a',
                        },
                    },
                }
            }
        }
    </script>

    <style>
        * { box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: #0a0a0a;
            color: #e8e8e8;
            min-height: 100vh;
        }

        .font-display { font-family: 'Space Grotesk', sans-serif; }

        ::-webkit-scrollbar { width: 4px; height: 4px; }
        ::-webkit-scrollbar-track { background: #111; }
        ::-webkit-scrollbar-thumb { background: #333; border-radius: 2px; }

        /* Leaflet dark override */
        .leaflet-container {
            background: #111 !important;
            font-family: 'Inter', sans-serif !important;
        }
        .leaflet-tile { filter: grayscale(100%) invert(92%) brightness(0.85) contrast(0.9); }
        .leaflet-control-zoom a {
            background: #1a1a1a !important;
            color: #e8e8e8 !important;
            border-color: #333 !important;
        }
        .leaflet-control-attribution {
            background: rgba(10,10,10,0.8) !important;
            color: #555 !important;
            font-size: 10px;
        }
        .leaflet-control-attribution a { color: #777 !important; }

        /* Popup styles */
        .leaflet-popup-content-wrapper {
            background: #1a1a1a !important;
            border: 1px solid #2a2a2a !important;
            border-radius: 8px !important;
            box-shadow: 0 8px 32px rgba(0,0,0,0.6) !important;
            color: #e8e8e8 !important;
        }
        .leaflet-popup-tip { background: #1a1a1a !important; }
        .leaflet-popup-close-button { color: #a0a0a0 !important; }

        /* Cluster overrides */
        .marker-cluster-small div,
        .marker-cluster-medium div,
        .marker-cluster-large div {
            background: #0a0a0a !important;
            color: #fff !important;
            font-family: 'Space Grotesk', sans-serif !important;
            font-weight: 600 !important;
        }
        .marker-cluster-small { background: rgba(255,255,255,0.08) !important; }
        .marker-cluster-medium { background: rgba(255,255,255,0.12) !important; }
        .marker-cluster-large { background: rgba(255,255,255,0.18) !important; }

        /* ============================================================
           FIX BUG: Sidebar filter overlap saat mobile
           - Background dibuat SOLID (bukan transparan) agar konten
             section lain tidak "tembus pandang" di belakang sidebar.
           - z-index dinaikkan & dipastikan lebih tinggi dari overlay.
           - Body discroll-lock saat sidebar mobile terbuka.
        ============================================================ */
        #sidebar {
            transition: transform 0.3s ease;
            background: #0a0a0a; /* solid, bukan /50 transparan */
        }
        #sidebar.sidebar-mobile-open {
            position: fixed;
            inset: 5rem 1rem auto 1rem;
            z-index: 60;
            max-height: 80vh;
            overflow-y: auto;
            box-shadow: 0 20px 60px rgba(0,0,0,0.7);
        }
        #sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.7);
            z-index: 50; /* di bawah sidebar (60) */
        }
        body.sidebar-locked {
            overflow: hidden;
        }

        /* Input / select dark styles */
        .dark-input {
            background: #111;
            border: 1px solid #2a2a2a;
            color: #e8e8e8;
            border-radius: 6px;
            padding: 8px 12px;
            width: 100%;
            font-size: 13px;
            outline: none;
        }
        .dark-input:focus { border-color: #555; }
        .dark-input option { background: #111; }

        /* Badge styles */
        .badge-aktif { background: #1a1a1a; color: #d4d4d4; border: 1px solid #2a2a2a; }
        .badge-nonaktif { background: #111; color: #555; border: 1px solid #222; }
        .badge-sd { background: #fff; color: #0a0a0a; }
        .badge-dist { background: #e8e8e8; color: #1a1a1a; }
        .badge-res { background: #2a2a2a; color: #aaa; }
        .badge-agen { background: #1a1a1a; color: #888; border: 1px solid #333; }

        /* ============================================================
           HOVER EFFECTS — section-hover
           Diterapkan ke: stat cards, produk cards, kemitraan cards,
           dan nav links di header.
        ============================================================ */
        .section-hover {
            transition: transform 0.25s ease, border-color 0.25s ease, box-shadow 0.25s ease;
        }
        .section-hover:hover {
            transform: translateY(-3px);
            border-color: #555 !important;
            box-shadow: 0 8px 24px rgba(255,255,255,0.04);
        }

        nav a {
            position: relative;
        }
        nav a.nav-underline::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 0;
            height: 1px;
            background: #fff;
            transition: width 0.25s ease;
        }
        nav a.nav-underline:hover::after { width: 100%; }

        /* Mobile sidebar overlay placeholder */
        #sidebar-overlay { display: none; }

        /* User location marker pulse */
        .user-location-pulse {
            animation: pulse-ring 1.8s ease-out infinite;
        }
        @keyframes pulse-ring {
            0%   { box-shadow: 0 0 0 0 rgba(255,255,255,0.5); }
            70%  { box-shadow: 0 0 0 12px rgba(255,255,255,0); }
            100% { box-shadow: 0 0 0 0 rgba(255,255,255,0); }
        }
    </style>

    @stack('head')
</head>
<body class="antialiased">

    @include('components.header')

    <main>
        @yield('content')
    </main>

    {{-- Leaflet JS --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>

    {{-- Leaflet Heatmap plugin --}}
    <script src="https://unpkg.com/leaflet.heat@0.2.0/dist/leaflet-heat.js"></script>

    {{-- Turf.js untuk analisis spasial (radius, distance) --}}
    <script src="https://unpkg.com/@turf/turf@6/turf.min.js"></script>

    {{-- Chart.js untuk dashboard analytics --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @stack('scripts')
</body>
</html>
