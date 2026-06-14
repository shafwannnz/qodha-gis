{{-- Hero Section --}}
<section class="border-b border-ink-700">
    <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-20">
        <div class="grid lg:grid-cols-12 gap-8 items-end">
            <div class="lg:col-span-8">
                <p class="text-[11px] uppercase tracking-[0.3em] text-ink-400 mb-4 font-medium">
                    Sistem Informasi Geografis
                </p>
                <h1 class="font-display font-bold text-4xl sm:text-5xl lg:text-7xl leading-[1.05] tracking-tight text-white">
                    Persebaran Mitra<br/>
                    <span class="text-ink-400">Qodha Aromatic</span>
                </h1>
                <p class="mt-6 text-ink-300 text-sm sm:text-base max-w-xl leading-relaxed">
                    Visualisasi interaktif jaringan mitra distributor, reseller, dan agen
                    Qodha Aromatic di seluruh Indonesia &mdash; lengkap dengan filter wilayah,
                    status kemitraan, dan statistik real-time.
                </p>
            </div>
            <div class="lg:col-span-4 lg:text-right">
                <div class="section-hover inline-flex flex-col gap-1 border border-ink-700 rounded-lg px-5 py-4">
                    <span class="font-display text-5xl font-bold text-white">{{ $stats['total'] }}</span>
                    <span class="text-[11px] uppercase tracking-widest text-ink-400">Mitra Terdaftar</span>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- About Me / Tentang Section --}}
<section id="about" class="border-b border-ink-700">
    <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
        <div class="grid lg:grid-cols-12 gap-8">
            <div class="lg:col-span-3">
                <p class="text-[11px] uppercase tracking-[0.3em] text-ink-400 font-medium">Tentang</p>
                <h2 class="font-display font-bold text-2xl lg:text-3xl mt-2 text-white">Qodha Aromatic</h2>
            </div>
            <div class="lg:col-span-9 grid sm:grid-cols-2 gap-6 text-sm text-ink-300 leading-relaxed">
                <div class="section-hover border border-transparent rounded-lg p-3 -m-3">
                    <p>
                        Qodha Aromatic adalah produsen wewangian sunnah yang memproduksi bukhur, dupa, hio,
                        dan parfum berkualitas dengan kemasan menarik, elegan, dan harga terjangkau.
                        Produk Qodha telah didistribusikan melalui jaringan mitra di berbagai wilayah
                        Indonesia, mulai dari Sumatera, Jawa, Kalimantan, hingga Nusa Tenggara &mdash;
                        bahkan hingga ke Malaysia &amp; Singapura.
                    </p>
                </div>
                <div class="section-hover border border-transparent rounded-lg p-3 -m-3">
                    <p>
                        Platform Web GIS ini dikembangkan untuk memberikan gambaran visual mengenai sebaran
                        mitra bisnis Qodha Aromatic, mempermudah analisis jangkauan pasar, serta mendukung
                        pengambilan keputusan strategis dalam pengembangan jaringan kemitraan di masa depan.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Produk Section --}}
<section id="produk" class="border-b border-ink-700">
    <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
        <div class="grid lg:grid-cols-12 gap-8">
            <div class="lg:col-span-3">
                <p class="text-[11px] uppercase tracking-[0.3em] text-ink-400 font-medium">Produk</p>
                <h2 class="font-display font-bold text-2xl lg:text-3xl mt-2 text-white">Lini Produk Kami</h2>
            </div>
            <div class="lg:col-span-9 grid sm:grid-cols-3 gap-4">
                <div class="section-hover border border-ink-700 rounded-lg p-5">
                    <h3 class="font-display font-semibold text-white mb-2">Parfum &amp; Minyak Wangi</h3>
                    <p class="text-xs text-ink-400 leading-relaxed">
                        Eau de Parfum dan Concentrate non-alkohol (0% alkohol, aman untuk ibadah) dengan
                        berbagai varian aroma khas Timur Tengah.
                    </p>
                </div>
                <div class="section-hover border border-ink-700 rounded-lg p-5">
                    <h3 class="font-display font-semibold text-white mb-2">Bukhur, Dupa &amp; Hio</h3>
                    <p class="text-xs text-ink-400 leading-relaxed">
                        Produk wewangian sunnah untuk kegiatan ibadah seperti sholat, pengajian, tabligh,
                        dan dzikir, telah bersertifikasi Halal MUI &amp; izin edar BPOM RI.
                    </p>
                </div>
                <div class="section-hover border border-ink-700 rounded-lg p-5">
                    <h3 class="font-display font-semibold text-white mb-2">Produk Aromatic Lainnya</h3>
                    <p class="text-xs text-ink-400 leading-relaxed">
                        Pengharum ruangan, body mist, dan produk turunan aromatic lainnya untuk kebutuhan retail.
                    </p>
                </div>
            </div>
            <div class="lg:col-span-9 lg:col-start-4">
                <a href="https://qodha.id" target="_blank" rel="noopener noreferrer"
                   class="inline-flex items-center gap-2 text-sm text-white border-b border-ink-500 hover:border-white pb-0.5 transition-colors">
                    Lihat katalog lengkap di qodha.id
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
</section>

{{--
    ============================================================
    Kemitraan Section
    ============================================================
    PERUBAHAN: Hanya 3 level kemitraan resmi sesuai qodha.id/about:
      1. Distributor
      2. Agen
      3. Reseller
    "Super Distributor" DIHAPUS dari tampilan publik (tetap ada
    sebagai opsi kategori di database/admin untuk fleksibilitas
    data internal, tapi tidak dipromosikan ke calon mitra umum).
--}}
<section id="kemitraan" class="border-b border-ink-700">
    <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
        <div class="grid lg:grid-cols-12 gap-8">
            <div class="lg:col-span-3">
                <p class="text-[11px] uppercase tracking-[0.3em] text-ink-400 font-medium">Kemitraan</p>
                <h2 class="font-display font-bold text-2xl lg:text-3xl mt-2 text-white">Bergabung Bersama Kami</h2>
            </div>
            <div class="lg:col-span-9">
                <p class="text-sm text-ink-300 leading-relaxed max-w-2xl mb-6">
                    Qodha Aromatic membuka peluang kemitraan dengan join modal mulai dari 1 juta saja.
                    Tersedia 3 tingkatan kemitraan resmi sesuai skala bisnis Anda:
                </p>
                <div class="grid sm:grid-cols-3 gap-4">
                    <div class="section-hover border border-ink-700 rounded-lg p-5">
                        <span class="text-[10px] uppercase tracking-widest text-ink-400">Level 1</span>
                        <h4 class="font-display font-semibold text-white text-lg mt-1">Distributor</h4>
                        <p class="text-xs text-ink-400 mt-2 leading-relaxed">
                            Distribusi area kota/kabupaten dengan dukungan stok rutin &amp; harga grosir terbaik.
                        </p>
                    </div>
                    <div class="section-hover border border-ink-700 rounded-lg p-5">
                        <span class="text-[10px] uppercase tracking-widest text-ink-400">Level 2</span>
                        <h4 class="font-display font-semibold text-white text-lg mt-1">Agen</h4>
                        <p class="text-xs text-ink-400 mt-2 leading-relaxed">
                            Mitra penjual dengan modal lebih ringan dan sistem repeat order bulanan.
                        </p>
                    </div>
                    <div class="section-hover border border-ink-700 rounded-lg p-5">
                        <span class="text-[10px] uppercase tracking-widest text-ink-400">Level 3</span>
                        <h4 class="font-display font-semibold text-white text-lg mt-1">Reseller</h4>
                        <p class="text-xs text-ink-400 mt-2 leading-relaxed">
                            Penjualan retail dengan modal awal paling fleksibel, cocok untuk pemula.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{--
    ============================================================
    Syarat & Ketentuan Mitra Section
    ============================================================
    MENGGANTIKAN section "Statistik" yang sebelumnya tampil di
    landing page (statistik sekarang hanya di /admin/dashboard).
    Konten diambil & diringkas dari qodha.id/about (FAQ kemitraan).
--}}
<section id="syarat-ketentuan" class="border-b border-ink-700">
    <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
        <div class="grid lg:grid-cols-12 gap-8">
            <div class="lg:col-span-3">
                <p class="text-[11px] uppercase tracking-[0.3em] text-ink-400 font-medium">Persyaratan</p>
                <h2 class="font-display font-bold text-2xl lg:text-3xl mt-2 text-white">
                    Syarat &amp; Ketentuan<br/>Mendaftar Mitra
                </h2>
                <p class="text-xs text-ink-400 mt-4 leading-relaxed">
                    Masing-masing level mitra memiliki harga spesial dan fasilitas bonus menarik.
                </p>
            </div>

            <div class="lg:col-span-9 grid sm:grid-cols-3 gap-4">

                {{-- Distributor --}}
                <div class="section-hover border border-ink-700 rounded-lg p-5">
                    <h4 class="font-display font-semibold text-white text-lg mb-3">Distributor</h4>
                    <ul class="space-y-2 text-xs text-ink-300 leading-relaxed">
                        <li class="flex gap-2">
                            <span class="text-ink-500">&#9656;</span>
                            Pembelian pertama 6 karton / senilai Rp6.000.000 (bisa mix produk)
                        </li>
                        <li class="flex gap-2">
                            <span class="text-ink-500">&#9656;</span>
                            Pembelian ke-2 dan seterusnya minimal 3 karton / Rp3.000.000
                        </li>
                        <li class="flex gap-2">
                            <span class="text-ink-500">&#9656;</span>
                            Mengisi formulir pendaftaran mitra
                        </li>
                    </ul>
                </div>

                {{-- Agen --}}
                <div class="section-hover border border-ink-700 rounded-lg p-5">
                    <h4 class="font-display font-semibold text-white text-lg mb-3">Agen</h4>
                    <ul class="space-y-2 text-xs text-ink-300 leading-relaxed">
                        <li class="flex gap-2">
                            <span class="text-ink-500">&#9656;</span>
                            Pembelian pertama 3 karton / senilai Rp3.000.000 (bisa mix produk)
                        </li>
                        <li class="flex gap-2">
                            <span class="text-ink-500">&#9656;</span>
                            Pembelian ke-2 dan seterusnya minimal 1 karton / Rp1.000.000
                        </li>
                        <li class="flex gap-2">
                            <span class="text-ink-500">&#9656;</span>
                            Mengisi formulir pendaftaran mitra
                        </li>
                    </ul>
                </div>

                {{-- Reseller --}}
                <div class="section-hover border border-ink-700 rounded-lg p-5">
                    <h4 class="font-display font-semibold text-white text-lg mb-3">Reseller</h4>
                    <ul class="space-y-2 text-xs text-ink-300 leading-relaxed">
                        <li class="flex gap-2">
                            <span class="text-ink-500">&#9656;</span>
                            Pembelian pertama 1 karton / senilai Rp1.000.000 (bisa mix produk)
                        </li>
                        <li class="flex gap-2">
                            <span class="text-ink-500">&#9656;</span>
                            Pembelian ke-2 dan seterusnya minimal lusinan
                        </li>
                        <li class="flex gap-2">
                            <span class="text-ink-500">&#9656;</span>
                            Mengisi formulir pendaftaran mitra
                        </li>
                    </ul>
                </div>

                {{-- Ketentuan Umum Mitra --}}
                <div class="section-hover border border-ink-700 rounded-lg p-5 sm:col-span-3">
                    <h4 class="font-display font-semibold text-white text-sm uppercase tracking-widest mb-3">
                        Ketentuan Umum Mitra
                    </h4>
                    <div class="grid sm:grid-cols-3 gap-4 text-xs text-ink-300 leading-relaxed">
                        <p class="flex gap-2">
                            <span class="text-ink-500">&#9656;</span>
                            Repeat order minimal 1x per bulan untuk mempertahankan status mitra.
                        </p>
                        <p class="flex gap-2">
                            <span class="text-ink-500">&#9656;</span>
                            Tidak repeat order lebih dari 3 bulan akan dihapus dari keanggotaan mitra
                            (kehilangan harga khusus mitra).
                        </p>
                        <p class="flex gap-2">
                            <span class="text-ink-500">&#9656;</span>
                            Mitra dapat mengajukan keluhan, kendala, maupun saran kapan saja.
                        </p>
                    </div>
                    <p class="text-[11px] text-ink-500 mt-4 italic">
                        Catatan: Syarat dan ketentuan dapat berubah sewaktu-waktu sesuai kebijakan Qodha Aromatic.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
