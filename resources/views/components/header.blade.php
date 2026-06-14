{{--
    Header — Landing Page Publik (Customer)

    PERUBAHAN:
    - Link "Admin" / "Dashboard" DIHAPUS dari nav (baik desktop maupun mobile).
      Halaman ini murni untuk customer/calon mitra.
    - Akses admin sekarang HANYA via URL langsung: /admin (lihat routes/web.php).
    - Anchor "#statistik" diganti menjadi "#syarat-ketentuan" karena section
      Statistik dipindah ke halaman admin, dan slot tersebut di landing page
      sekarang berisi Syarat & Ketentuan Mitra.
--}}

<header class="sticky top-0 z-50 bg-ink-900/90 backdrop-blur border-b border-ink-700">
    <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            <a href="{{ route('map.index') }}" class="flex items-center gap-2 group">
                <div class="w-8 h-8 border border-ink-100 flex items-center justify-center font-display font-bold text-sm">
                    Q
                </div>
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
            </nav>

            <button id="mobile-menu-btn" class="md:hidden p-2 text-ink-200 hover:text-white" aria-label="Buka menu">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </button>
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
</script>
