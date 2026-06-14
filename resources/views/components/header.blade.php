{{--
    Header — Landing Page Publik (Customer)

    PERUBAHAN:
    - Logo "Q" kotak diganti dengan logo resmi Qodha (public/images/logo-qodha.png).
    - Tombol theme toggle (dark/light) ditambahkan di sebelah link "Tentang",
      menggunakan icon Sun/Moon dari Lucide (SVG inline, tanpa dependency JS
      tambahan — Lucide icons disalin sebagai inline SVG agar tetap ringan
      dan tidak butuh React/bundler).
    - Link "Admin"/"Dashboard" TETAP TIDAK ADA (sesuai revisi sebelumnya).
      Akses admin hanya via URL langsung /admin/login.
--}}

<header class="sticky top-0 z-50 backdrop-blur border-b" style="background-color: var(--bg-surface-soft); border-color: var(--border-color);">
    <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            <a href="{{ route('map.index') }}" class="flex items-center gap-2 group">
                <img src="{{ asset('images/logo-qodha.png') }}" alt="Qodha Aromatic" class="h-8 w-auto object-contain">
                <div class="font-display font-semibold text-lg tracking-tight">
                    Qodha <span class="text-ink-400 font-normal">GIS</span>
                </div>
            </a>

            {{-- Desktop nav --}}
            <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-ink-300">
                <a href="#peta" class="nav-underline hover:text-white transition-colors">Peta</a>
                <a href="#syarat-ketentuan" class="nav-underline hover:text-white transition-colors">Syarat &amp; Ketentuan</a>
                <a href="#produk" class="nav-underline hover:text-white transition-colors">Produk</a>
                <a href="#kemitraan" class="nav-underline hover:text-white transition-colors">Kemitraan</a>
                <a href="#about" class="nav-underline hover:text-white transition-colors">Tentang</a>

                {{-- Theme Toggle Button (Lucide: sun / moon) --}}
                <button id="theme-toggle-btn" type="button" aria-label="Ganti tema gelap/terang">
                    {{-- Lucide "moon" icon (ditampilkan saat dark mode aktif) --}}
                    <svg id="icon-moon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/>
                    </svg>
                    {{-- Lucide "sun" icon (ditampilkan saat light mode aktif) --}}
                    <svg id="icon-sun" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="4"/>
                        <path d="M12 2v2"/>
                        <path d="M12 20v2"/>
                        <path d="m4.93 4.93 1.41 1.41"/>
                        <path d="m17.66 17.66 1.41 1.41"/>
                        <path d="M2 12h2"/>
                        <path d="M20 12h2"/>
                        <path d="m6.34 17.66-1.41 1.41"/>
                        <path d="m19.07 4.93-1.41 1.41"/>
                    </svg>
                </button>
            </nav>

            {{-- Mobile: theme toggle + hamburger --}}
            <div class="md:hidden flex items-center gap-2">
                <button id="theme-toggle-btn-mobile" type="button" aria-label="Ganti tema gelap/terang"
                    class="inline-flex items-center justify-center w-9 h-9 rounded-lg border" style="border-color: var(--border-color); color: var(--text-primary);">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 theme-icon-moon">
                        <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/>
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 theme-icon-sun hidden">
                        <circle cx="12" cy="12" r="4"/>
                        <path d="M12 2v2"/><path d="M12 20v2"/>
                        <path d="m4.93 4.93 1.41 1.41"/><path d="m17.66 17.66 1.41 1.41"/>
                        <path d="M2 12h2"/><path d="M20 12h2"/>
                        <path d="m6.34 17.66-1.41 1.41"/><path d="m19.07 4.93-1.41 1.41"/>
                    </svg>
                </button>

                <button id="mobile-menu-btn" class="p-2 text-ink-200 hover:text-white" aria-label="Buka menu">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- Mobile nav --}}
        <nav id="mobile-menu" class="md:hidden hidden pb-4 flex flex-col gap-3 text-sm font-medium text-ink-300">
            <a href="#peta" class="hover:text-white transition-colors">Peta</a>
            <a href="#syarat-ketentuan" class="hover:text-white transition-colors">Syarat &amp; Ketentuan</a>
            <a href="#produk" class="hover:text-white transition-colors">Produk</a>
            <a href="#kemitraan" class="hover:text-white transition-colors">Kemitraan</a>
            <a href="#about" class="hover:text-white transition-colors">Tentang</a>
        </nav>
    </div>
</header>

<script>
    document.getElementById('mobile-menu-btn')?.addEventListener('click', () => {
        document.getElementById('mobile-menu')?.classList.toggle('hidden');
    });

    // Sinkronkan tombol toggle versi mobile dengan tombol desktop
    (function () {
        const mobileBtn = document.getElementById('theme-toggle-btn-mobile');
        const desktopBtn = document.getElementById('theme-toggle-btn');

        function syncMobileIcons() {
            const isDark = document.documentElement.classList.contains('dark');
            mobileBtn?.querySelector('.theme-icon-moon')?.classList.toggle('hidden', !isDark);
            mobileBtn?.querySelector('.theme-icon-sun')?.classList.toggle('hidden', isDark);
        }

        mobileBtn?.addEventListener('click', () => {
            desktopBtn?.click();
            syncMobileIcons();
        });

        syncMobileIcons();
    })();
</script>
