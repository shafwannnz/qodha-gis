{{--
    ============================================================
    Komponen Peta Leaflet — dengan "Gate" Section
    ============================================================
    PERUBAHAN:
    Sebelumnya peta langsung tampil saat halaman dimuat. Sesuai
    referensi (footer Dobha Perfumes — tampilan ringkas/branded
    sebelum konten utama), sekarang peta DISEMBUNYIKAN di belakang
    sebuah "cover section" berisi judul besar, deskripsi singkat,
    dan tombol CTA "Cek Lokasi Mitra Sekarang".

    User harus klik tombol tersebut untuk memuat & menampilkan
    peta + filter panel. Ini mengurangi beban awal (peta tidak
    langsung fetch GeoJSON saat page load) dan membuat landing
    page terasa lebih ringan/elegan seperti referensi.

    Implementasi:
    - #map-gate  : section cover (selalu terlihat di awal)
    - #map-panel : container peta+filter, default hidden
    - public/js/leaflet-map.js TIDAK auto-init saat load;
      inisialisasi dipanggil oleh public/js/map-gate.js setelah
      tombol diklik & #map-panel ditampilkan.
--}}

<section id="peta" class="border-b border-ink-700">
    <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24">

        {{-- ===================== MAP GATE (cover) ===================== --}}
        <div id="map-gate" class="text-center">
            <p class="text-[11px] uppercase tracking-[0.3em] text-ink-400 mb-4 font-medium">
                Persebaran Mitra
            </p>
            <h2 class="font-display font-bold text-3xl sm:text-4xl lg:text-6xl tracking-tight text-white leading-tight">
                Cari Mitra Qodha<br/>
                <span class="text-ink-400">Terdekat di Lokasi Anda</span>
            </h2>
            <p class="mt-6 text-ink-300 text-sm sm:text-base max-w-xl mx-auto leading-relaxed">
                Temukan jaringan distributor, agen, dan reseller resmi Qodha Aromatic
                di seluruh Indonesia. Aktifkan lokasi Anda untuk menemukan mitra
                terdekat, atau jelajahi peta secara manual.
            </p>

            <button id="open-map-btn" type="button"
                class="mt-8 inline-flex items-center gap-2 bg-white text-ink-900 font-display font-semibold text-sm px-6 py-3 rounded-md hover:bg-ink-100 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v8.25m.503 3.498l4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 00-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0z" />
                </svg>
                Cek Lokasi Mitra Sekarang
            </button>

            {{-- Mini stat hint --}}
            <p class="mt-4 text-xs text-ink-500">
                {{ $stats['total'] }} mitra terdaftar &middot; {{ $stats['aktif'] }} aktif saat ini
            </p>
        </div>

        {{-- ===================== MAP PANEL (hidden by default) ===================== --}}
        <div id="map-panel" class="hidden mt-10">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <p class="text-[11px] uppercase tracking-[0.3em] text-ink-400 font-medium">Sebaran</p>
                    <h3 class="font-display font-bold text-2xl lg:text-3xl mt-2 text-white">Peta Mitra Interaktif</h3>
                </div>
                <button id="close-map-btn" type="button"
                    class="text-[11px] px-3 py-1.5 border border-ink-600 rounded text-ink-300 hover:text-white hover:border-ink-400 transition-colors">
                    Tutup Peta
                </button>
            </div>

            <div class="grid lg:grid-cols-12 gap-6">
                <div class="lg:col-span-3">
                    @include('components.filter-panel', ['wilayahs' => $wilayahs, 'kategoris' => $kategoris])
                </div>

                <div class="lg:col-span-9">
                    @include('components.map')
                </div>
            </div>
        </div>

    </div>
</section>
