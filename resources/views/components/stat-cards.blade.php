{{--
    Komponen Statistik Cards
    Props: $stats (array dengan keys: total, aktif, nonAktif, perWilayah, perKategori)
--}}

@php
    $topWilayah = collect($stats['perWilayah'] ?? [])->first();
    $topWilayahName = collect($stats['perWilayah'] ?? [])->keys()->first() ?? '-';
    $topWilayahCount = $topWilayah ?? 0;
@endphp

<div class="grid grid-cols-2 lg:grid-cols-5 gap-3 lg:gap-4">

    {{-- Total Mitra --}}
    <div class="section-hover border border-ink-700 bg-ink-800/50 rounded-lg p-4 lg:p-5" data-stat="total">
        <div class="flex items-center justify-between mb-2">
            <span class="text-[10px] uppercase tracking-widest text-ink-400 font-medium">Total Mitra</span>
            <svg class="w-4 h-4 text-ink-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
            </svg>
        </div>
        <div class="font-display text-3xl lg:text-4xl font-bold text-white" id="stat-total">
            {{ $stats['total'] }}
        </div>
        <div class="text-xs text-ink-400 mt-1">mitra terdaftar</div>
    </div>

    {{-- Mitra Aktif --}}
    <div class="section-hover border border-ink-700 bg-ink-800/50 rounded-lg p-4 lg:p-5" data-stat="aktif">
        <div class="flex items-center justify-between mb-2">
            <span class="text-[10px] uppercase tracking-widest text-ink-400 font-medium">Mitra Aktif</span>
            <span class="w-2 h-2 rounded-full bg-white"></span>
        </div>
        <div class="font-display text-3xl lg:text-4xl font-bold text-white" id="stat-aktif">
            {{ $stats['aktif'] }}
        </div>
        <div class="text-xs text-ink-400 mt-1">
            {{ $stats['total'] > 0 ? round($stats['aktif'] / $stats['total'] * 100) : 0 }}% dari total
        </div>
    </div>

    {{-- Mitra Non Aktif --}}
    <div class="section-hover border border-ink-700 bg-ink-800/50 rounded-lg p-4 lg:p-5" data-stat="nonaktif">
        <div class="flex items-center justify-between mb-2">
            <span class="text-[10px] uppercase tracking-widest text-ink-400 font-medium">Non Aktif</span>
            <span class="w-2 h-2 rounded-full bg-ink-500"></span>
        </div>
        <div class="font-display text-3xl lg:text-4xl font-bold text-ink-300" id="stat-nonaktif">
            {{ $stats['nonAktif'] }}
        </div>
        <div class="text-xs text-ink-400 mt-1">tidak aktif</div>
    </div>

    {{-- Wilayah Terbanyak --}}
    <div class="section-hover border border-ink-700 bg-ink-800/50 rounded-lg p-4 lg:p-5" data-stat="wilayah">
        <div class="flex items-center justify-between mb-2">
            <span class="text-[10px] uppercase tracking-widest text-ink-400 font-medium">Wilayah Teratas</span>
            <svg class="w-4 h-4 text-ink-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
            </svg>
        </div>
        <div class="font-display text-xl lg:text-2xl font-bold text-white truncate" title="{{ $topWilayahName }}">
            {{ $topWilayahName }}
        </div>
        <div class="text-xs text-ink-400 mt-1">{{ $topWilayahCount }} mitra</div>
    </div>

    {{-- Total Kontak/HP terdaftar --}}
    <div class="section-hover border border-ink-700 bg-ink-800/50 rounded-lg p-4 lg:p-5 col-span-2 lg:col-span-1" data-stat="kontak">
        <div class="flex items-center justify-between mb-2">
            <span class="text-[10px] uppercase tracking-widest text-ink-400 font-medium">Kontak Terdaftar</span>
            <svg class="w-4 h-4 text-ink-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
            </svg>
        </div>
        <div class="font-display text-3xl lg:text-4xl font-bold text-white" id="stat-kontak">
            {{ $stats['total'] }}
        </div>
        <div class="text-xs text-ink-400 mt-1">nomor WhatsApp/HP</div>
    </div>

</div>
