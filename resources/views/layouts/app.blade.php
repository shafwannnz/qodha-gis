<!DOCTYPE html>
<html lang="id" class="scroll-smooth dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Qodha GIS — Persebaran Mitra')</title>

    <link rel="icon" href="{{ asset('images/logo-qodha.png') }}" type="image/png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">

    {{-- Leaflet CSS --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" />

    {{-- TailwindCSS CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
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

    {{--
        ============================================================
        DARK / LIGHT MODE — CSS VARIABLES
        ============================================================
        PERBAIKAN (v3):
        1. Selector override warna sekarang menggunakan
           `html.dark .bg-ink-900` / `html:not(.dark) .bg-ink-900`
           dengan spesifisitas LEBIH TINGGI daripada utility class
           Tailwind biasa (2 class selector vs 1), sehingga PASTI
           menang tanpa harus mengandalkan !important secara liar
           yang sebelumnya masih bisa kalah dari Tailwind CDN
           (yang men-generate beberapa utility dengan !important
           juga, contoh: beberapa preflight base styles).

        2. `hover:text-white` di nav & link sebelumnya SELALU
           menuju putih (#ffffff) meskipun di light mode — membuat
           teks heading & hover nav jadi tak terbaca di atas latar
           terang. Sekarang di-override:
             - dark mode  : hover -> putih (#ffffff)
             - light mode : hover -> hitam (#0a0a0a)

        3. Heading besar (h1, h2, dst dengan class text-white)
           dipaksa memakai var(--text-heading) dengan selector
           bertarget tag+class agar tidak ada lagi heading yang
           "putih pudar di atas putih" pada light mode.
    --}}
    <style>
        * { box-sizing: border-box; }

        :root {
            --bg-base: #0a0a0a;
            --bg-surface: #111111;
            --bg-surface-soft: rgba(17,17,17,0.5);
            --border-color: #2a2a2a;
            --border-color-soft: #1a1a1a;
            --text-primary: #e8e8e8;
            --text-heading: #ffffff;
            --text-muted: #707070;
            --text-faint: #4a4a4a;
            --text-hover: #ffffff;
            --tile-filter: grayscale(100%) invert(92%) brightness(0.85) contrast(0.9);
        }

        /* ============================================================
           LIGHT MODE OVERRIDES
        ============================================================ */
        html:not(.dark) {
            --bg-base: #ffffff;
            --bg-surface: #f7f7f7;
            --bg-surface-soft: rgba(247,247,247,0.7);
            --border-color: #e0e0e0;
            --border-color-soft: #ececec;
            --text-primary: #2a2a2a;
            --text-heading: #0a0a0a;
            --text-muted: #6b6b6b;
            --text-faint: #9a9a9a;
            --text-hover: #0a0a0a;
            --tile-filter: grayscale(60%) brightness(1.05) contrast(0.95);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-base);
            color: var(--text-primary);
            min-height: 100vh;
            transition: background-color 0.25s ease, color 0.25s ease;
        }

        .font-display { font-family: 'Space Grotesk', sans-serif; }

        ::-webkit-scrollbar { width: 4px; height: 4px; }
        ::-webkit-scrollbar-track { background: var(--bg-surface); }
        ::-webkit-scrollbar-thumb { background: var(--border-color); border-radius: 2px; }

        /* ------------------------------------------------------------
           Override warna Tailwind statis — selector ganda (html.dark / html:not(.dark))
           untuk spesifisitas lebih tinggi daripada utility class tunggal.
        ------------------------------------------------------------ */
        html.dark .bg-ink-900,
        html.dark .bg-ink-900\/90,
        html:not(.dark) .bg-ink-900,
        html:not(.dark) .bg-ink-900\/90 {
            background-color: var(--bg-base) !important;
        }

        html.dark .bg-ink-800,
        html.dark .bg-ink-800\/50,
        html:not(.dark) .bg-ink-800,
        html:not(.dark) .bg-ink-800\/50 {
            background-color: var(--bg-surface-soft) !important;
        }

        html.dark .border-ink-700,
        html.dark .border-ink-600,
        html:not(.dark) .border-ink-700,
        html:not(.dark) .border-ink-600 {
            border-color: var(--border-color) !important;
        }

        html.dark .text-ink-200,
        html.dark .text-ink-300,
        html:not(.dark) .text-ink-200,
        html:not(.dark) .text-ink-300 {
            color: var(--text-primary) !important;
        }

        html.dark .text-ink-400,
        html:not(.dark) .text-ink-400 {
            color: var(--text-muted) !important;
        }

        html.dark .text-ink-500,
        html:not(.dark) .text-ink-500 {
            color: var(--text-faint) !important;
        }

        /* Heading / text-white -> selalu ikut --text-heading,
           di light mode jadi HITAM, di dark mode jadi PUTIH */
        html.dark .text-white,
        html:not(.dark) .text-white {
            color: var(--text-heading) !important;
        }

        /* ------------------------------------------------------------
           FIX UTAMA: hover:text-white
           Sebelumnya hardcode ke putih di semua mode.
           Sekarang ikut --text-hover (putih di dark, hitam di light).
        ------------------------------------------------------------ */
        html.dark .hover\:text-white:hover,
        html:not(.dark) .hover\:text-white:hover {
            color: var(--text-hover) !important;
        }

        /* Tombol putih solid (CTA) tetap kontras di kedua mode */
        .btn-solid {
            background: var(--text-heading);
            color: var(--bg-base);
        }
        html.dark .btn-solid { background: #ffffff; color: #0a0a0a; }
        html:not(.dark) .btn-solid { background: #0a0a0a; color: #ffffff; }

        /* Leaflet dark/light override */
        .leaflet-container {
            background: var(--bg-surface) !important;
            font-family: 'Inter', sans-serif !important;
        }
        .leaflet-tile { filter: var(--tile-filter); }
        .leaflet-control-zoom a {
            background: var(--bg-surface) !important;
            color: var(--text-primary) !important;
            border-color: var(--border-color) !important;
        }
        .leaflet-control-attribution {
            background: var(--bg-surface-soft) !important;
            color: var(--text-muted) !important;
            font-size: 10px;
        }
        .leaflet-control-attribution a { color: var(--text-muted) !important; }

        .leaflet-popup-content-wrapper {
            background: var(--bg-surface) !important;
            border: 1px solid var(--border-color) !important;
            border-radius: 8px !important;
            box-shadow: 0 8px 32px rgba(0,0,0,0.3) !important;
            color: var(--text-primary) !important;
        }
        .leaflet-popup-tip { background: var(--bg-surface) !important; }
        .leaflet-popup-close-button { color: var(--text-muted) !important; }

        .marker-cluster-small div,
        .marker-cluster-medium div,
        .marker-cluster-large div {
            background: var(--bg-base) !important;
            color: var(--text-heading) !important;
            font-family: 'Space Grotesk', sans-serif !important;
            font-weight: 600 !important;
        }
        .marker-cluster-small { background: rgba(128,128,128,0.15) !important; }
        .marker-cluster-medium { background: rgba(128,128,128,0.22) !important; }
        .marker-cluster-large { background: rgba(128,128,128,0.3) !important; }

        /* Sidebar filter */
        #sidebar {
            transition: transform 0.3s ease, background-color 0.25s ease;
            background: var(--bg-base);
        }
        #sidebar.sidebar-mobile-open {
            position: fixed;
            inset: 5rem 1rem auto 1rem;
            z-index: 60;
            max-height: 80vh;
            overflow-y: auto;
            box-shadow: 0 20px 60px rgba(0,0,0,0.4);
        }
        #sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.6);
            z-index: 50;
        }
        body.sidebar-locked { overflow: hidden; }

        .dark-input {
            background: var(--bg-surface);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            border-radius: 6px;
            padding: 8px 12px;
            width: 100%;
            font-size: 13px;
            outline: none;
        }
        .dark-input:focus { border-color: var(--text-muted); }
        .dark-input option { background: var(--bg-surface); color: var(--text-primary); }

        /* Badges */
        .badge-aktif { background: var(--bg-surface); color: var(--text-primary); border: 1px solid var(--border-color); }
        .badge-nonaktif { background: var(--bg-surface); color: var(--text-muted); border: 1px solid var(--border-color-soft); }
        .badge-sd { background: var(--text-heading); color: var(--bg-base); }
        .badge-dist { background: var(--text-primary); color: var(--bg-base); }
        .badge-res { background: var(--border-color); color: var(--text-muted); }
        .badge-agen { background: var(--bg-surface); color: var(--text-muted); border: 1px solid var(--border-color); }

        /* Hover effects (card lift) */
        .section-hover {
            transition: transform 0.25s ease, border-color 0.25s ease, box-shadow 0.25s ease;
        }
        .section-hover:hover {
            transform: translateY(-3px);
            border-color: var(--text-muted) !important;
            box-shadow: 0 8px 24px rgba(0,0,0,0.08);
        }

        /* Nav underline animation */
        nav a { position: relative; }
        nav a.nav-underline::after {
            content: '';
            position: absolute;
            bottom: -4px; left: 0;
            width: 0; height: 1px;
            background: var(--text-hover);
            transition: width 0.25s ease;
        }
        nav a.nav-underline:hover::after { width: 100%; }

        .user-location-pulse { animation: pulse-ring 1.8s ease-out infinite; }
        @keyframes pulse-ring {
            0%   { box-shadow: 0 0 0 0 rgba(128,128,128,0.5); }
            70%  { box-shadow: 0 0 0 12px rgba(128,128,128,0); }
            100% { box-shadow: 0 0 0 0 rgba(128,128,128,0); }
        }

        /* ------------------------------------------------------------
           Theme toggle button
        ------------------------------------------------------------ */
        #theme-toggle-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            transition: border-color 0.2s ease, color 0.2s ease, transform 0.2s ease;
        }
        #theme-toggle-btn:hover {
            border-color: var(--text-muted);
            transform: scale(1.05);
            color: var(--text-hover);
        }
        #theme-toggle-btn svg { width: 16px; height: 16px; }
        #icon-sun { display: none; }
        #icon-moon { display: inline-block; }
        html:not(.dark) #icon-sun { display: inline-block; }
        html:not(.dark) #icon-moon { display: none; }

        /* ------------------------------------------------------------
           Footer social icons
        ------------------------------------------------------------ */
        .social-icon-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            transition: border-color 0.2s ease, transform 0.2s ease, background-color 0.2s ease, color 0.2s ease;
        }
        .social-icon-btn:hover {
            border-color: var(--text-muted);
            transform: translateY(-2px);
            background: var(--bg-surface);
            color: var(--text-hover);
        }
        .social-icon-btn svg { width: 18px; height: 18px; }
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
    <script src="https://unpkg.com/leaflet.heat@0.2.0/dist/leaflet-heat.js"></script>
    <script src="https://unpkg.com/@turf/turf@6/turf.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- Theme toggle script --}}
    <script src="{{ asset('js/theme-toggle.js') }}"></script>

    @stack('scripts')
</body>
</html>
