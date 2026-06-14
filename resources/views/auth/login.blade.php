<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login Admin — Qodha GIS</title>

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

    <style>
        body { font-family: 'Inter', sans-serif; background: #0a0a0a; color: #e8e8e8; }
        .font-display { font-family: 'Space Grotesk', sans-serif; }

        .dark-input {
            background: #111;
            border: 1px solid #2a2a2a;
            color: #e8e8e8;
            border-radius: 6px;
            padding: 10px 14px;
            width: 100%;
            font-size: 14px;
            outline: none;
            transition: border-color 0.2s ease;
        }
        .dark-input:focus { border-color: #777; }
        .dark-input::placeholder { color: #555; }
    </style>
</head>
<body class="antialiased min-h-screen flex items-center justify-center px-4">

    <div class="w-full max-w-sm">

        <div class="flex items-center justify-center gap-2 mb-8">
            <div class="w-9 h-9 border border-ink-100 flex items-center justify-center font-display font-bold text-base">
                Q
            </div>
            <div class="font-display font-semibold text-xl tracking-tight">
                Qodha <span class="text-ink-400 font-normal">GIS</span>
            </div>
        </div>

        <div class="border border-ink-700 bg-ink-800/50 rounded-lg p-6 sm:p-8">
            <p class="text-[11px] uppercase tracking-[0.3em] text-ink-400 font-medium mb-1">Admin Panel</p>
            <h1 class="font-display font-bold text-2xl text-white mb-6">Masuk ke Akun</h1>

            @if (session('status'))
                <div class="mb-4 text-xs text-ink-200 border border-ink-600 rounded px-3 py-2">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 text-xs text-white border border-ink-400 rounded px-3 py-2 space-y-1">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.attempt') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-[11px] uppercase tracking-wider text-ink-400 mb-1.5">
                        Email
                    </label>
                    <input
                        type="email"
                        name="email"
                        id="email"
                        value="{{ old('email') }}"
                        placeholder="admin@qodha.id"
                        class="dark-input"
                        required
                        autofocus
                        autocomplete="username"
                    >
                </div>

                <div>
                    <label for="password" class="block text-[11px] uppercase tracking-wider text-ink-400 mb-1.5">
                        Password
                    </label>
                    <input
                        type="password"
                        name="password"
                        id="password"
                        placeholder="••••••••"
                        class="dark-input"
                        required
                        autocomplete="current-password"
                    >
                </div>

                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center gap-2 text-ink-300 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-ink-600 bg-ink-900 text-white focus:ring-0">
                        Ingat saya
                    </label>
                </div>

                <button
                    type="submit"
                    class="w-full bg-white text-ink-900 font-display font-semibold text-sm py-2.5 rounded-md hover:bg-ink-100 transition-colors"
                >
                    Masuk
                </button>
            </form>
        </div>

        <div class="text-center mt-6">
            <a href="{{ route('map.index') }}" class="text-xs text-ink-400 hover:text-white transition-colors">
                &larr; Kembali ke halaman utama
            </a>
        </div>
    </div>

</body>
</html>
