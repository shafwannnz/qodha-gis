{{--
    Komponen Filter Panel / Sidebar
    Props: $wilayahs (array), $kategoris (array)

    PERBAIKAN BUG:
    - Class mobile sidebar diubah dari util Tailwind ad-hoc menjadi
      satu class terdefinisi: "sidebar-mobile-open" (lihat app.blade.php)
      agar background solid & z-index konsisten, tidak overlap dengan
      section lain.
--}}

<div id="sidebar" class="lg:sticky lg:top-20 h-fit border border-ink-700 bg-ink-800/50 rounded-lg p-4 lg:p-5 space-y-5">

    <div class="flex items-center justify-between">
        <h3 class="font-display font-semibold text-sm uppercase tracking-widest text-ink-200">Filter Mitra</h3>
        <button id="reset-filter" type="button" class="text-[11px] text-ink-400 hover:text-white underline underline-offset-2">
            Reset
        </button>
    </div>

    {{-- Lokasi Terkini --}}
    <div>
        <button id="locate-me-btn" type="button" class="w-full text-xs border border-ink-600 rounded py-2 text-ink-200 hover:text-white hover:border-ink-400 transition-colors flex items-center justify-center gap-2">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
            </svg>
            <span id="locate-me-label">Gunakan Lokasi Saya</span>
        </button>
        <div id="nearest-result" class="hidden mt-2 text-xs text-ink-300 border border-ink-700 rounded p-2 leading-relaxed"></div>
    </div>

    {{-- Search bar --}}
    <div>
        <label class="block text-[11px] uppercase tracking-wider text-ink-400 mb-1.5" for="search-input">
            Cari Mitra
        </label>
        <div class="relative">
            <input
                type="text"
                id="search-input"
                placeholder="Nama atau wilayah..."
                class="dark-input pl-9"
                autocomplete="off"
            >
            <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-ink-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
            </svg>
        </div>
    </div>

    {{-- Filter Status --}}
    <div>
        <label class="block text-[11px] uppercase tracking-wider text-ink-400 mb-1.5" for="filter-status">
            Status Mitra
        </label>
        <select id="filter-status" class="dark-input">
            <option value="">Semua Status</option>
            <option value="Aktif">Aktif</option>
            <option value="Non Aktif">Non Aktif</option>
        </select>
    </div>

    {{-- Filter Kategori --}}
    <div>
        <label class="block text-[11px] uppercase tracking-wider text-ink-400 mb-1.5" for="filter-kategori">
            Kategori Mitra
        </label>
        <select id="filter-kategori" class="dark-input">
            <option value="">Semua Kategori</option>
            @foreach ($kategoris as $kategori)
                <option value="{{ $kategori }}">{{ $kategori }}</option>
            @endforeach
        </select>
    </div>

    {{-- Filter Wilayah --}}
    <div>
        <label class="block text-[11px] uppercase tracking-wider text-ink-400 mb-1.5" for="filter-wilayah">
            Wilayah
        </label>
        <select id="filter-wilayah" class="dark-input">
            <option value="">Semua Wilayah</option>
            @foreach ($wilayahs as $wilayah)
                <option value="{{ $wilayah }}">{{ $wilayah }}</option>
            @endforeach
        </select>
    </div>

    {{-- Hasil counter --}}
    <div class="pt-2 border-t border-ink-700">
        <p class="text-xs text-ink-400">
            Menampilkan <span id="result-count" class="text-white font-semibold">0</span> mitra
        </p>
    </div>

    {{-- Toggle Layer Peta --}}
    <div class="pt-2 border-t border-ink-700 space-y-2">
        <h4 class="text-[11px] uppercase tracking-wider text-ink-400 mb-2">Tampilan Peta</h4>

        <label class="flex items-center justify-between text-xs text-ink-200 cursor-pointer">
            Marker Cluster
            <input type="checkbox" id="toggle-markers" checked class="rounded border-ink-600 bg-ink-900 text-white focus:ring-0">
        </label>
        <label class="flex items-center justify-between text-xs text-ink-200 cursor-pointer">
            Heatmap Kepadatan
            <input type="checkbox" id="toggle-heatmap" class="rounded border-ink-600 bg-ink-900 text-white focus:ring-0">
        </label>
        <label class="flex items-center justify-between text-xs text-ink-200 cursor-pointer">
            Choropleth Wilayah
            <input type="checkbox" id="toggle-choropleth" class="rounded border-ink-600 bg-ink-900 text-white focus:ring-0">
        </label>
    </div>

    {{-- Legenda Peta --}}
    <div class="pt-2 border-t border-ink-700 space-y-2">
        <h4 class="text-[11px] uppercase tracking-wider text-ink-400 mb-2">Legenda</h4>

        <div class="flex items-center gap-2 text-xs text-ink-200">
            <span class="w-3 h-3 rounded-full bg-white border border-ink-300 inline-block"></span>
            Mitra Aktif
        </div>
        <div class="flex items-center gap-2 text-xs text-ink-200">
            <span class="w-3 h-3 rounded-full bg-ink-500 border border-ink-600 inline-block"></span>
            Mitra Non Aktif
        </div>
        <div class="flex items-center gap-2 text-xs text-ink-200 pt-1">
            <span class="w-4 h-4 rounded-full border border-ink-300 flex items-center justify-center text-[9px] font-bold bg-ink-700">12</span>
            Cluster Marker
        </div>
        <div class="flex items-center gap-2 text-xs text-ink-200 pt-1">
            <span class="w-3 h-3 rounded-full bg-white border-2 border-ink-900 inline-block user-location-pulse"></span>
            Lokasi Anda
        </div>
    </div>

</div>

{{-- Mobile sidebar toggle button --}}
<button id="filter-toggle-btn"
    class="lg:hidden fixed bottom-5 right-5 z-50 bg-white text-ink-900 rounded-full w-12 h-12 flex items-center justify-center shadow-lg shadow-black/50"
    aria-label="Buka filter">
    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75" />
    </svg>
</button>

<div id="sidebar-overlay"></div>
