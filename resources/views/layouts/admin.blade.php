<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — Qodha GIS</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">

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
                            50: '#f5f5f5', 100: '#e8e8e8', 200: '#c8c8c8',
                            300: '#a0a0a0', 400: '#707070', 500: '#4a4a4a',
                            600: '#2a2a2a', 700: '#1a1a1a', 800: '#111111', 900: '#0a0a0a',
                        },
                    },
                }
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #0a0a0a; color: #e8e8e8; min-height: 100vh; }
        .font-display { font-family: 'Space Grotesk', sans-serif; }

        ::-webkit-scrollbar { width: 4px; height: 4px; }
        ::-webkit-scrollbar-track { background: #111; }
        ::-webkit-scrollbar-thumb { background: #333; border-radius: 2px; }

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
        .dark-input::placeholder { color: #555; }

        .section-hover {
            transition: transform 0.25s ease, border-color 0.25s ease, box-shadow 0.25s ease;
        }
        .section-hover:hover {
            transform: translateY(-2px);
            border-color: #555 !important;
            box-shadow: 0 8px 24px rgba(255,255,255,0.04);
        }

        .badge-aktif { background: #1a1a1a; color: #d4d4d4; border: 1px solid #2a2a2a; }
        .badge-nonaktif { background: #111; color: #555; border: 1px solid #222; }
        .badge-sd { background: #fff; color: #0a0a0a; }
        .badge-dist { background: #e8e8e8; color: #1a1a1a; }
        .badge-res { background: #2a2a2a; color: #aaa; }
        .badge-agen { background: #1a1a1a; color: #888; border: 1px solid #333; }
    </style>

    @stack('head')
</head>
<body class="antialiased">

    {{-- Header --}}
    <header class="sticky top-0 z-50 bg-ink-900/90 backdrop-blur border-b border-ink-700">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
                    <div class="w-8 h-8 border border-ink-100 flex items-center justify-center font-display font-bold text-sm">
                        Q
                    </div>
                    <div class="font-display font-semibold text-lg tracking-tight">
                        Qodha <span class="text-ink-400 font-normal">GIS</span>
                        <span class="text-ink-500 text-xs font-normal ml-1">/ Admin</span>
                    </div>
                </a>

                <nav class="hidden md:flex items-center gap-6 text-sm font-medium text-ink-300">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-white transition-colors {{ request()->routeIs('admin.dashboard') ? 'text-white' : '' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('admin.mitras.index') }}" class="hover:text-white transition-colors {{ request()->routeIs('admin.mitras.*') ? 'text-white' : '' }}">
                        Data Mitra
                    </a>
                    <a href="{{ route('map.index') }}" class="hover:text-white transition-colors">
                        Lihat Peta
                    </a>
                </nav>

                <div class="flex items-center gap-3">
                    <span class="hidden sm:inline text-xs text-ink-400">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="text-sm border border-ink-600 rounded px-3 py-1.5 text-ink-200 hover:text-white hover:border-ink-400 transition-colors">
                            Logout
                        </button>
                    </form>
                </div>
            </div>

            {{-- Mobile nav --}}
            <nav class="md:hidden pb-3 flex items-center gap-4 text-sm font-medium text-ink-300">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-white transition-colors {{ request()->routeIs('admin.dashboard') ? 'text-white' : '' }}">
                    Dashboard
                </a>
                <a href="{{ route('admin.mitras.index') }}" class="hover:text-white transition-colors {{ request()->routeIs('admin.mitras.*') ? 'text-white' : '' }}">
                    Data Mitra
                </a>
                <a href="{{ route('map.index') }}" class="hover:text-white transition-colors">
                    Lihat Peta
                </a>
            </nav>
        </div>
    </header>

    {{-- Flash message --}}
    @if (session('status'))
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 pt-4">
            <div class="text-xs text-ink-200 border border-ink-600 rounded px-3 py-2 bg-ink-800/50">
                {{ session('status') }}
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 pt-4">
            <div class="text-xs text-white border border-ink-400 rounded px-3 py-2 bg-ink-800/50 space-y-1">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Content --}}
    <main class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
